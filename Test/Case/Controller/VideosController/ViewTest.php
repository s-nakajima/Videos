<?php
/**
 * VideosController::view()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('WorkflowControllerViewTest', 'Workflow.TestSuite');

/**
 * VideosController::view()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Videos\Test\Case\Controller\VideosController
 */
class VideosControllerViewTest extends WorkflowControllerViewTest {

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
 * テストDataの取得
 *
 * @return array
 */
	private function __data() {
		$frameId = '6';
		$blockId = '2';
		$contentKey = 'content_key_1';

		$data = array(
			'action' => 'view',
			'frame_id' => $frameId,
			'block_id' => $blockId,
			'key' => $contentKey,
		);

		return $data;
	}

/**
 * viewアクションのテスト用DataProvider
 *
 * ### 戻り値
 *  - urlOptions: URLオプション
 *  - assert: テストの期待値
 *  - exception: Exception
 *  - return: testActionの実行後の結果
 *
 * @return array
 */
	public function dataProviderView() {
		$data = $this->__data();

		//テストデータ
		$results = array();
		$results[0] = array(
			'urlOptions' => Hash::insert($data, 'key', 'content_key_1'),
			'assert' => array('method' => 'assertNotEmpty'),
		);
		$results[1] = array(
			'urlOptions' => Hash::insert($data, 'key', 'content_key_2'),
			'assert' => null, 'exception' => 'BadRequestException'
		);
		$results[2] = array(
			'urlOptions' => Hash::insert($data, 'key', 'content_key_3'),
			'assert' => array('method' => 'assertNotEmpty'),
		);
		$results[3] = array(
			'urlOptions' => Hash::insert($data, 'key', 'content_key_4'),
			'assert' => array('method' => 'assertNotEmpty'),
		);
		$results[4] = array(
			'urlOptions' => Hash::insert($data, 'key', 'content_key_5'),
			'assert' => null, 'exception' => 'BadRequestException'
		);
		$results[5] = array(
			'urlOptions' => Hash::insert($data, 'key', 'content_key_999'),
			'assert' => null, 'exception' => 'BadRequestException'
		);
		$results[6] = array(
			'urlOptions' => Hash::insert($data, 'key', 'content_key_999'),
			'assert' => null, 'exception' => 'BadRequestException',
			'return' => 'json',
		);

		return $results;
	}

/**
 * viewアクションのテスト
 *
 * @param array $urlOptions URLオプション
 * @param array $assert テストの期待値
 * @param string|null $exception Exception
 * @param string $return testActionの実行後の結果
 * @dataProvider dataProviderView
 * @return void
 */
	public function testView($urlOptions, $assert, $exception = null, $return = 'view') {
		//テスト実行
		parent::testView($urlOptions, $assert, $exception, $return);
		if ($exception) {
			return;
		}

		//チェック
		$this->__assertView($urlOptions['key'], false);
	}

/**
 * viewアクションのクッキー3500byteオーバーテスト
 *
 * @return void
 * @throws InternalErrorException
 */
	public function testViewCookie() {
		$this->generate(
			'Videos.Videos', [
				'components' => [
					'Cookie' => ['read']
				]
			]
		);

		// --- 3500 byte
		$cookieStr = 'so26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3Aso26344108%3A1433308937%3A1433308944%3A1fe90245b40b2c5f%3A3%2Csm4705448%3A1433308641%3A1433308641%3A1';
		$this->controller->Components->Cookie
			->expects($this->at(0))
			->method('read')
			->will($this->returnValue($cookieStr));

		$urlOptions = $this->__data();

		//テスト実施
		$this->_testGetAction($urlOptions, array('method' => 'assertNotEmpty'), null, 'view');
	}

/**
 * viewアクションのテスト(作成権限のみ)用DataProvider
 *
 * ### 戻り値
 *  - urlOptions: URLオプション
 *  - assert: テストの期待値
 *  - exception: Exception
 *  - return: testActionの実行後の結果
 *
 * @return array
 */
	public function dataProviderViewByCreatable() {
		$data = $this->__data();

		//テストデータ
		$results = array();
		$results[0] = array(
			'urlOptions' => Hash::insert($data, 'key', 'content_key_1'),
			'assert' => array('method' => 'assertNotEmpty'),
		);
		$results[1] = array(
			'urlOptions' => Hash::insert($data, 'key', 'content_key_2'),
			'assert' => array('method' => 'assertNotEmpty'),
		);
		$results[2] = array(
			'urlOptions' => Hash::insert($data, 'key', 'content_key_3'),
			'assert' => array('method' => 'assertNotEmpty'),
		);
		$results[3] = array(
			'urlOptions' => Hash::insert($data, 'key', 'content_key_4'),
			'assert' => array('method' => 'assertNotEmpty'),
		);
		$results[4] = array(
			'urlOptions' => Hash::insert($data, 'key', 'content_key_5'),
			'assert' => null, 'exception' => 'BadRequestException'
		);
		$results[5] = array(
			'urlOptions' => Hash::insert($data, 'key', 'content_key_999'),
			'assert' => null, 'exception' => 'BadRequestException'
		);

		return $results;
	}

/**
 * viewアクションのテスト(作成権限のみ)
 *
 * @param array $urlOptions URLオプション
 * @param array $assert テストの期待値
 * @param string|null $exception Exception
 * @param string $return testActionの実行後の結果
 * @dataProvider dataProviderViewByCreatable
 * @return void
 */
	public function testViewByCreatable($urlOptions, $assert, $exception = null, $return = 'view') {
		//テスト実行
		parent::testViewByCreatable($urlOptions, $assert, $exception, $return);
		if ($exception) {
			return;
		}

		//チェック
		if ($urlOptions['key'] === 'content_key_1') {
			$this->__assertView($urlOptions['key'], false);

		} elseif ($urlOptions['key'] === 'content_key_3') {
			$this->__assertView($urlOptions['key'], true);

		} elseif ($urlOptions['key'] === 'content_key_4') {
			$this->__assertView($urlOptions['key'], false);

		} else {
			$this->__assertView($urlOptions['key'], false);
		}
	}

/**
 * viewアクションのテスト用DataProvider
 *
 * ### 戻り値
 *  - urlOptions: URLオプション
 *  - assert: テストの期待値
 *  - exception: Exception
 *  - return: testActionの実行後の結果
 *
 * @return array
 */
	public function dataProviderViewByEditable() {
		$data = $this->__data();

		//テストデータ
		$results = array();
		$results[0] = array(
			'urlOptions' => Hash::insert($data, 'key', 'content_key_1'),
			'assert' => array('method' => 'assertNotEmpty'),
		);
		$results[1] = array(
			'urlOptions' => Hash::insert($data, 'key', 'content_key_2'),
			'assert' => array('method' => 'assertNotEmpty'),
		);
		$results[2] = array(
			'urlOptions' => Hash::insert($data, 'key', 'content_key_3'),
			'assert' => array('method' => 'assertNotEmpty'),
		);
		$results[3] = array(
			'urlOptions' => Hash::insert($data, 'key', 'content_key_4'),
			'assert' => array('method' => 'assertNotEmpty'),
		);
		$results[4] = array(
			'urlOptions' => Hash::insert($data, 'key', 'content_key_5'),
			'assert' => array('method' => 'assertNotEmpty'),
		);
		$results[5] = array(
			'urlOptions' => Hash::insert($data, 'key', 'content_key_999'),
			'assert' => null, 'exception' => 'BadRequestException'
		);

		return $results;
	}

/**
 * viewアクションのテスト(編集権限あり)
 *
 * @param array $urlOptions URLオプション
 * @param array $assert テストの期待値
 * @param string|null $exception Exception
 * @param string $return testActionの実行後の結果
 * @dataProvider dataProviderViewByEditable
 * @return void
 */
	public function testViewByEditable($urlOptions, $assert, $exception = null, $return = 'view') {
		//テスト実行
		parent::testViewByEditable($urlOptions, $assert, $exception, $return);
		if ($exception) {
			return;
		}

		//チェック
		$this->__assertView($urlOptions['key'], true);
	}

/**
 * view()のassert
 *
 * @param string $contentKey コンテンツキー
 * @param bool $isLatest 最終コンテンツかどうか
 * @return void
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 */
	private function __assertView($contentKey, $isLatest = false) {
		if ($contentKey === 'content_key_1') {
			if ($isLatest) {
				//コンテンツのデータ(id=2, key=content_key_1)に対する期待値
				$this->assertTextContains('Title 2', $this->view);
			} else {
				//コンテンツのデータ(id=1, key=content_key_1)に対する期待値
				$this->assertTextContains('Title 1', $this->view);
			}

		} elseif ($contentKey === 'content_key_2') {
			//コンテンツのデータ(id=3, key=content_key_2)に対する期待値
			$this->assertTextContains('Title 3', $this->view);

		} elseif ($contentKey === 'content_key_3') {
			if ($isLatest) {
				//コンテンツのデータ(id=5, key=content_key_3)に対する期待値
				$this->assertTextContains('Title 5', $this->view);
			} else {
				//コンテンツのデータ(id=4, key=content_key_3)に対する期待値
				$this->assertTextContains('Title 4', $this->view);
			}

		} elseif ($contentKey === 'content_key_4') {
			if ($isLatest) {
				//コンテンツのデータ(id=7, key=content_key_4)に対する期待値
				$this->assertTextContains('Title 7', $this->view);
			} else {
				//コンテンツのデータ(id=6, key=content_key_4)に対する期待値
				$this->assertTextContains('Title 6', $this->view);
			}

		} elseif ($contentKey === 'content_key_5') {
			//コンテンツのデータ(id=8, key=content_key_5)に対する期待値
			$this->assertTextContains('Title 8', $this->view);
		}
	}

}
