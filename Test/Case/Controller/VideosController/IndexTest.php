<?php
/**
 * VideosController::index()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('WorkflowControllerIndexTest', 'Workflow.TestSuite');

/**
 * VideosController::index()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Videos\Test\Case\Controller\VideosController
 */
class VideosControllerIndexTest extends WorkflowControllerIndexTest {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.videos.video',
		'plugin.videos.video_setting',
		'plugin.videos.block_setting_for_video',
		'plugin.videos.video_frame_setting',
		'plugin.likes.like',
		'plugin.likes.likes_user',
		'plugin.tags.tag',
		'plugin.tags.tags_content',
		'plugin.content_comments.content_comment',
		'plugin.categories.category',
		'plugin.categories.category_order',
		'plugin.frames.frame4frames',
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
		return array(
			'正常' => array(
				'urlOptions' => array(
					'action' => 'index',
					'frame_id' => '6',
					'block_id' => '2',
				),
				'assert' => array('method' => 'assertNotEmpty'),
			),
			'ブロック未選択' => array(
				'urlOptions' => array(
					'action' => 'index',
					'frame_id' => '13',
					'block_id' => '2',
				),
				'assert' => array('method' => 'assertNotEmpty'),
			),
			'一覧表示件数指定' => array(
				'urlOptions' => array(
					'action' => 'index',
					'frame_id' => '6',
					'block_id' => '2',
					'limit' => '1',
				),
				'assert' => array('method' => 'assertNotEmpty'),
			),
			'ソート指定' => array(
				'urlOptions' => array(
					'action' => 'index',
					'frame_id' => '6',
					'block_id' => '2',
					'sort' => 'Video.created',
					'direction' => 'desc',
				),
				'assert' => array('method' => 'assertNotEmpty'),
			),
			'ソート条件-新着順' => array(
				'urlOptions' => array(
					'action' => 'index',
					'frame_id' => '2',
					'block_id' => '2',
				),
				'assert' => array('method' => 'assertNotEmpty'),
			),
			'ソート条件-再生回数順' => array(
				'urlOptions' => array(
					'action' => 'index',
					'frame_id' => '4',
					'block_id' => '2',
				),
				'assert' => array('method' => 'assertNotEmpty'),
			),
			'ソート条件-評価順' => array(
				'urlOptions' => array(
					'action' => 'index',
					'frame_id' => '8',
					'block_id' => '2',
				),
				'assert' => array('method' => 'assertNotEmpty'),
			),
		);
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
		$message = __d('videos', 'Add video');
		$this->assertTextNotContains($message, $this->view);
	}

/**
 * indexアクションのPaginator例外テスト
 *
 * @return void
 * @throws InternalErrorException
 */
	public function testIndexException() {
		$this->generate(
			'Videos.Videos', [
				'components' => [
					'Paginator'
				]
			]
		);

		// Exception
		$this->controller->Components->Paginator
			->expects($this->once())
			->method('paginate')
			->will($this->returnCallback(function () {
				throw new InternalErrorException();
			}));

		$urlOptions = array(
			'action' => 'index',
			'frame_id' => '6',
			'block_id' => '2',
		);

		//テスト実施
		$this->_testGetAction($urlOptions, null, 'InternalErrorException', 'index');
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
		return array($this->dataProviderIndex()['正常']);
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
		//debug($this->view);
		$message = __d('videos', 'Add video');
		$this->assertTextContains($message, $this->view);
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
		return array($this->dataProviderIndex()['正常']);
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
		$message = __d('videos', 'Add video');
		$this->assertTextContains($message, $this->view);
	}

}
