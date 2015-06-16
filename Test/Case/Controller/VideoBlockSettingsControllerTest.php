<?php
/**
 * VideoBlockSettingsControllerTest Case
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('VideosTestBase', 'Videos.Test/Case/Controller');

/**
 * VideoBlockSettingsControllerTest Case
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Announcements\Test\Case\Controller
 */
class VideoBlockSettingsControllerTest extends VideosTestBase {

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
			'Videos.VideoBlockSettings',
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
 * 管理者ログイン後 ブロック一覧 表示テスト
 *
 * @return void
 */
	public function testIndex() {
		RolesControllerTest::login($this);

		$frameId = 1;
		$this->testAction(
			'/videos/video_block_settings/index/' . $frameId,
			array(
				'method' => 'get',
				'return' => 'view',
			)
		);
		$this->assertTextEquals('index', $this->controller->view);

		AuthGeneralControllerTest::logout($this);
	}
}
