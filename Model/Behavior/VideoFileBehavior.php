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
 * 暫定部品(;'∀') Filesプラグインに同等機能が実装される想定
 */
class VideoFileBehavior extends ModelBehavior {

/**
 * ファイルチェック
 *
 * @param Model $Model モデル
 * @param array $data received post data
 * @param string $fileField ファイルのフィールド名
 * @param string $alias モデル名
 * @param string $fileIdColom ファイルIDのDBカラム名
 * @param int $index File inputのindex
 * @return mixed Array on success, false on error
 */
	public function validateVideoFile(Model $Model, $data, $fileField, $alias, $fileIdColom, $index = 0) {
		//更新用：ファイル更新の準備
		$data = $this->__readyUpdateFile($Model, $data, $fileField, $alias, $fileIdColom, $index);

		//ファイルのvalidate
		if (isset($data[$fileField])) {
			if (! $Model->FileModel->validateFile($data[$fileField])) {
				$Model->validationErrors = Hash::merge($Model->validationErrors, $Model->FileModel->validationErrors);
				return false;
			}
			if (! $Model->FileModel->validateFileAssociated($data[$fileField])) {
				$Model->validationErrors = Hash::merge($Model->validationErrors, $Model->FileModel->validationErrors);
				return false;
			}
		}

		return $data;
	}

/**
 * ファイル更新の準備
 *
 * @param Model $Model モデル
 * @param array $data received post data
 * @param string $fileField ファイルのフィールド名
 * @param string $alias モデル名
 * @param string $fileIdColom ファイルIDのDBカラム名
 * @param int $index File inputのindex
 * @return mixed Array on success, false on error
 */
	private function __readyUpdateFile(Model $Model, $data, $fileField, $alias, $fileIdColom, $index = 0) {
		//ファイル更新は、今までのファイルを削除,新しいファイルを登録する
		//そのためここで、今までのファイルの削除準備をしている
		// 例)     $data['thumbnail']           $data['Video']['thumbnail_id']
		if (isset($data[$fileField]) && isset($data[$alias][$fileIdColom]) && $data[$alias][$fileIdColom] !== null) {

			// FileModelをfind
			$fileId = $data[$alias][$fileIdColom];
			if (! $deleteFile = $Model->FileModel->validateDeletedFiles($fileId)) {
				$Model->validationErrors = Hash::merge($Model->validationErrors, $Model->FileModel->validationErrors);
				return false;
			}
			$data['DeleteFile'][$index] = $deleteFile[0];
		}

		return $data;
	}

/**
 * ファイルの登録
 * ファイル更新は、delete⇒insert
 *
 * @param Model $Model モデル
 * @param array $data received post data
 * @param string $fileField ファイルのフィールド名
 * @param string $alias 登録するモデル名
 * @param string $fileIdColom ファイルIDのDBカラム名
 * @param int $index File inputのindex
 * @return mixed Array on success, false on error
 * @throws InternalErrorException
 */
	public function saveVideoFile(Model $Model, $data, $fileField, $alias, $fileIdColom, $index = 0) {
		//更新用：ファイル削除
		$data = $this->deleteFile($Model, $data, $alias, $fileIdColom, $index);

		//ファイル登録
		if (isset($data[$fileField])) {
			// 新規作成
			$Model->FileModel->create();

			// 更新用：ファイル上書き = 上書きチェックON、今までのファイルあり
			if (isset($data['OverwriteFile'][$index]['File']['id']) && $data['OverwriteFile'][$index]['File']['id'] > 0 &&
				isset($data['DeleteFile'][$index]['File']['id']) && $data['DeleteFile'][$index]['File']['id'] > 0) {
				// 今までのファイルのslugを使う = ファイルのURLが変わらない
				$data[$fileField]['File']['slug'] = $data['DeleteFile'][$index]['File']['slug'];
				// バグのため暫定対応(;'∀') https://github.com/NetCommons3/Files/issues/2
				$data[$fileField]['File']['original_name'] = $data['DeleteFile'][$index]['File']['slug'];
			}

			if (! $file = $Model->FileModel->save(
				$data[$fileField],
				array('validate' => false, 'callbacks' => 'before')
			)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}
			if (! $Model->FileModel->saveFileAssociated($file)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}
			$data[$fileField] = Hash::insert(
				$data[$fileField], '{s}.id', (int)$file[$Model->FileModel->alias]['id']
			);
			$data[$alias][$fileIdColom] = $data[$fileField][$Model->FileModel->alias]['id'];
		}

		return $data;
	}

/**
 * ファイル削除
 *
 * @param Model $Model モデル
 * @param array $data received post data
 * @param string $alias 登録するモデル名
 * @param string $fileIdColom ファイルIDのDBカラム名
 * @param int $index File inputのindex
 * @return mixed Array on success, false on error
 * @throws InternalErrorException
 */
	public function deleteFile(Model $Model, $data, $alias, $fileIdColom, $index = 0) {
		if (isset($data['DeleteFile'][$index]['File']['id']) && $data['DeleteFile'][$index]['File']['id'] > 0) {

			//データ削除
			if (!$Model->FileModel->deleteAll([$Model->FileModel->alias . '.id' => $data['DeleteFile'][$index]['File']['id']], true, false)) {
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

			$data[$alias][$fileIdColom] = 0;
		}

		return $data;
	}

/**
 * サムネイル削除
 *
 * @param array $deletefile delete file data
 * @param array $key target array key
 * @return void
 */
	private function __deleteThumbnail($deletefile, $key) {
		if (isset($deletefile[$key])) {
			$thumbnailUrl = $deletefile[$key];
			$thumbnailUrlArray = explode(DS, $thumbnailUrl);
			$thumbnailName = array_pop($thumbnailUrlArray);

			//ファイル削除
			$file = new File($deletefile['path'] . $thumbnailName);
			$file->delete();
		}
	}
}