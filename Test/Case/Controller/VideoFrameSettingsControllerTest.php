<?php
/**
 * VideoFrameSettingsControllerTest Case
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
 * VideoFrameSettingsControllerTest Case
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Announcements\Test\Case\Controller
 */
class VideoFrameSettingsControllerTest extends VideosTestBase {

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
			'Videos.VideoFrameSettings',
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
 * 管理者ログイン後 表示方法変更画面 表示テスト
 *
 * @return void
 */
	public function testEdit() {
		TestAuthGeneral::login($this);

		$frameId = 2;
		$this->testAction(
			'/videos/videos_edit/edit/' . $frameId,
			array(
				'method' => 'get',
				'return' => 'view',
			)
		);
		$this->assertTextEquals('edit', $this->controller->view);

		TestAuthGeneral::logout($this);
	}

/**
 * 管理者ログイン後 表示方法変更画面 編集テスト
 *
 * @return void
 */
	public function testEditPost() {
		TestAuthGeneral::login($this);

		$data = array(
			//'VideoFrameSetting' => array(), 暫定対応(;'∀') 登録・編集値の具体的に記述する予定
		);

		$frameId = 2;
		$this->testAction(
			'/videos/videos_edit/edit/' . $frameId,
			array(
				'method' => 'post',
				'data' => $data,
				'return' => 'view',
			)
		);
		$this->assertTextEquals('edit', $this->controller->view);

		TestAuthGeneral::logout($this);
	}
}
