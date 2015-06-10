<?php
/**
 * VideoBehavior Test Case
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
 * Summary for VideoBehavior Test Case
 */
class VideoBehaviorTest extends VideoAppTest {

/**
 * 動画変換とデータ保存 MP4テスト
 *
 * @return void
 */
	public function testSaveConvertVideoMp4() {
		// ファイル準備
		$folder = new Folder();
		$folder->create(TMP . 'tests' . DS . 'file' . DS . '1');
		$file = new File(
			APP . 'Plugin' . DS . 'Videos' . DS . 'Test' . DS . 'Fixture' . DS . 'video1.mp4'
		);
		$file->copy(TMP . 'tests' . DS . 'file' . DS . '1' . DS . 'video1.mp4');
		$file->close();

		// AuthComponent::user('id');対応
		$Session = new CakeSession();
		$Session->write('Auth.User.id', 1);

		$data = array('Video' => array(
			'block_id' => 2
		));
		$video = array(
			'Video' => array(
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

		// 動画変換とデータ保存
		$rtn = $this->Video->saveConvertVideo($data, $video, $roomId);

		//アップロードテストのためのディレクトリ削除
		$folder = new Folder();
		$folder->delete(TMP . 'tests' . DS . 'file');

		$this->assertTrue($rtn);
	}

/**
 * 動画変換とデータ保存 動画変換 失敗テスト
 *
 * @return void
 */
	public function testSaveConvertVideoConvertVideoFail() {
		// $dataが空なので、動画変換失敗
		$data = null;
		$video = array(
			'Video' => array(
				'mp4_id' => 1	// video1.mp4
			),
			Video::VIDEO_FILE_FIELD => array(
				'FilesPlugin' => array(
					'plugin_key' => 'videos'
				)
			),
		);
		$roomId = 1;

		// 動画変換とデータ保存
		$rtn = $this->Video->saveConvertVideo($data, $video, $roomId);

		$this->assertFalse($rtn);
	}

/**
 * 動画変換とデータ保存 動画時間取得 失敗テスト
 *
 * @return void
 */
	public function testSaveConvertVideoGetVideoTimeFail() {
		// '/var/www/app/app/tmp/tests/file/1/video1.mp4: No such file or directory' ファイルが無いので、再生時間取得失敗
		$data = array('hoge');
		$video = array(
			'Video' => array(
				'mp4_id' => 1	// video1.mp4
			),
			Video::VIDEO_FILE_FIELD => array(
				'FilesPlugin' => array(
					'plugin_key' => 'videos'
				)
			),
		);
		$roomId = 1;

		// 動画変換とデータ保存
		$rtn = $this->Video->saveConvertVideo($data, $video, $roomId);

		$this->assertFalse($rtn);
	}

/**
 * 動画変換とデータ保存 サムネイル ファイルチェック 失敗テスト
 *
 * @return void
 */
	public function testSaveConvertVideoThumbnailValidateFail() {
		$folder = new Folder();
		$folder->create(TMP . 'tests' . DS . 'file' . DS . '1');
		$file = new File(
			APP . 'Plugin' . DS . 'Videos' . DS . 'Test' . DS . 'Fixture' . DS . 'video1.mp4'
		);
		$file->copy(TMP . 'tests' . DS . 'file' . DS . '1' . DS . 'video1.mp4');
		$file->close();

		// セッションにAuth.User.idをセットしていないので、サムネイル ファイルチェックのvalidetionで、user_id数値チェックエラーで失敗
		$data = null;
		$video = array(
			'Video' => array(
				'mp4_id' => 1	// video1.mp4
			),
			Video::VIDEO_FILE_FIELD => array(
				'FilesPlugin' => array(
					'plugin_key' => 'videos'
				)
			),
		);
		$roomId = 1;

		// 動画変換とデータ保存
		$rtn = $this->Video->saveConvertVideo($data, $video, $roomId);

		//アップロードテストのためのディレクトリ削除
		$folder = new Folder();
		$folder->delete(TMP . 'tests' . DS . 'file');

		$this->assertFalse($rtn);
	}

/**
 * 動画変換とデータ保存 quicktimeテスト
 *
 * @return void
 */
	public function testSaveConvertVideoMov() {
		// ファイル準備
		$folder = new Folder();
		$folder->create(TMP . 'tests' . DS . 'file' . DS . '3');
		$file = new File(
			APP . 'Plugin' . DS . 'Videos' . DS . 'Test' . DS . 'Fixture' . DS . 'video2.MOV'
		);
		$file->copy(TMP . 'tests' . DS . 'file' . DS . '3' . DS . 'video2.MOV');
		$file->close();

		// AuthComponent::user('id');対応
		$Session = new CakeSession();
		$Session->write('Auth.User.id', 1);

		$data = array(
			'Video' => array(
				'block_id' => 2
			),
			Video::VIDEO_FILE_FIELD => array(
				'File' => array(
					'slug' => 'video2',
					'role_type' => 'room_file_role',
				),
			),
		);
		$video = array(
			'Video' => array(
				'id' => 2,		// video2.MOV
				'mp4_id' => 3,	// video2.MOV
			),
			Video::VIDEO_FILE_FIELD => array(
				'FilesPlugin' => array(
					'plugin_key' => 'videos'
				),
			),
		);
		$roomId = 1;

		// 動画変換とデータ保存
		$rtn = $this->Video->saveConvertVideo($data, $video, $roomId);

		//アップロードテストのためのディレクトリ削除
		$folder = new Folder();
		$folder->delete(TMP . 'tests' . DS . 'file');

		$this->assertTrue($rtn);
	}

/**
 * 動画変換とデータ保存 quicktime 動画変換 失敗テスト
 *
 * @return void
 */
	public function testSaveConvertVideoMovConvertVideoFail() {
		// ファイルなしのため、動画変換失敗
		$data = array('Video' => array(
			'block_id' => 2
		));
		$video = array(
			'Video' => array(
				'id' => 2,		// video2.MOV
				'mp4_id' => 3,	// video2.MOV
			),
			Video::VIDEO_FILE_FIELD => array(
				'FilesPlugin' => array(
					'plugin_key' => 'videos'
				)
			),
		);
		$roomId = 1;

		// 動画変換とデータ保存
		$rtn = $this->Video->saveConvertVideo($data, $video, $roomId);

		$this->assertFalse($rtn);
	}

/**
 * 動画変換とデータ保存 quicktime 動画変換 validateVideoFile 失敗テスト
 *
 * @return void
 */
	public function testSaveConvertVideoMovValidateVideoFileFail() {
		// ファイル準備
		$folder = new Folder();
		$folder->create(TMP . 'tests' . DS . 'file' . DS . '3');
		$file = new File(
			APP . 'Plugin' . DS . 'Videos' . DS . 'Test' . DS . 'Fixture' . DS . 'video2.MOV'
		);
		$file->copy(TMP . 'tests' . DS . 'file' . DS . '3' . DS . 'video2.MOV');
		$file->close();

		// AuthComponent::user('id');対応
		$Session = new CakeSession();
		$Session->write('Auth.User.id', 1);

		// $data[videoFile][File][slug], [role_type] が空のため、validateエラー
		$data = array('Video' => array(
			'block_id' => 2
		));
		$video = array(
			'Video' => array(
				'id' => 2,		// video2.MOV
				'mp4_id' => 3,	// video2.MOV
			),
			Video::VIDEO_FILE_FIELD => array(
				'FilesPlugin' => array(
					'plugin_key' => 'videos'
				),
			),
		);
		$roomId = 1;

		// 動画変換とデータ保存
		$rtn = $this->Video->saveConvertVideo($data, $video, $roomId);

		//アップロードテストのためのディレクトリ削除
		$folder = new Folder();
		$folder->delete(TMP . 'tests' . DS . 'file');

		$this->assertFalse($rtn);
	}
}
