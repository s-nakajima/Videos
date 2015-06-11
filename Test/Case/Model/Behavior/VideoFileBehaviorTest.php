<?php
/**
 * VideoFileBehaviorTest Case
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
 * Summary for VideoFileBehaviorTest Case
 */
class VideoFileBehaviorTest extends VideoAppTest {

/**
 * ファイルチェック テスト
 *
 * @return void
 */
	public function testValidateVideoFile() {
		$video = array(
			'Video' => array(
				'id' => 1,
				//'mp4_id' => 3	// video2.MOV
				'mp4_id' => 1	// video1.mp4
			),
		);
		$roomId = 1;

		// ファイル準備
		// 本来は      /{TMP}/file/{roomId}/{contentsId} だけど、
		// テストの為、/{TMP}/file/{roomId}/{fileId} で対応する
		$folder = new Folder();
		$folder->create(TMP . 'tests' . DS . 'file' . DS . $roomId . DS . $video['Video']['mp4_id']);
		$file = new File(APP . 'Plugin' . DS . 'Videos' . DS . 'Test' . DS . 'Fixture' . DS . 'video1.mp4');
		$file->copy(TMP . 'tests' . DS . 'file' . DS . $roomId . DS . $video['Video']['mp4_id'] . DS . 'video1.mp4');
		$file->close();

		// VideoBehavior.php 29行目付近よりコピー
		// 元動画 取得
		$noConvert = $this->Video->FileModel->findById($video['Video']['mp4_id']);

		// VideoBehavior.php 87行目付近よりコピー
		// アップロードファイルの受け取りと移動
		$noConvertPath = $noConvert['File']["path"];
		$noConvertSlug = $noConvert['File']["slug"];
		//$noConvertExtension = $noConvert['File']["extension"];

		// サムネイル名は動画名で末尾jpgにしたものをセット
		$videoName = explode('.', $noConvert['File']["name"])[0];

		// VideoBehavior.php 116行目付近よりコピー
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

		// ファイルチェック 変換後動画ファイル
		$data = $this->Video->validateVideoFile($data, Video::VIDEO_FILE_FIELD, $this->Video->alias, 'mp4_id', 0);

		//アップロードテストのためのディレクトリ削除
		$folder = new Folder();
		$folder->delete(TMP . 'tests' . DS . 'file');

		$this->assertInternalType('array', $data);
	}

/**
 * ファイルチェック validateエラー テスト
 * $data[videoFile][File][slug], [role_type] が空のため、validateエラー
 *
 * @return void
 */
	public function testValidateVideoFileValidateFail() {
		$video = array(
			'Video' => array(
				'id' => 1,
				//'mp4_id' => 3	// video2.MOV
				'mp4_id' => 1	// video1.mp4
			),
		);
		$roomId = 1;

		// ファイル準備
		$folder = new Folder();
		$folder->create(TMP . 'tests' . DS . 'file' . DS . $roomId . DS . $video['Video']['mp4_id']);
		$file = new File(APP . 'Plugin' . DS . 'Videos' . DS . 'Test' . DS . 'Fixture' . DS . 'video1.mp4');
		$file->copy(TMP . 'tests' . DS . 'file' . DS . $roomId . DS . $video['Video']['mp4_id'] . DS . 'video1.mp4');
		$file->close();

		// 元動画 取得
		$noConvert = $this->Video->FileModel->findById($video['Video']['mp4_id']);

		// アップロードファイルの受け取りと移動
		$noConvertPath = $noConvert['File']["path"];
		$noConvertSlug = $noConvert['File']["slug"];
		//$noConvertExtension = $noConvert['File']["extension"];

		// サムネイル名は動画名で末尾jpgにしたものをセット
		$videoName = explode('.', $noConvert['File']["name"])[0];

		// Filesテーブルに変換後動画を登録。Delete->Insert
		$data[Video::VIDEO_FILE_FIELD]['File']['type'] = 'video/mp4';
		$data[Video::VIDEO_FILE_FIELD]['File']['mimetype'] = 'video/mp4';
		$data[Video::VIDEO_FILE_FIELD]['File']['path'] = '{ROOT}' . 'videos' . '{DS}' . $roomId . '{DS}' . $video['Video']['id'] . '{DS}';
		$data[Video::VIDEO_FILE_FIELD]['File']['name'] = $videoName . '.mp4';
		$data[Video::VIDEO_FILE_FIELD]['File']['alt'] = $videoName . '.mp4';
		$data[Video::VIDEO_FILE_FIELD]['File']['extension'] = 'mp4';
		$data[Video::VIDEO_FILE_FIELD]['File']['tmp_name'] = $noConvertPath . $noConvertSlug . '.mp4';
		$data[Video::VIDEO_FILE_FIELD]['File']['size'] = filesize($noConvertPath . $noConvertSlug . '.mp4');

		// ファイルチェック 変換後動画ファイル
		$data = $this->Video->validateVideoFile($data, Video::VIDEO_FILE_FIELD, $this->Video->alias, 'mp4_id', 0);

		//アップロードテストのためのディレクトリ削除
		$folder = new Folder();
		$folder->delete(TMP . 'tests' . DS . 'file');

		$this->assertFalse($data);
	}

/**
 * ファイルチェック ファイル更新テスト
 *
 * @return void
 */
	public function testValidateVideoFileEdit() {
		$video = array(
			'Video' => array(
				'id' => 1,
				//'mp4_id' => 3	// video2.MOV
				'mp4_id' => 1	// video1.mp4
			),
		);
		$roomId = 1;

		// ファイル準備
		$folder = new Folder();
		$folder->create(TMP . 'tests' . DS . 'file' . DS . $roomId . DS . $video['Video']['mp4_id']);
		$file = new File(APP . 'Plugin' . DS . 'Videos' . DS . 'Test' . DS . 'Fixture' . DS . 'video1.mp4');
		$file->copy(TMP . 'tests' . DS . 'file' . DS . $roomId . DS . $video['Video']['mp4_id'] . DS . 'video1.mp4');
		$file->close();

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

		$data['Video']['mp4_id'] = 1;

		// ファイルチェック 変換後動画ファイル
		$data = $this->Video->validateVideoFile($data, Video::VIDEO_FILE_FIELD, $this->Video->alias, 'mp4_id', 0);

		//アップロードテストのためのディレクトリ削除
		$folder = new Folder();
		$folder->delete(TMP . 'tests' . DS . 'file');

		$this->assertInternalType('array', $data);
	}

/**
 * ファイルチェック ファイル更新時、既に登録済みのファイル削除validate 失敗テスト
 * FileModelモックで強制的に失敗させる
 *
 * @return void
 */
	public function testValidateVideoFileEditValidateDeletedFilesFail() {
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

		// ファイル準備
		$folder = new Folder();
		$folder->create(TMP . 'tests' . DS . 'file' . DS . $roomId . DS . $video['Video']['mp4_id']);
		$file = new File(APP . 'Plugin' . DS . 'Videos' . DS . 'Test' . DS . 'Fixture' . DS . 'video1.mp4');
		$file->copy(TMP . 'tests' . DS . 'file' . DS . $roomId . DS . $video['Video']['mp4_id'] . DS . 'video1.mp4');
		$file->close();

		// modelモック
		$fileModelMock = $this->getMockForModel('Files.FileModel', ['validateDeletedFiles']);
		$fileModelMock->expects($this->any())
			->method('validateDeletedFiles')
			->will($this->returnValue(false));
		$this->Video->FileModel = $fileModelMock;

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

		$data['Video']['mp4_id'] = 1;

		// ファイルチェック 変換後動画ファイル
		$data = $this->Video->validateVideoFile($data, Video::VIDEO_FILE_FIELD, $this->Video->alias, 'mp4_id', 0);

		//アップロードテストのためのディレクトリ削除
		$folder = new Folder();
		$folder->delete(TMP . 'tests' . DS . 'file');

		$this->assertFalse($data);
	}

/**
 * ファイルチェック 更新ファイル関連validete 失敗テスト
 * FileModelモックで強制的に失敗させる
 *
 * @return void
 */
	public function testValidateVideoFileEditValidateFileAssociatedFail() {
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

		// ファイル準備
		$folder = new Folder();
		$folder->create(TMP . 'tests' . DS . 'file' . DS . $roomId . DS . $video['Video']['mp4_id']);
		$file = new File(APP . 'Plugin' . DS . 'Videos' . DS . 'Test' . DS . 'Fixture' . DS . 'video1.mp4');
		$file->copy(TMP . 'tests' . DS . 'file' . DS . $roomId . DS . $video['Video']['mp4_id'] . DS . 'video1.mp4');
		$file->close();

		// modelモック
		$fileModelMock = $this->getMockForModel('Files.FileModel', ['validateFileAssociated']);
		$fileModelMock->expects($this->any())
			->method('validateFileAssociated')
			->will($this->returnValue(false));
		$this->Video->FileModel = $fileModelMock;

		// VideoBehavior.php 29行目付近よりコピー
		// 元動画 取得
		$noConvert = $this->Video->FileModel->findById($video['Video']['mp4_id']);

		// VideoBehavior.php 87行目付近よりコピー
		// アップロードファイルの受け取りと移動
		$noConvertPath = $noConvert['File']["path"];
		$noConvertSlug = $noConvert['File']["slug"];
		//$noConvertExtension = $noConvert['File']["extension"];

		// サムネイル名は動画名で末尾jpgにしたものをセット
		$videoName = explode('.', $noConvert['File']["name"])[0];

		// VideoBehavior.php 116行目付近よりコピー
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
		$data['Video']['mp4_id'] = 1;

		// ファイルチェック 変換後動画ファイル
		$data = $this->Video->validateVideoFile($data, Video::VIDEO_FILE_FIELD, $this->Video->alias, 'mp4_id', 0);

		//アップロードテストのためのディレクトリ削除
		$folder = new Folder();
		$folder->delete(TMP . 'tests' . DS . 'file');

		$this->assertFalse($data);
	}

/**
 * ファイルの登録テスト
 *
 * @return void
 */
	public function testSaveVideoFile() {
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

		// ファイル準備
		$folder = new Folder();
		$folder->create(TMP . 'tests' . DS . 'file' . DS . $roomId . DS . $video['Video']['mp4_id']);
		$file = new File(APP . 'Plugin' . DS . 'Videos' . DS . 'Test' . DS . 'Fixture' . DS . 'video1.mp4');
		$file->copy(TMP . 'tests' . DS . 'file' . DS . $roomId . DS . $video['Video']['mp4_id'] . DS . 'video1.mp4');
		$file->close();

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

		// ファイルの登録 変換後動画ファイル
		$data = $this->Video->saveVideoFile($data, Video::VIDEO_FILE_FIELD, $this->Video->alias, 'mp4_id', 0);

		//アップロードテストのためのディレクトリ削除
		$folder = new Folder();
		$folder->delete(TMP . 'tests' . DS . 'file');

		$this->assertInternalType('array', $data);
	}

/**
 * ファイルの更新テスト
 *
 * @return void
 */
	public function testSaveVideoFileEdit() {
		$video = array(
			'Video' => array(
				'id' => 1,
				'mp4_id' => 1	// video1.mp4
			),
			Video::VIDEO_FILE_FIELD => array(
				'FilesPlugin' => array(
					'plugin_key' => 'videos'
				)
			),
		);
		$roomId = 1;

		// ファイル準備
		$folder = new Folder();
		$folder->create(TMP . 'tests' . DS . 'file' . DS . $roomId . DS . $video['Video']['mp4_id']);
		$file = new File(APP . 'Plugin' . DS . 'Videos' . DS . 'Test' . DS . 'Fixture' . DS . 'video1.mp4');
		$file->copy(TMP . 'tests' . DS . 'file' . DS . $roomId . DS . $video['Video']['mp4_id'] . DS . 'video1.mp4');
		$file->close();

		// 元動画 取得
		$noConvert = $this->Video->FileModel->findById($video['Video']['mp4_id']);

		// アップロードファイルの受け取りと移動
		$noConvertPath = $noConvert['File']["path"];
		$noConvertSlug = $noConvert['File']["slug"];
		//$noConvertExtension = $noConvert['File']["extension"];

		// サムネイル名は動画名で末尾jpgにしたものをセット
		$videoName = explode('.', $noConvert['File']["name"])[0];

		// アップロードしたファイルのデータ準備
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

		// いままでのファイル 削除対象
		$data['Video']['mp4_id'] = 1;

		// ファイルチェック 変換後動画ファイル
		$data = $this->Video->validateVideoFile($data, Video::VIDEO_FILE_FIELD, $this->Video->alias, 'mp4_id', 0);

		// ファイルの登録 変換後動画ファイル
		$data = $this->Video->saveVideoFile($data, Video::VIDEO_FILE_FIELD, $this->Video->alias, 'mp4_id', 0);

		//アップロードテストのためのディレクトリ削除
		$folder = new Folder();
		$folder->delete(TMP . 'tests' . DS . 'file');

		$this->assertInternalType('array', $data);
	}
}
