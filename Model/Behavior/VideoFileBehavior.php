<?php
/**
 * Video File Behavior
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

/**
 * Summary for Video File Behavior
 */
class VideoFileBehavior extends ModelBehavior {

/**
 * @var array 設定
 */
	public $settings = array();

/**
 * setup
 *
 * @param Model $Model モデル
 * @param array $settings 設定値
 * @return void
 */
	public function setup(Model $Model, $settings = array()) {
		$this->settings[$Model->alias] = $settings;
	}

/**
 * ファイルチェック
 *
 * @param Model $Model モデル
 * @param array $data received post data
 * @param string $field ファイルのフィールド名
 * @param string $modelAlias モデル名
 * @param string $colom セットするDBカラム名
 * @param int $index File inputのindex
 * @return mixed Array on success, false on error
 */
	public function validateVideoFile(Model $Model, $data, $field, $modelAlias, $colom, $index = 0) {
		//更新用：ファイル更新の準備
		$data = $this->__readyUpdateFile($data, $field, $modelAlias, $colom, $index);

		//更新用：ファイル削除のvalidate
		if (isset($data['DeleteFile'][$index]['File']['id']) && $data['DeleteFile'][$index]['File']['id'] > 0) {
			if (! $deleteFile = $Model->FileModel->validateDeletedFiles($data['DeleteFile'][$index]['File']['id'])) {
				$Model->validationErrors = Hash::merge($Model->validationErrors, $Model->FileModel->validationErrors);
				return false;
			}
			$data['DeleteFile'][$index] = $deleteFile[0];
		}

		//ファイルのvalidate
		if (isset($data[$field])) {
			if (! $Model->FileModel->validateFile($data[$field])) {
				$Model->validationErrors = Hash::merge($Model->validationErrors, $Model->FileModel->validationErrors);
				return false;
			}
			if (! $Model->FileModel->validateFileAssociated($data[$field])) {
				$Model->validationErrors = Hash::merge($Model->validationErrors, $Model->FileModel->validationErrors);
				return false;
			}
		}

		return $data;
	}

/**
 * ファイル更新の準備
 *
 * @param array $data received post data
 * @param string $field ファイルのフィールド名
 * @param string $modelAlias モデル名
 * @param string $colom セットするDBカラム名
 * @param int $index File inputのindex
 * @return mixed Array on success, false on error
 */
	private function __readyUpdateFile($data, $field, $modelAlias, $colom, $index = 0) {
		//ファイル更新は、今までのファイルを削除,新しいファイルを登録する
		//そのためここで、今までのファイルの削除準備をしている
		if (isset($data[$field]) && isset($data[$modelAlias][$colom]) && $data[$modelAlias][$colom] !== null) {
			$data['DeleteFile'][$index]['File'] = array(
				'id' => $data[$modelAlias][$colom]
			);
		}
		return $data;
	}

/**
 * ファイルの登録
 * ファイル更新は、delete⇒insert
 *
 * @param Model $Model モデル
 * @param array $data received post data
 * @param string $field ファイルのフィールド名
 * @param string $modelAlias 登録するモデル名
 * @param string $colom 登録するDBカラム名
 * @param int $index File inputのindex
 * @return mixed Array on success, false on error
 * @throws InternalErrorException
 */
	public function saveVideoFile(Model $Model, $data, $field, $modelAlias, $colom, $index = 0) {
		//更新用：ファイル削除
		$data = $this->deleteFile($Model, $data, $modelAlias, $colom, $index);

		//ファイル登録
		if (isset($data[$field])) {
			// 新規作成
			$Model->FileModel->create();

			// 更新用：ファイル上書き = 上書きチェックON、今までのファイルあり
			if (isset($data['OverwriteFile'][$index]['File']['id']) && $data['OverwriteFile'][$index]['File']['id'] > 0 &&
				isset($data['DeleteFile'][$index]['File']['id']) && $data['DeleteFile'][$index]['File']['id'] > 0) {
				// 今までのファイルのslugを使う = ファイルのURLが変わらない
				$data[$field]['File']['slug'] = $data['DeleteFile'][$index]['File']['slug'];
				// バグのため暫定対応(;'∀') https://github.com/NetCommons3/Files/issues/2
				$data[$field]['File']['original_name'] = $data['DeleteFile'][$index]['File']['slug'];
			}

			if (! $file = $Model->FileModel->save(
				$data[$field],
				array('validate' => false, 'callbacks' => 'before')
			)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}
			if (! $Model->FileModel->saveFileAssociated($file)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}
			$data[$field] = Hash::insert(
				$data[$field], '{s}.id', (int)$file[$Model->FileModel->alias]['id']
			);
			$data[$modelAlias][$colom] = $data[$field][$Model->FileModel->alias]['id'];
		}

		return $data;
	}

/**
 * ファイル削除
 *
 * @param Model $Model モデル
 * @param array $data received post data
 * @param string $modelAlias 登録するモデル名
 * @param string $colom 登録するDBカラム名
 * @param int $index File inputのindex
 * @return mixed Array on success, false on error
 * @throws InternalErrorException
 */
	public function deleteFile(Model $Model, $data, $modelAlias, $colom, $index = 0) {
		if (isset($data['DeleteFile'][$index]['File']['id']) && $data['DeleteFile'][$index]['File']['id'] > 0) {

			//データ削除
			if (!$Model->FileModel->deleteAll(['id' => $data['DeleteFile'][$index]['File']['id']], true, false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}
			if (!$Model->FileModel->deleteFileAssociated($data['DeleteFile'][$index]['File']['id'])) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			// 暫定対応(;'∀') コメントアウトする。
			// 現在、path=フォルダなので、フォルダ削除になっている。2ファイルを1度にアップロードすると同じフォルダにアップロードされる。
			// 更新時に1ファイルだけアップロードすると、下記フォルダ削除によりもう一方のファイルが消える問題あり。
			//
			// 1ファイルのアップロード毎にフォルダが別になれば、下記フォルダ削除のままでも問題解消する。
			//ファイル削除
			//$folder = new Folder();
			//$folder->delete($data['DeleteFile'][$index]['File']['path']);
			// サムネイルだったら、個別に削除したので、現在のままでも大丈夫

			// ファイル削除
			$file = new File($data['DeleteFile'][$index]['File']['path'] . $data['DeleteFile'][$index]['File']['name']);
			$file->delete();

			// サムネイル削除
			$this->__deleteThumbnail($data['DeleteFile'][$index]['File'], 'url_big');
			$this->__deleteThumbnail($data['DeleteFile'][$index]['File'], 'url_medium');
			$this->__deleteThumbnail($data['DeleteFile'][$index]['File'], 'url_small');
			$this->__deleteThumbnail($data['DeleteFile'][$index]['File'], 'url_thumbnail');

			// 空ならアップロードディレクトリ削除
			$folder = new Folder($data['DeleteFile'][$index]['File']['path']);
			if ($folder->dirsize() === 0) {
				$folder->delete();
			}

			$data[$modelAlias][$colom] = 0;
		}

		return $data;
	}

/**
 * サムネイル削除
 *
 * @param array $deletefile delete file data
 * @param array $targetArrayKey target array key
 * @return void
 */
	private function __deleteThumbnail($deletefile, $targetArrayKey) {
		if (isset($deletefile[$targetArrayKey])) {
			$thumbnailUrl = $deletefile[$targetArrayKey];
			$thumbnailUrlArray = explode(DS, $thumbnailUrl);
			$thumbnailName = array_pop($thumbnailUrlArray);

			//ファイル削除
			$file = new File($deletefile['path'] . $thumbnailName);
			$file->delete();
		}
	}
}