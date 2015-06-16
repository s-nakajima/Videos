<?php
/**
 * VideoBlockRolePermissionsControllerTest Case
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
 * VideoBlockRolePermissionsControllerTest Case
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Announcements\Test\Case\Controller
 */
class VideoBlockRolePermissionsControllerTest extends VideosTestBase {

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
			'Videos.VideoBlockRolePermissions',
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
		RolesControllerTest::login($this);

		$frameId = 1;
		$blockId = 1;
		$this->testAction(
			'/videos/video_block_role_permissions/edit/' . $frameId . '/' . $blockId,
			array(
				'method' => 'get',
				'return' => 'view',
			)
		);
		$this->assertTextEquals('edit', $this->controller->view);

		AuthGeneralControllerTest::logout($this);
	}
}
