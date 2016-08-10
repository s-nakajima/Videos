<?php
/**
 * VideoFrameSettingsController::edit()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsControllerTestCase', 'NetCommons.TestSuite');

/**
 * VideoFrameSettingsController::edit()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Videos\Test\Case\Controller\VideoFrameSettingsController
 */
class VideoFrameSettingsControllerEditTest extends NetCommonsControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.videos.video',
		'plugin.videos.video_setting',
		'plugin.videos.block_setting_for_video',
		'plugin.videos.video_frame_setting',
	);

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'videos';

/**
 * Controller name
 *
 * @var string
 */
	protected $_controller = 'video_frame_settings';

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		//ログイン
		TestAuthGeneral::login($this);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		//ログアウト
		TestAuthGeneral::logout($this);

		parent::tearDown();
	}

/**
 * edit()アクションのGetリクエストテスト
 *
 * @return void
 */
	public function testEditGet() {
		//テストデータ
		$frameId = '6';
		$blockId = '2';
		$blockKey = 'block_1';

		//テスト実行
		$this->_testGetAction(array('action' => 'edit', 'block_id' => $blockId, 'frame_id' => $frameId),
				array('method' => 'assertNotEmpty'), null, 'view');

		//チェック
		$this->__assertEditGet($frameId, $blockId, $blockKey);
	}

/**
 * edit()のチェック
 *
 * @param int $frameId フレームID
 * @param int $blockId ブロックID
 * @param string $blockKey ブロックKey
 * @return void
 */
	private function __assertEditGet($frameId, $blockId, $blockKey) {
		//debug($this->view);
		//debug($this->controller->request->data);

		$this->assertInput('form', null, 'videos/video_frame_settings/edit/' . $blockId, $this->view);
		$this->assertInput('input', '_method', 'PUT', $this->view);
		$this->assertInput('input', 'data[Frame][id]', $frameId, $this->view);
		//$this->assertInput('input', 'data[Block][id]', $blockId, $this->view);
		//$this->assertInput('input', 'data[Block][key]', $blockKey, $this->view);

		$this->assertEquals($frameId, Hash::get($this->controller->request->data, 'Frame.id'));
		//$this->assertEquals($blockId, Hash::get($this->controller->request->data, 'Block.id'));
		//$this->assertEquals($blockKey, Hash::get($this->controller->request->data, 'Block.key'));
	}

/**
 * POSTリクエストデータ生成
 *
 * @return array リクエストデータ
 */
	private function __data() {
		$data = array(
			'Frame' => array(
				'id' => '6'
			),
			'VideoFrameSetting' => array(
				'id' => '6',
				'frame_key' => 'frame_3',
				'display_order' => 'new',
				'display_number' => '10',
			),
		);

		return $data;
	}

/**
 * edit()アクションのPOSTリクエストテスト
 *
 * @return void
 */
	public function testEditPost() {
		//テストデータ
		$frameId = '6';
		$blockId = '2';

		//テスト実行
		$this->_testPostAction('put', $this->__data(),
				array('action' => 'edit', 'block_id' => $blockId, 'frame_id' => $frameId), null, 'view');

		//チェック
		$header = $this->controller->response->header();
		//$pattern = 'video/video/index/' . $blockId;
		// リダイレクト先
		$pattern = Router::fullbaseUrl() . DS;
		$this->assertTextContains($pattern, $header['Location']);
	}

/**
 * ValidationErrorテスト
 *
 * @return void
 */
	public function testEditPostValidationError() {
		$this->_mockForReturnCallback('VideoFrameSetting', 'saveVideoFrameSetting', function () {
			$model = 'VideoFrameSetting';
			$message = __d('net_commons', 'Invalid request.');
			$this->controller->$model->invalidate('display_number', $message);
			return false;
		});

		//テストデータ
		$frameId = '6';
		$blockId = '2';

		//テスト実行
		$this->_testPostAction('put', $this->__data(),
				array('action' => 'edit', 'block_id' => $blockId, 'frame_id' => $frameId), null, 'view');

		//チェック
		$message = __d('net_commons', 'Invalid request.');
		$this->assertTextContains($message, $this->view);
	}

}
