<?php
/**
 * VideoFileBehaviorExceptionTest Case
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('VideoAppTest', 'Videos.Test/Case/Model');
App::uses('CakeSession', 'Model/Datasource');

/**
 * Summary for VideoFileBehaviorExceptionTest Case
 */
class VideoFileBehaviorExceptionTest extends VideoAppTest {

/**
 * ファイルの登録 例外テスト
 *
 * @return void
 * @throws Exception
 */
	public function testSaveVideoFileException() {
		$this->setExpectedException('InternalErrorException');

		$video = array(
			'Video' => array(
				'id' => 1,
				//'mp4_id' => 3	// video2.MOV
				'mp4_id' => 1	// video1.mp4
			),
			Video::VIDEO_FILE_FIELD => array(
				'FilesPlugin' => array(
					'plugin_key' => 'videos'
				)
			),
		);
		$roomId = 1;

		// テストファイル準備
		$fileName = 'video1.mp4';
		$contentsId = $video['Video']['mp4_id'];
		$this->_readyTestFile($contentsId, $roomId, $fileName);

		// 元動画 取得
		$noConvert = $this->Video->FileModel->findById($video['Video']['mp4_id']);

		// アップロードファイルの受け取りと移動
		$noConvertPath = $noConvert['File']["path"];
		$noConvertSlug = $noConvert['File']["slug"];
		//$noConvertExtension = $noConvert['File']["extension"];

		// サムネイル名は動画名で末尾jpgにしたものをセット
		$videoName = explode('.', $noConvert['File']["name"])[0];

		$data[Video::VIDEO_FILE_FIELD]['File']['type'] = 'video/mp4';
		$data[Video::VIDEO_FILE_FIELD]['File']['mimetype'] = 'video/mp4';
		$data[Video::VIDEO_FILE_FIELD]['File']['path'] = '{ROOT}' . 'videos' . '{DS}' . $roomId . '{DS}' . $video['Video']['id'] . '{DS}';
		$data[Video::VIDEO_FILE_FIELD]['File']['name'] = $videoName . '.mp4';
		$data[Video::VIDEO_FILE_FIELD]['File']['alt'] = $videoName . '.mp4';
		$data[Video::VIDEO_FILE_FIELD]['File']['extension'] = 'mp4';
		$data[Video::VIDEO_FILE_FIELD]['File']['tmp_name'] = $noConvertPath . $noConvertSlug . '.mp4';
		$data[Video::VIDEO_FILE_FIELD]['File']['size'] = filesize($noConvertPath . $noConvertSlug . '.mp4');

		$data[Video::VIDEO_FILE_FIELD]['File']['slug'] = 'video1';
		$data[Video::VIDEO_FILE_FIELD]['File']['role_type'] = 'room_file_role';

		// modelモック
		$fileModelMock = $this->getMockForModel('Files.FileModel', ['save']);
		$fileModelMock->expects($this->any())
			->method('save')
			->will($this->returnValue(false));
		$this->Video->FileModel = $fileModelMock;

		try {
			// ファイルの登録 変換後動画ファイル
			$data = $this->Video->saveVideoFile($data, Video::VIDEO_FILE_FIELD, $this->Video->alias, 'mp4_id', 0);
		} catch (Exception $e) {
			// テストファイル削除
			$this->_deleteTestFile();
			throw $e;
		}
	}

/**
 * ファイルの登録 saveFileAssociated 例外テスト
 *
 * @return void
 * @throws Exception
 */
	public function testSaveVideoFileSaveFileAssociatedException() {
		$this->setExpectedException('InternalErrorException');

		$video = array(
			'Video' => array(
				'id' => 1,
				//'mp4_id' => 3	// video2.MOV
				'mp4_id' => 1	// video1.mp4
			),
			Video::VIDEO_FILE_FIELD => array(
				'FilesPlugin' => array(
					'plugin_key' => 'videos'
				)
			),
		);
		$roomId = 1;

		// テストファイル準備
		$fileName = 'video1.mp4';
		$contentsId = $video['Video']['mp4_id'];
		$this->_readyTestFile($contentsId, $roomId, $fileName);

		// 元動画 取得
		$noConvert = $this->Video->FileModel->findById($video['Video']['mp4_id']);

		// アップロードファイルの受け取りと移動
		$noConvertPath = $noConvert['File']["path"];
		$noConvertSlug = $noConvert['File']["slug"];
		//$noConvertExtension = $noConvert['File']["extension"];

		// サムネイル名は動画名で末尾jpgにしたものをセット
		$videoName = explode('.', $noConvert['File']["name"])[0];

		$data[Video::VIDEO_FILE_FIELD]['File']['type'] = 'video/mp4';
		$data[Video::VIDEO_FILE_FIELD]['File']['mimetype'] = 'video/mp4';
		$data[Video::VIDEO_FILE_FIELD]['File']['path'] = '{ROOT}' . 'videos' . '{DS}' . $roomId . '{DS}' . $video['Video']['id'] . '{DS}';
		$data[Video::VIDEO_FILE_FIELD]['File']['name'] = $videoName . '.mp4';
		$data[Video::VIDEO_FILE_FIELD]['File']['alt'] = $videoName . '.mp4';
		$data[Video::VIDEO_FILE_FIELD]['File']['extension'] = 'mp4';
		$data[Video::VIDEO_FILE_FIELD]['File']['tmp_name'] = $noConvertPath . $noConvertSlug . '.mp4';
		$data[Video::VIDEO_FILE_FIELD]['File']['size'] = filesize($noConvertPath . $noConvertSlug . '.mp4');

		$data[Video::VIDEO_FILE_FIELD]['File']['slug'] = 'video1';
		$data[Video::VIDEO_FILE_FIELD]['File']['role_type'] = 'room_file_role';

		// modelモック
		$fileModelMock = $this->getMockForModel('Files.FileModel', ['saveFileAssociated']);
		$fileModelMock->expects($this->any())
			->method('save')
			->will($this->returnValue(false));
		$this->Video->FileModel = $fileModelMock;

		try {
			// ファイルの登録 変換後動画ファイル
			$data = $this->Video->saveVideoFile($data, Video::VIDEO_FILE_FIELD, $this->Video->alias, 'mp4_id', 0);
		} catch (Exception $e) {
			// テストファイル削除
			$this->_deleteTestFile();
			throw $e;
		}
	}

/**
 * ファイル削除 例外テスト
 *
 * @return void
 * @throws Exception
 */
	public function testDeleteFileException() {
		$this->setExpectedException('InternalErrorException');

		$video = array(
			'Video' => array(
				'id' => 2,
				'thumbnail_id' => 2,	// thumbnail1.jpg
			),
		);
		$roomId = 1;

		// テストファイル準備
		$fileName = 'thumbnail1.jpg';
		$contentsId = $video['Video']['thumbnail_id'];
		$this->_readyTestFile($contentsId, $roomId, $fileName);

		$filePath = TMP . 'tests' . DS . 'file' . DS . $roomId . DS . $contentsId;
		$file = new File(APP . 'Plugin' . DS . 'Videos' . DS . 'Test' . DS . 'Fixture' . DS . $fileName);
		$file->copy($filePath . DS . 'thumbnail1_big.jpg');
		$file->copy($filePath . DS . 'thumbnail1_medium.jpg');
		$file->copy($filePath . DS . 'thumbnail1_small.jpg');
		$file->copy($filePath . DS . 'thumbnail1_thumbnail.jpg');
		$file->close();

		// 元動画 取得
		$noConvert = $this->Video->FileModel->findById($video['Video']['thumbnail_id']);

		// アップロードファイルの受け取りと移動
		$noConvertPath = $noConvert['File']["path"];
		$thumbnailSlug = $noConvert['File']["slug"];
		//$noConvertExtension = $noConvert['File']["extension"];

		// サムネイル名は動画名で末尾jpgにしたものをセット
		$videoName = explode('.', $noConvert['File']["name"])[0];
		$pluginKey = 'videos';

		// サムネイルデータ準備
		$data['Video'][Video::THUMBNAIL_FIELD]['name'] = $videoName . '.jpg';	// サムネイル名は動画名で末尾jpgにしたものをセット
		$data['Video'][Video::THUMBNAIL_FIELD]['type'] = 'image/jpeg';
		$data['Video'][Video::THUMBNAIL_FIELD]['tmp_name'] = $noConvertPath . $thumbnailSlug . '.jpg';
		$data['Video'][Video::THUMBNAIL_FIELD]['error'] = UPLOAD_ERR_OK;
		$data['Video'][Video::THUMBNAIL_FIELD]['size'] = filesize($noConvertPath . $thumbnailSlug . '.jpg');

		// Filesテーブルにサムネイルを登録
		$data[Video::THUMBNAIL_FIELD]['File']['status'] = 1;
		$data[Video::THUMBNAIL_FIELD]['File']['role_type'] = 'room_file_role';
		$data[Video::THUMBNAIL_FIELD]['File']['name'] = $videoName . '.jpg';		// サムネイル名は動画名をjpgにしたものをセット
		$data[Video::THUMBNAIL_FIELD]['File']['alt'] = $videoName . '.jpg';
		$data[Video::THUMBNAIL_FIELD]['File']['mimetype'] = 'image/jpeg';
		$data[Video::THUMBNAIL_FIELD]['File']['path'] = '{ROOT}' . 'videos' . '{DS}' . $roomId . '{DS}';		// 自動的に $video['Video']['id'] . '{DS}' が末尾に追記されるので、ここでは追記しない
		$data[Video::THUMBNAIL_FIELD]['File']['extension'] = 'jpg';
		$data[Video::THUMBNAIL_FIELD]['File']['tmp_name'] = $noConvertPath . $thumbnailSlug . '.jpg';
		$data[Video::THUMBNAIL_FIELD]['File']['size'] = filesize($noConvertPath . $thumbnailSlug . '.jpg');
		$data[Video::THUMBNAIL_FIELD]['File']['slug'] = $thumbnailSlug;
		$data[Video::THUMBNAIL_FIELD]['File']['original_name'] = $thumbnailSlug;

		$data[Video::THUMBNAIL_FIELD]['FilesPlugin']['plugin_key'] = $pluginKey;	// plugin_keyは、元動画のをセット
		$data[Video::THUMBNAIL_FIELD]['FilesRoom']['room_id'] = $roomId;
		$data[Video::THUMBNAIL_FIELD]['FilesUser']['user_id'] = 1;

		// いままでのファイル 削除対象
		$data['Video']['thumbnail_id'] = 2;

		// ファイルチェック 変換後動画ファイル
		$data = $this->Video->validateVideoFile($data, Video::THUMBNAIL_FIELD, $this->Video->alias, 'thumbnail_id', 0);

		// modelモック
		$fileModelMock = $this->getMockForModel('Files.FileModel', ['deleteAll']);
		$fileModelMock->expects($this->any())
			->method('save')
			->will($this->returnValue(false));
		$this->Video->FileModel = $fileModelMock;

		try {
			// ファイル削除
			$data = $this->Video->deleteFile($data, $this->Video->alias, 'thumbnail_id', 0);
		} catch (Exception $e) {
			// テストファイル削除
			$this->_deleteTestFile();
			throw $e;
		}
	}

/**
 * ファイル削除 deleteFileAssociated　例外テスト
 *
 * @return void
 * @throws Exception
 */
	public function testDeleteFileDeleteFileAssociatedException() {
		$this->setExpectedException('InternalErrorException');

		$video = array(
			'Video' => array(
				'id' => 2,
				'thumbnail_id' => 2,	// thumbnail1.jpg
			),
		);
		$roomId = 1;

		// テストファイル準備
		$fileName = 'thumbnail1.jpg';
		$contentsId = $video['Video']['thumbnail_id'];
		$this->_readyTestFile($contentsId, $roomId, $fileName);

		$filePath = TMP . 'tests' . DS . 'file' . DS . $roomId . DS . $contentsId;
		$file = new File(APP . 'Plugin' . DS . 'Videos' . DS . 'Test' . DS . 'Fixture' . DS . $fileName);
		$file->copy($filePath . DS . 'thumbnail1_big.jpg');
		$file->copy($filePath . DS . 'thumbnail1_medium.jpg');
		$file->copy($filePath . DS . 'thumbnail1_small.jpg');
		$file->copy($filePath . DS . 'thumbnail1_thumbnail.jpg');
		$file->close();

		// 元動画 取得
		$noConvert = $this->Video->FileModel->findById($video['Video']['thumbnail_id']);

		// アップロードファイルの受け取りと移動
		$noConvertPath = $noConvert['File']["path"];
		$thumbnailSlug = $noConvert['File']["slug"];
		//$noConvertExtension = $noConvert['File']["extension"];

		// サムネイル名は動画名で末尾jpgにしたものをセット
		$videoName = explode('.', $noConvert['File']["name"])[0];
		$pluginKey = 'videos';

		// サムネイルデータ準備
		$data['Video'][Video::THUMBNAIL_FIELD]['name'] = $videoName . '.jpg';	// サムネイル名は動画名で末尾jpgにしたものをセット
		$data['Video'][Video::THUMBNAIL_FIELD]['type'] = 'image/jpeg';
		$data['Video'][Video::THUMBNAIL_FIELD]['tmp_name'] = $noConvertPath . $thumbnailSlug . '.jpg';
		$data['Video'][Video::THUMBNAIL_FIELD]['error'] = UPLOAD_ERR_OK;
		$data['Video'][Video::THUMBNAIL_FIELD]['size'] = filesize($noConvertPath . $thumbnailSlug . '.jpg');

		// Filesテーブルにサムネイルを登録
		$data[Video::THUMBNAIL_FIELD]['File']['status'] = 1;
		$data[Video::THUMBNAIL_FIELD]['File']['role_type'] = 'room_file_role';
		$data[Video::THUMBNAIL_FIELD]['File']['name'] = $videoName . '.jpg';		// サムネイル名は動画名をjpgにしたものをセット
		$data[Video::THUMBNAIL_FIELD]['File']['alt'] = $videoName . '.jpg';
		$data[Video::THUMBNAIL_FIELD]['File']['mimetype'] = 'image/jpeg';
		$data[Video::THUMBNAIL_FIELD]['File']['path'] = '{ROOT}' . 'videos' . '{DS}' . $roomId . '{DS}';		// 自動的に $video['Video']['id'] . '{DS}' が末尾に追記されるので、ここでは追記しない
		$data[Video::THUMBNAIL_FIELD]['File']['extension'] = 'jpg';
		$data[Video::THUMBNAIL_FIELD]['File']['tmp_name'] = $noConvertPath . $thumbnailSlug . '.jpg';
		$data[Video::THUMBNAIL_FIELD]['File']['size'] = filesize($noConvertPath . $thumbnailSlug . '.jpg');
		$data[Video::THUMBNAIL_FIELD]['File']['slug'] = $thumbnailSlug;
		$data[Video::THUMBNAIL_FIELD]['File']['original_name'] = $thumbnailSlug;

		$data[Video::THUMBNAIL_FIELD]['FilesPlugin']['plugin_key'] = $pluginKey;	// plugin_keyは、元動画のをセット
		$data[Video::THUMBNAIL_FIELD]['FilesRoom']['room_id'] = $roomId;
		$data[Video::THUMBNAIL_FIELD]['FilesUser']['user_id'] = 1;

		// いままでのファイル 削除対象
		$data['Video']['thumbnail_id'] = 2;

		// ファイルチェック 変換後動画ファイル
		$data = $this->Video->validateVideoFile($data, Video::THUMBNAIL_FIELD, $this->Video->alias, 'thumbnail_id', 0);

		// modelモック
		$fileModelMock = $this->getMockForModel('Files.FileModel', ['deleteFileAssociated']);
		$fileModelMock->expects($this->any())
			->method('save')
			->will($this->returnValue(false));
		$this->Video->FileModel = $fileModelMock;

		try {
			// ファイル削除
			$data = $this->Video->deleteFile($data, $this->Video->alias, 'thumbnail_id', 0);
		} catch (Exception $e) {
			// テストファイル削除
			$this->_deleteTestFile();
			throw $e;
		}
	}
}
