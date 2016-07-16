<?php
/**
 * VideosController::tag()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('WorkflowControllerIndexTest', 'Workflow.TestSuite');

/**
 * VideosController::tag()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Videos\Test\Case\Controller\VideosController
 */
class VideosControllerTagTest extends WorkflowControllerIndexTest {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.videos.video',
		'plugin.videos.block_setting_for_video',
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
 * テストDataの取得
 *
 * @return array
 */
	private function __data() {
		$frameId = '6';
		$blockId = '2';

		$data = array(
			'action' => 'tag',
			'frame_id' => $frameId,
			'block_id' => $blockId,
			'id' => '1',	// tagID
		);

		return $data;
	}

/**
 * indexアクションのテスト(ログインなし)用DataProvider
 *
 * ### 戻り値
 *  - urlOptions: URLオプション
 *  - assert: テストの期待値
 *  - exception: Exception
 *  - return: testActionの実行後の結果
 *
 * @return array
 */
	public function dataProviderIndex() {
		$data = $this->__data();

		//テストデータ
		$results = array();
		$results[0] = array(
			'urlOptions' => $data,
			'assert' => array('method' => 'assertNotEmpty'),
		);

		return $results;
	}

/**
 * indexアクションのテスト
 *
 * @param array $urlOptions URLオプション
 * @param array $assert テストの期待値
 * @param string|null $exception Exception
 * @param string $return testActionの実行後の結果
 * @dataProvider dataProviderIndex
 * @return void
 */
	public function testIndex($urlOptions, $assert, $exception = null, $return = 'view') {
		//テスト実行
		parent::testIndex($urlOptions, $assert, $exception, $return);

		//チェック
		$message = __d('tags', 'tag');
		$this->assertTextContains($message, $this->view);
		//debug($this->view);
	}

/**
 * indexアクションのテスト(作成権限あり)用DataProvider
 *
 * ### 戻り値
 *  - urlOptions: URLオプション
 *  - assert: テストの期待値
 *  - exception: Exception
 *  - return: testActionの実行後の結果
 *
 * @return array
 */
	public function dataProviderIndexByCreatable() {
		return array($this->dataProviderIndex()[0]);
	}

/**
 * indexアクションのテスト(作成権限のみ)
 *
 * @param array $urlOptions URLオプション
 * @param array $assert テストの期待値
 * @param string|null $exception Exception
 * @param string $return testActionの実行後の結果
 * @dataProvider dataProviderIndexByCreatable
 * @return void
 */
	public function testIndexByCreatable($urlOptions, $assert, $exception = null, $return = 'view') {
		//テスト実行
		parent::testIndexByCreatable($urlOptions, $assert, $exception, $return);

		//チェック
		$message = __d('tags', 'tag');
		$this->assertTextContains($message, $this->view);
		//debug($this->view);
	}

/**
 * indexアクションのテスト(編集権限あり)用DataProvider
 *
 * ### 戻り値
 *  - urlOptions: URLオプション
 *  - assert: テストの期待値
 *  - exception: Exception
 *  - return: testActionの実行後の結果
 *
 * @return array
 */
	public function dataProviderIndexByEditable() {
		return array($this->dataProviderIndex()[0]);
	}

/**
 * indexアクションのテスト(編集権限あり)
 *
 * @param array $urlOptions URLオプション
 * @param array $assert テストの期待値
 * @param string|null $exception Exception
 * @param string $return testActionの実行後の結果
 * @dataProvider dataProviderIndexByEditable
 * @return void
 */
	public function testIndexByEditable($urlOptions, $assert, $exception = null, $return = 'view') {
		//テスト実行
		parent::testIndexByEditable($urlOptions, $assert, $exception, $return);

		//チェック
		$message = __d('tags', 'tag');
		$this->assertTextContains($message, $this->view);
		//debug($this->view);
	}

}
