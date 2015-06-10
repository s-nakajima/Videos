<?php
/**
 * VideoBehaviorExceptionTest Case
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
 * Summary for VideoBehaviorExceptionTest Case
 */
class VideoBehaviorExceptionTest extends VideoAppTest {

/**
 * 動画変換とデータ保存 MP4例外テスト
 *
 * @return void
 * @throws Exception
 */
	public function testSaveConvertVideoMp4Exception() {
		$this->setExpectedException('InternalErrorException');

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

		// 例外を発生させるためのモック
		$videoMock = $this->getMockForModel('Videos.Video', ['save']);
		$videoMock->expects($this->any())
			->method('save')
			->will($this->returnValue(false));

		$videoMock->FileModel = ClassRegistry::init('Files.FileModel');

		try {
			// 動画変換とデータ保存
			$videoMock->saveConvertVideo($data, $video, $roomId);
		} catch (Exception $e) {
			//アップロードテストのためのディレクトリ削除
			$folder = new Folder();
			$folder->delete(TMP . 'tests' . DS . 'file');
			throw $e;
		}
	}
}
