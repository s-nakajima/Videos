<?php
/**
 * VideosController::beforeFilter()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsControllerTestCase', 'NetCommons.TestSuite');

/**
 * VideosController::beforeFilter()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Videos\Test\Case\Controller\VideosController
 */
class VideosControllerBeforeFilterTest extends NetCommonsControllerTestCase {

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
		'plugin.categories.category',
		'plugin.categories.category_order',
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
	protected $_controller = 'videos';

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
 * テストDataの取得
 *
 * @return array
 */
	private function __data() {
		$frameId = '6';
		$blockId = '2';

		$data = array(
			'action' => 'index',
			'frame_id' => $frameId,
			'block_id' => $blockId,
		);

		return $data;
	}

/**
 * index()アクションのGetリクエストテスト
 *
 * @return void
 */
	public function testBeforeFilterGet() {
		$data = $this->__data();

		//テスト実行
		$this->_testGetAction($data, array('method' => 'assertNotEmpty'), null, 'view');
		//debug($this->view);
	}

/**
 * index()アクションのGetリクエストテスト - ブロックIDなしによる空表示確認
 *
 * @return void
 */
	public function testBeforeFilterGetEmptyRender() {
		//テスト実行
		$this->_testGetAction(array('action' => 'index'), array('method' => 'assertEmpty'), null, 'view');
		//debug($this->view);
	}
}
