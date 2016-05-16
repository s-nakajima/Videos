<?php
/**
 * VideoBlockSetting::deleteVideoBlockSetting()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsDeleteTest', 'NetCommons.TestSuite');
App::uses('VideoBlockSettingFixture', 'Videos.Test/Fixture');
App::uses('VideoTestUtil', 'Videos.Test/Case');

/**
 * VideoBlockSetting::deleteVideoBlockSetting()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Videos\Test\Case\Model\VideoBlockSetting
 */
class VideoBlockSettingDeleteVideoBlockSettingTest extends NetCommonsDeleteTest {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.videos.video',
		'plugin.videos.video_block_setting',
		'plugin.videos.video_frame_setting',
		'plugin.likes.like',
		'plugin.likes.likes_user',
		'plugin.tags.tag',
		'plugin.tags.tags_content',
		'plugin.content_comments.content_comment',
		'plugin.frames.frame4frames',
		'plugin.workflow.workflow_comment',
	);

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'videos';

/**
 * Model name
 *
 * @var string
 */
	protected $_modelName = 'VideoBlockSetting';

/**
 * Method name
 *
 * @var string
 */
	protected $_methodName = 'deleteVideoBlockSetting';

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		// テスト実ファイル配置
		$this->_testFilePath = APP . WEBROOT_DIR . DS . 'files/upload_file/test/11/';
		(new VideoTestUtil())->readyTestFile('Videos', 'video1.mp4', $this->_testFilePath);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		// テスト実ファイル削除
		(new VideoTestUtil())->deleteTestFile($this->_testFilePath);

		parent::tearDown();
	}

/**
 * Delete用DataProvider
 *
 * ### 戻り値
 *  - data: 削除データ
 *  - associationModels: 削除確認の関連モデル array(model => conditions)
 *
 * @return array テストデータ
 */
	public function dataProviderDelete() {
		$data['VideoBlockSetting'] = (new VideoBlockSettingFixture())->records[0];
		$association = array();
		//Current::$current['Block']['key'] = 'block_1';

		$results = array();
		$results[0] = array($data, $association);

		return $results;
	}

/**
 * ExceptionError用DataProvider
 *
 * ### 戻り値
 *  - data 登録データ
 *  - mockModel Mockのモデル
 *  - mockMethod Mockのメソッド
 *
 * @return array テストデータ
 */
	public function dataProviderDeleteOnExceptionError() {
		$data = $this->dataProviderDelete()[0][0];

		return array(
			array($data, 'Videos.VideoBlockSetting', 'deleteAll'),
			array($data, 'Tags.TagsContent', 'deleteAll'),
			array($data, 'Likes.LikesUser', 'deleteAll'),
			array($data, 'Files.UploadFile', 'deleteAll'),
		);
	}

}
