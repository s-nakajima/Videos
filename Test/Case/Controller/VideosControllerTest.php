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

App::uses('VideosTestBase', 'Videos.Test/Case/Controller');
App::uses('NetCommonsControllerTestCase', 'NetCommons.TestSuite');

/**
 * VideosController Test Case
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Videos\Test\Case\Controller
 */
class VideosControllerTest extends VideosTestBase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		// PageLayout対応
		NetCommonsControllerTestCase::loadTestPlugin($this, 'NetCommons', 'TestPlugin');

		// モック作成
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
 * 未ログイン 一覧表示（初期表示）の遷移先 確認テスト
 *
 * @return void
 */
	public function testIndex() {
		$frameId = 1;
		$this->testAction(
			'/videos/videos/index/' . $frameId,
			array(
				'method' => 'get',
				'return' => 'view',
			)
		);
		$this->assertTextEquals('index', $this->controller->view);
	}

/**
 * 未ログイン 一覧表示（初期表示）ソート 動作テスト
 *
 * @return void
 */
	public function testIndexSort() {
		$frameId = 1;
		$this->testAction(
			'/videos/videos/index/' . $frameId . '/sort:Video.title/direction:asc',
			array(
				'method' => 'get',
				'return' => 'view',
			)
		);
		$this->assertTextEquals('index', $this->controller->view);
	}

/**
 * 未ログイン 一覧表示（初期表示）ソート VideoFrameSettingデータあり 再生回数順 動作テスト
 *
 * @return void
 */
	public function testIndexOrderVideoFrameSettingPlay() {
		$frameId = 2;
		$this->testAction(
			'/videos/videos/index/' . $frameId,
			array(
				'method' => 'get',
				'return' => 'view',
			)
		);
		$this->assertTextEquals('index', $this->controller->view);
	}

/**
 * 未ログイン 一覧表示（初期表示）ソート VideoFrameSettingデータなし 動作テスト
 *
 * @return void
 */
	public function testIndexOrderVideoFrameSettingEmpty() {
		$frameId = 10;
		$this->testAction(
			'/videos/videos/index/' . $frameId,
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
		$frameId = 1;
		$this->testAction(
			'/videos/videos/tag/' . $frameId . '/id:1',
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

		// クッキー3500byteを超えたら、cookieの内容をクリアするテスト、できず。
		// テストケースでCookieに書き込んで VideosController.phpの$this->Cookie->read('video_history'); で値を取得しても null。
		// --- 4000 byte
		//$cookieStr = 'so26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3A';
		// --- 3500 byte
		//$cookieStr = 'so26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3A1';
		//$this->controller->Cookie->write('video_history', $cookieStr, false, '1 hour');
		//var_dump($this->controller->Cookie->read('video_history'));

		$frameId = 1;
		$videoKey = 'video_1';
		$this->testAction(
			'/videos/videos/view/' . $frameId . '/' . $videoKey,
			array(
				'method' => 'get',
				'return' => 'view',
			)
		);
		//var_dump($this->controller->Cookie->read('video_history'));

		// アップロードテストのためのディレクトリ削除
		$folder = new Folder();
		$folder->delete(TMP . 'tests' . DS . 'file');

		$this->assertTextEquals('view', $this->controller->view);
	}

/**
 * 未ログイン 詳細表示 例外テスト
 * $videoKey=空のため、エラー
 *
 * @return void
 * @throws Exception
 */
	public function testViewException() {
		$this->setExpectedException('BadRequestException');

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

		$frameId = 1;
		$videoKey = '';
		try {
			$this->testAction(
				'/videos/videos/view/' . $frameId . '/' . $videoKey,
				array(
					'method' => 'get',
					'return' => 'view',
				)
			);
		} catch (Exception $e) {
			// アップロードテストのためのディレクトリ削除
			$folder = new Folder();
			$folder->delete(TMP . 'tests' . DS . 'file');
			throw $e;
		}
	}

/**
 * 未ログイン 詳細表示 Ajaxで例外テスト
 * $videoKey=空のため、エラー
 *
 * @return void
 * @throws Exception
 */
	public function testViewAjaxException() {
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

		$frameId = 1;
		$videoKey = '';

		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest'; // ajax通信

		$this->testAction(
			'/videos/videos/view/' . $frameId . '/' . $videoKey,
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
	//		TestAuthGeneral::login($this);
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
	//		TestAuthGeneral::logout($this);
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
	//		TestAuthGeneral::login($this);
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
	//		TestAuthGeneral::logout($this);
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
	//		TestAuthGeneral::login($this);
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
	//		TestAuthGeneral::logout($this);
	//	}
}
