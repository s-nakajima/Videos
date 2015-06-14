<?php
/**
 * VideosController Test Case
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('ContentCommentsComponent', 'ContentComments.Controller/Component');
App::uses('VideosAppTest', 'Videos.Test/Case/Controller');
App::uses('VideosController', 'Videos.Controller');

/**
 * VideosController Test Case
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Announcements\Test\Case\Controller
 */
class VideosControllerTest extends VideosAppTest {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		// PageLayout対応
		YACakeTestCase::loadTestPlugin($this, 'NetCommons', 'TestPlugin');

		$this->generate(
			'Videos.Videos',
			array(
				'components' => array(
					'Auth' => array('user'),
					'Session',
					'Security',
				)
			)
		);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->controller);
		parent::tearDown();
	}

/**
 * 未ログイン 一覧表示（初期表示）の遷移先 確認テスト
 *
 * @return void
 */
	public function testIndex() {
		$this->testAction(
			'/videos/videos/index/1',
			array(
				'method' => 'get',
				'return' => 'view',
			)
		);
		$this->assertTextEquals('index', $this->controller->view);
	}

/**
 * 未ログイン tag別一覧の遷移先 確認テスト
 *
 * @return void
 */
	public function testTag() {
		$this->testAction(
			'/videos/videos/tag/1/id:1',
			array(
				'method' => 'get',
				'return' => 'view',
			)
		);
		$this->assertTextEquals('tag', $this->controller->view);
	}

/**
 * 未ログイン 詳細表示 確認テスト
 *
 * @return void
 */
	public function testView() {
		$fileName = 'thumbnail1.jpg';
		$contentsId = 2;
		$roomId = 1;

		$filePath = TMP . 'tests' . DS . 'file' . DS . $roomId . DS . $contentsId;
		$folder = new Folder();
		$folder->create($filePath);
		$file = new File(APP . 'Plugin' . DS . 'Videos' . DS . 'Test' . DS . 'Fixture' . DS . $fileName);
		$file->copy($filePath . DS . $fileName);
		$file->copy($filePath . DS . 'thumbnail1_big.jpg');
		$file->copy($filePath . DS . 'thumbnail1_medium.jpg');
		$file->copy($filePath . DS . 'thumbnail1_small.jpg');
		$file->copy($filePath . DS . 'thumbnail1_thumbnail.jpg');
		$file->close();

		$this->testAction(
			'/videos/videos/view/1/video_1',
			array(
				'method' => 'get',
				'return' => 'view',
			)
		);

		// アップロードテストのためのディレクトリ削除
		$folder = new Folder();
		$folder->delete(TMP . 'tests' . DS . 'file');

		$this->assertTextEquals('view', $this->controller->view);
	}

	///**
	// * 未ログイン 詳細表示の遷移先 確認テスト
	// *
	// * @return void
	// */
	//	public function testView() {
	//		$this->testAction(
	//			'/videos/videos/view/1',
	//			array(
	//				'method' => 'get',
	//				'return' => 'view',
	//			)
	//		);
	//		$this->assertTextEquals('view', $this->controller->view);
	//	}
	//
	///**
	// * 管理者ログイン後 一覧表示（初期表示）の 確認テスト
	// *
	// * @return void
	// */
	//	public function testIndexByAdmin() {
	//		RolesControllerTest::login($this);
	//
	//		$view = $this->testAction(
	//			'/videos/videos/index/1',
	//			array(
	//				'method' => 'get',
	//				'return' => 'view',
	//			)
	//		);
	//
	//		// これで何を確認できるか
	//		$this->assertTextContains('nc-videos-1', $view, print_r($view, true));
	//
	//		AuthGeneralControllerTest::logout($this);
	//	}

	///**
	// * Expect user cannot access view action with unknown frame id
	// *
	// * @return void
	// */
	//	public function testViewByUnkownFrameId() {
	//		$this->setExpectedException('InternalErrorException');
	//		$this->testAction(
	//			'/announcements/announcements/view/999',
	//			array(
	//				'method' => 'get',
	//				'return' => 'view',
	//			)
	//		);
	//	}
	//
	///**
	// * Expect admin user can access edit action
	// *
	// * @return void
	// */
	//	public function testEditGet() {
	//		RolesControllerTest::login($this);
	//
	//		$this->testAction(
	//			'/announcements/announcements/edit/1',
	//			array(
	//				'method' => 'get',
	//				'return' => 'contents'
	//			)
	//		);
	//
	//		$this->assertTextEquals('edit', $this->controller->view);
	//
	//		AuthGeneralControllerTest::logout($this);
	//	}
	//
	///**
	// * Expect view action to be successfully handled w/ null frame.block_id
	// * This situation typically occur after placing new plugin into page
	// *
	// * @return void
	// */
	//
	//	public function testAddFrameWithoutBlock() {
	//		$this->testAction(
	//			'/announcements/announcements/view/3',
	//			array(
	//				'method' => 'get',
	//				'return' => 'contents'
	//			)
	//		);
	//		$this->assertTextEquals('view', $this->controller->view);
	//	}
	//
	///**
	// * Expect admin user can publish announcements
	// *
	// * @return void
	// */
	//	public function testEditPost() {
	//		RolesControllerTest::login($this);
	//
	//		$data = array(
	//			'Announcement' => array(
	//				'block_id' => '1',
	//				'key' => 'announcement_1',
	//				'content' => 'edit content',
	//			),
	//			'Frame' => array(
	//				'id' => '1'
	//			),
	//			'Comment' => array(
	//				'comment' => 'edit comment',
	//			),
	//			sprintf('save_%s', NetCommonsBlockComponent::STATUS_PUBLISHED) => '',
	//		);
	//
	//		$this->testAction(
	//			'/announcements/announcements/edit/1',
	//			array(
	//				'method' => 'post',
	//				'data' => $data,
	//				'return' => 'contents'
	//			)
	//		);
	//		$this->assertTextEquals('edit', $this->controller->view);
	//
	//		AuthGeneralControllerTest::logout($this);
	//	}
}
