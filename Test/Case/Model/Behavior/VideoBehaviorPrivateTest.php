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

App::uses('VideoBehaviorAppTest', 'Videos.Test/Case/Model/Behavior');

/**
 * Summary for VideoBehaviorPrivateTest Case
 */
class VideoBehaviorPrivateTest extends VideoBehaviorAppTest {

/**
 * 動画変換テスト
 *
 * @return void
 */
	public function testConvertVideo() {
		App::uses('Video', 'Videos.Model');

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
				'id' => 3,		// video2.MOV
				'mp4_id' => 3,	// video2.MOV
			),
			Video::VIDEO_FILE_FIELD => array(
				'FilesPlugin' => array(
					'plugin_key' => 'videos'
				),
			),
		);
		$roomId = 1;

		// テストファイル準備
		$fileName = 'video2.MOV';
		$contentsId = $video['Video']['mp4_id'];
		$this->_readyTestFile($contentsId, $roomId, $fileName);

		// 元動画 取得
		$noConvert = $this->Video->FileModel->findById($video['Video']['mp4_id']);

		// behaviorでprivateメソッド呼び出し
		$method = new ReflectionMethod($this->Video->Behaviors->Video, '__convertVideo');
		$method->setAccessible(true);
		$data = $method->invokeArgs($this->Video->Behaviors->Video, array($this->Video, $data, $video, $noConvert, $roomId));

		// テストファイル削除
		$this->_deleteTestFile();

		// 暫定対応(;'∀') ffmpeg未インストールによる travis-ci error のため、コメントアウト
		//$this->assertInternalType('array', $data);
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
