<?php
/**
 * Video::saveVideo()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('WorkflowSaveTest', 'Workflow.TestSuite');
App::uses('VideoFixture', 'Videos.Test/Fixture');
App::uses('Video', 'Videos.Model');
App::uses('VideoTestUtil', 'Videos.Test/Case');

/**
 * Video::saveVideo()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Videos\Test\Case\Model\Video
 */
class VideoSaveVideoTest extends WorkflowSaveTest {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.categories.category',
		'plugin.categories.category_order',
		'plugin.site_manager.site_setting',
		'plugin.videos.video',
		'plugin.videos.video_setting',
		'plugin.videos.block_setting_for_video',
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
	protected $_modelName = 'Video';

/**
 * Method name
 *
 * @var string
 */
	protected $_methodName = 'saveVideo';

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		Current::$current['Plugin']['key'] = $this->plugin;

		// ファイルアップロードの実ファイルが配置されなかったので、強制的に実ファイルを配置
		// アップロードパスの変更
		$tmpFolder = new TemporaryFolder();
		$this->UploadFile = ClassRegistry::init('Files.UploadFile', true);
		$this->UploadFile->uploadBasePath = $tmpFolder->path . '/';
		// テスト実ファイル配置
		$testFilePath = $tmpFolder->path . '/files/upload_file/test/11';
		$tmpFolder->create($testFilePath);
		$videoFilePath = APP . 'Plugin' . DS . 'Videos' . DS . 'Test' . DS . 'Fixture' . DS .
			'video1.mp4';
		$tmpFilePath = $testFilePath . DS . 'video1.mp4';
		copy($videoFilePath, $tmpFilePath);
		// テスト実ファイル配置 2個目
		$testFilePath = $tmpFolder->path . '/files/upload_file/real_file_name/1/14';
		$tmpFilePath = $testFilePath . DS . 'ef4ac246226cf2f9896c0d978c71541f.mp4';
		$tmpFolder->create($testFilePath);
		copy($videoFilePath, $tmpFilePath);
	}

/**
 * Save用DataProvider
 *
 * ### 戻り値
 *  - data 登録データ
 *
 * @return array テストデータ
 */
	public function dataProviderSave() {
		$data['Video'] = (new VideoFixture())->records[1];
		$data['Video']['status'] = '1';

		// アップロードした一時ファイル作成
		$fileName = 'video1.mp4';
		$testUtil = new VideoTestUtil();
		$data['Video'][Video::VIDEO_FILE_FIELD] = $testUtil->getFileData('Videos', $fileName, 'video/mp4');

		$results = array();
		// * 編集の登録処理
		$results[0] = array($data);
		// * 新規の登録処理
		$data['Video'][Video::VIDEO_FILE_FIELD] = $testUtil->getFileData('Videos', $fileName, 'video/mp4');
		$results[1] = array($data);
		$results[1] = Hash::insert($results[1], '0.Video.id', null);
		$results[1] = Hash::insert($results[1], '0.Video.key', null);
		$results[1] = Hash::remove($results[1], '0.Video.created_user');

		// 下記でエラー
		// rename(/var/www/app/app/webroot/files/upload_file/real_file_name/1/12/5f9b2e82a47c436e0a368341281a20ca.jpg,files/upload_file/real_file_name/1/13/97dd8152265c1543f0ca1dc0435d18e3.jpg): No such file or directory
		//   /var/www/app/app/Plugin/Upload/Model/Behavior/UploadBehavior.php : 360
		//   /var/www/app/app/Plugin/Videos/Model/Behavior/VideoBehavior.php : 200
		//		$data['Video'][Video::VIDEO_FILE_FIELD] = $testUtil->getFileData('Videos', 'video2.MOV', 'video/quicktime');
		//		$results[2] = array($data);
		//		$results[2] = Hash::insert($results[2], '0.Video.id', null);
		//		$results[2] = Hash::insert($results[2], '0.Video.key', null);
		//		$results[2] = Hash::remove($results[2], '0.Video.created_user');

		return $results;
	}

/**
 * Save(公開)のテスト
 *
 * @param array $data 登録データ
 * @dataProvider dataProviderSave
 * @return void
 */
	public function testSave($data) {
		$model = $this->_modelName;
		$method = $this->_methodName;

		//テスト実行
		$result = $this->$model->$method($data);
		$this->assertNotEmpty($result);
	}

/**
 * 編集のテスト
 *
 * @return void
 */
	public function testEdit() {
		$data = $this->dataProviderSave()[0][0];

		$model = $this->_modelName;
		$method = $this->_methodName;

		//テスト実行
		$result = $this->$model->$method($data, 1);
		$this->assertNotEmpty($result);
	}

/**
 * Test to call WorkflowBehavior::beforeSave
 *
 * WorkflowBehaviorをモックに置き換えて登録処理を呼び出します。<br>
 * WorkflowBehavior::beforeSaveが1回呼び出されることをテストします。<br>
 * ##### 参考URL
 * http://stackoverflow.com/questions/19833495/how-to-mock-a-cakephp-behavior-for-unit-testing]
 *
 * @param array $data 登録データ
 * @dataProvider dataProviderSave
 * @return void
 * @throws CakeException Workflow.Workflowがロードされていないとエラー
 */
	public function testCallWorkflowBehavior($data) {
		$model = $this->_modelName;
		$method = $this->_methodName;

		if (! $this->$model->Behaviors->loaded('Workflow.Workflow')) {
			$error = '"Workflow.Workflow" not loaded in ' . $this->$model->alias . '.';
			throw new CakeException($error);
		};

		ClassRegistry::removeObject('WorkflowBehavior');
		$workflowBehaviorMock = $this->getMock('WorkflowBehavior', ['beforeSave']);
		ClassRegistry::addObject('WorkflowBehavior', $workflowBehaviorMock);
		$this->$model->Behaviors->unload('Workflow');
		$this->$model->Behaviors->load('Workflow', $this->$model->actsAs['Workflow.Workflow']);

		$workflowBehaviorMock
			//->expects($this->once())
			->expects($this->any())
			->method('beforeSave')
			->will($this->returnValue(true));

		$this->$model->$method($data);
	}

/**
 * SaveのExceptionError用DataProvider
 *
 * ### 戻り値
 *  - data 登録データ
 *  - mockModel Mockのモデル
 *  - mockMethod Mockのメソッド
 *
 * @return array テストデータ
 */
	public function dataProviderSaveOnExceptionError() {
		$data = $this->dataProviderSave()[0][0];

		return array(
			array($data, 'Videos.Video', 'save'),
		);
	}

/**
 * SaveのValidationError用DataProvider
 *
 * ### 戻り値
 *  - data 登録データ
 *  - mockModel Mockのモデル
 *  - mockMethod Mockのメソッド(省略可：デフォルト validates)
 *
 * @return array テストデータ
 */
	public function dataProviderSaveOnValidationError() {
		$data = $this->dataProviderSave()[0][0];

		return array(
			array($data, 'Videos.Video'),
		);
	}

}
