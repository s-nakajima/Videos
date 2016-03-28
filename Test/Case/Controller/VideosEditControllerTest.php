<?php
/**
 * VideosEditControllerTest Case
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
 * VideosEditControllerTest Case
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Announcements\Test\Case\Controller
 */
class VideosEditControllerTest extends VideosTestBase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		// PageLayout対応
		NetCommonsControllerTestCase::loadTestPlugin($this, 'NetCommons', 'TestPlugin');

		$this->generate(
			'Videos.VideosEdit',
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
 * 管理者ログイン後 登録画面 表示テスト
 *
 * @return void
 */
	public function testAdd() {
		TestAuthGeneral::login($this);

		$frameId = 2;
		$this->testAction(
			'/videos/videos_edit/add/' . $frameId,
			array(
				'method' => 'get',
				'return' => 'view',
			)
		);
		$this->assertTextEquals('add', $this->controller->view);

		TestAuthGeneral::logout($this);
	}

/**
 * 管理者ログイン後 登録画面 登録テスト
 *
 * @return void
 */
	public function testAddPost() {
		TestAuthGeneral::login($this);

		$data = array(
			//'Video' => array(), 暫定対応(;'∀') 登録値の具体的に記述する予定
			sprintf('save_%s', WorkflowComponent::STATUS_PUBLISHED) => ''
		);

		$frameId = 2;
		$this->testAction(
			'/videos/videos_edit/add/' . $frameId,
			array(
				'method' => 'post',
				'data' => $data,
				'return' => 'view',
			)
		);
		$this->assertTextEquals('add', $this->controller->view);

		TestAuthGeneral::logout($this);
	}

/**
 * 管理者ログイン後 編集画面 表示テスト
 *
 * @return void
 */
	public function testEdit() {
		TestAuthGeneral::login($this);

		$frameId = 2;
		$videoKey = 'video_2';
		$this->testAction(
			'/videos/videos_edit/edit/' . $frameId . '/' . $videoKey,
			array(
				'method' => 'get',
				'return' => 'view',
			)
		);
		$this->assertTextEquals('edit', $this->controller->view);

		TestAuthGeneral::logout($this);
	}

/**
 * 管理者ログイン後 編集画面 編集テスト
 *
 * @return void
 */
	public function testEditPost() {
		TestAuthGeneral::login($this);

		$data = array(
			//'Video' => array(), 暫定対応(;'∀') 編集値の具体的に記述する予定
			'Comment' => array(
				'comment' => null
			),
			sprintf('save_%s', WorkflowComponent::STATUS_PUBLISHED) => ''
		);

		$frameId = 2;
		$videoKey = 'video_2';
		$this->testAction(
			'/videos/videos_edit/edit/' . $frameId . '/' . $videoKey,
			array(
				'method' => 'post',
				'data' => $data,
				'return' => 'view',
			)
		);
		$this->assertTextEquals('edit', $this->controller->view);

		TestAuthGeneral::logout($this);
	}

/**
 * 管理者ログイン後 削除テスト
 *
 * @return void
 */
	public function testDelete() {
		TestAuthGeneral::login($this);

		$data = array('Video' => array(
			'id' => 1,
			'key' => 'video_1',
		));
		$frameId = 2;

		$this->testAction(
			'/videos/videos_edit/delete/' . $frameId,
			array(
				'method' => 'delete',
				'data' => $data,
				'return' => 'view',
			)
		);
		$this->assertTextEquals('delete', $this->controller->view);

		TestAuthGeneral::logout($this);
	}
}
