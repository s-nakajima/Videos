<?php
/**
 * VideosEditControllerExceptionTest Case
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
 * VideosEditControllerExceptionTest Case
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Announcements\Test\Case\Controller
 */
class VideosEditControllerExceptionTest extends VideosTestBase {

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
 * 管理者ログイン後  編集画面表示 例外テスト
 * $videoKey なしのため、エラー
 *
 * @return void
 * @throws Exception
 */
	public function testEditException() {
		$this->setExpectedException('BadRequestException');

		TestAuthGeneral::login($this);

		$frameId = 2;
		try {
			$this->testAction(
				'/videos/videos_edit/edit/' . $frameId,
				array(
					'method' => 'get',
					'return' => 'view',
				)
			);
		} catch (Exception $e) {
			TestAuthGeneral::logout($this);
			throw $e;
		}
	}

/**
 * 管理者ログイン後  削除 例外テスト
 * 'method' => 'delete' 以外のため、エラー
 *
 * @return void
 * @throws Exception
 */
	public function testDeleteException() {
		$this->setExpectedException('BadRequestException');

		TestAuthGeneral::login($this);

		$frameId = 2;
		try {
			$this->testAction(
				'/videos/videos_edit/delete/' . $frameId,
				array(
					'method' => 'post',
					'return' => 'view',
				)
			);
		} catch (Exception $e) {
			TestAuthGeneral::logout($this);
			throw $e;
		}
	}
}
