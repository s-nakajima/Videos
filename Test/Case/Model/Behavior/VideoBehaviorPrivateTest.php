<?php
/**
 * VideoBehaviorPrivateTest Case
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
 * Summary for VideoBehaviorPrivateTest Case
 */
class VideoBehaviorPrivateTest extends VideoAppTest {

/**
 * 動画変換テスト
 *
 * @return void
 */
	public function testConvertVideo() {
		App::uses('Video', 'Videos.Model');

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

		// 元動画 取得
		$noConvert = $this->Video->FileModel->findById($video['Video']['mp4_id']);

		// behaviorでprivateメソッド呼び出し
		$method = new ReflectionMethod($this->Video->Behaviors->Video, '__convertVideo');
		$method->setAccessible(true);
		$data = $method->invokeArgs($this->Video->Behaviors->Video, array($this->Video, $data, $video, $noConvert, $roomId));

		//アップロードテストのためのディレクトリ削除
		$folder = new Folder();
		$folder->delete(TMP . 'tests' . DS . 'file');

		$this->assertInternalType('array', $data);
	}

/**
 * 再生時間を取得 ファイルなしテスト
 *
 * @return void
 */
	public function testetVideoTime() {
		// ファイルなしのため、再生時間を取得が 0
		$video = array(
			'Video' => array(
				'id' => 1,		// video1.mp4
				'mp4_id' => 1,	// video1.mp4
			),
			Video::VIDEO_FILE_FIELD => array(
				'FilesPlugin' => array(
					'plugin_key' => 'videos'
				),
			),
		);

		// 元動画 取得
		$noConvert = $this->Video->FileModel->findById($video['Video']['mp4_id']);

		// behaviorでprivateメソッド呼び出し
		$method = new ReflectionMethod($this->Video->Behaviors->Video, '__getVideoTime');
		$method->setAccessible(true);
		$data = $method->invokeArgs($this->Video->Behaviors->Video, array($noConvert));

		$this->assertEquals(0, $data);
	}

/**
 * サムネイル自動作成 失敗テスト
 *
 * @return void
 */
	public function testtGenerateThumbnailFail() {
		// ファイルなしのため、サムネイル自動作成 失敗
		$video = array(
			'Video' => array(
				'mp4_id' => 1,	// video1.mp4
			),
		);

		$data = array();
		$roomId = 1;
		$pluginKey = 'videos';
		// 元動画 取得
		$noConvert = $this->Video->FileModel->findById($video['Video']['mp4_id']);

		// behaviorでprivateメソッド呼び出し
		$method = new ReflectionMethod($this->Video->Behaviors->Video, '__generateThumbnail');
		$method->setAccessible(true);
		$data = $method->invokeArgs($this->Video->Behaviors->Video, array($data, $pluginKey, $noConvert, $roomId));

		$this->assertEmpty($data);
	}
}
