<?php
/**
 * VideoBehavior::saveConvertVideo()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsModelTestCase', 'NetCommons.TestSuite');
App::uses('VideoFixture', 'Videos.Test/Fixture');
App::uses('Video', 'Videos.Model');
App::uses('VideoTestUtil', 'Videos.Test/Case');
App::uses('TemporaryFolder', 'Files.Utility');

/**
 * VideoBehavior::saveConvertVideo()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Videos\Test\Case\Model\Behavior\VideoBehavior
 */
class VideoBehaviorSaveConvertVideoTest extends NetCommonsModelTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.videos.video',
		'plugin.videos.upload_file_for_video',
		'plugin.videos.upload_files_content_for_video',
	);

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'videos';

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		//テストプラグインのロード
		NetCommonsCakeTestCase::loadTestPlugin($this, 'Videos', 'TestVideos');
		$this->TestModel = ClassRegistry::init('TestVideos.TestVideoBehaviorModel');
	}

/**
 * saveConvertVideo()テストのDataProvider
 *
 * ### 戻り値
 *  - video Video
 *
 * @return array データ
 */
	public function dataProvider() {
		$data['Video'] = (new VideoFixture())->records[0];
		$data['Video']['status'] = '1';
		$results['movテスト'] = array($data, 12, 'video2.MOV');

		$data['Video'] = (new VideoFixture())->records[1];
		$data['Video']['status'] = '1';
		$results['mp4テスト'] = array($data, 11, 'video1.mp4');

		return $results;
	}

/**
 * saveConvertVideo()のテスト
 *
 * @param array $video Video
 * @param string $uploadFileId アップロードファイルID
 * @param string $fileName ファイル名
 * @dataProvider dataProvider
 * @return void
 */
	public function testSaveConvertVideo($video, $uploadFileId, $fileName) {
		$this->TestModel->id = $video['Video']['id'];

		$UploadFile = ClassRegistry::init('Files.UploadFile', true);
		// Uploadビヘイビアが実ファイルを削除しにくるので事前に削除されるファイルを用意しておく
		$tmpFolder = new TemporaryFolder();
		$tmpFolder->create($tmpFolder->path . '/files/upload_file/real_file_name/1/' .
			$uploadFileId . '/');
		$UploadFile->uploadBasePath = $tmpFolder->path . '/';

		// テストのため、アップロードしたファイルをコピーして置いておく
		$plugin = 'Videos';
		$testFilePath = APP . 'Plugin' . DS . $plugin . DS . 'Test' . DS . 'Fixture' . DS . $fileName;
		$tmpFilePath = $tmpFolder->path . '/files/upload_file/real_file_name/1/' .
			$uploadFileId . '/' . $fileName;
		copy($testFilePath, $tmpFilePath);

		//テスト実施
		$result = $this->TestModel->saveConvertVideo($video);

		//チェック
		//debug($result);
		$this->assertTrue($result);
	}

}
