<?php
/**
 * VideoBlocksController::add(),edit(),delete()
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('BlocksControllerEditTest', 'Blocks.TestSuite');

/**
 * VideoBlocksController::add(),edit(),delete()
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Videos\Test\Case\Controller\VideoBlocksController
 */
class VideoBlocksControllerEditTest extends BlocksControllerEditTest {

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
		'plugin.workflow.workflow_comment',
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
	protected $_controller = 'video_blocks';

/**
 * Edit controller name
 *
 * @var string
 */
	protected $_editController = 'video_blocks';

/**
 * テストDataの取得
 *
 * @param bool $isEdit 編集かどうか
 * @return array
 */
	private function __data($isEdit) {
		$frameId = '6';
		$frameKey = 'frame_3';
		if ($isEdit) {
			$blockId = '4';
			$blockKey = 'block_2';
			$videoId = '3';
			$flg = 1;
			$displayOrder = 'new';
			$displayNumber = 5;
			$blockName = 'Channel name';
		} else {
			$blockId = 1;
			$blockKey = 'block_1';
			$videoId = null;
			$flg = 1;
			$displayOrder = 'new';
			$displayNumber = 5;
			$blockName = null;
		}

		$data = array(
			'Frame' => array(
				'id' => $frameId,
				'key' => $frameKey
			),
			'Block' => array(
				'id' => $blockId,
				'key' => $blockKey,
				'language_id' => '2',
				'room_id' => '1',
				'plugin_key' => $this->plugin,
				'public_type' => '1',
				'from' => null,
				'to' => null,
				'name' => $blockName,
			),
			'VideoBlockSetting' => array(
				'id' => $videoId,
				'block_key' => $blockKey,
				'use_like' => $flg,
				'use_unlike' => $flg,
				'use_comment' => $flg,
				'use_workflow' => $flg,
				'auto_play' => $flg,
				'use_comment_approval' => $flg,
			),
			'VideoFrameSetting' => array(
				'id' => $frameId,
				'frame_key' => $frameKey,
				'display_order' => $displayOrder,
				'display_number' => $displayNumber,
			),
		);

		return $data;
	}

/**
 * add()アクションDataProvider
 *
 * ### 戻り値
 *  - method: リクエストメソッド（get or post or put）
 *  - data: 登録データ
 *  - validationError: バリデーションエラー
 *
 * @return array
 */
	public function dataProviderAdd() {
		$data = $this->__data(false);

		//テストデータ
		$results = array();
		$results[0] = array('method' => 'get');
		$results[1] = array('method' => 'put');
		$data['Block']['name'] = 'Channel name';
		$results[2] = array('method' => 'post', 'data' => $data, 'validationError' => false);
		unset($data['Block']['name']);
		$results[3] = array('method' => 'post', 'data' => $data,
			'validationError' => array(
				'field' => 'Block.name',
				'value' => '',
				'message' => sprintf(__d('net_commons', 'Please input %s.'), __d('videos', 'Channel name')),
			)
		);

		return $results;
	}

/**
 * edit()アクションDataProvider
 *
 * ### 戻り値
 *  - method: リクエストメソッド（get or post or put）
 *  - data: 登録データ
 *  - validationError: バリデーションエラー
 *
 * @return array
 */
	public function dataProviderEdit() {
		$data = $this->__data(true);

		//テストデータ
		$results = array();
		$results[0] = array('method' => 'get');
		$results[1] = array('method' => 'post');
		$results[2] = array('method' => 'put', 'data' => $data, 'validationError' => false);
		$results[3] = array('method' => 'put', 'data' => $data,
			'validationError' => array(
				'field' => 'Block.name',
				'value' => '',
				'message' => sprintf(__d('net_commons', 'Please input %s.'), __d('videos', 'Channel name')),
			)
		);

		return $results;
	}

/**
 * editアクションのGET例外テスト
 *
 * @return void
 */
	public function testEditGetException() {
		//ログイン
		TestAuthGeneral::login($this);

		$this->_mockForReturnFalse('Videos.VideoBlockSetting', 'getVideoBlockSetting');

		$frameId = '6';
		$blockId = '4';

		//テスト実施
		$url = array(
			'plugin' => $this->plugin,
			'controller' => $this->_controller,
			'action' => 'edit',
			'frame_id' => $frameId,
			'block_id' => $blockId
		);
		$params = array(
			'method' => 'get',
			'return' => 'view',
		);
		$return = 'view';
		$this->_testNcAction($url, $params, 'BadRequestException', $return);

		$this->fail('テストNG');
	}

/**
 * editアクションのGET例外テスト json
 *
 * @return void
 */
	public function testEditGetAjaxFail() {
		//ログイン
		TestAuthGeneral::login($this);

		$this->_mockForReturnFalse('Videos.VideoBlockSetting', 'getVideoBlockSetting');

		$frameId = '6';
		$blockId = '4';

		//テスト実施
		$url = array(
			'plugin' => $this->plugin,
			'controller' => $this->_controller,
			'action' => 'edit',
			'frame_id' => $frameId,
			'block_id' => $blockId
		);
		$params = array(
			'method' => 'get',
			'return' => 'view',
		);
		$return = 'json';
		$result = $this->_testNcAction($url, $params, 'BadRequestException', $return);

		// チェック
		// 不正なリクエスト
		$this->assertEquals(400, $result['code']);

		//ログアウト
		TestAuthGeneral::logout($this);
	}

/**
 * delete()アクションDataProvider
 *
 * ### 戻り値
 *  - data 削除データ
 *
 * @return array
 */
	public function dataProviderDelete() {
		$data = array(
			'Block' => array(
				'id' => '4',
				'key' => 'block_2',
			),
		);

		//テストデータ
		$results = array();
		$results[0] = array('data' => $data);

		return $results;
	}

/**
 * deleteアクションのDELETE例外テスト json
 *
 * @return void
 */
	public function testDeleteAjaxFail() {
		//ログイン
		TestAuthGeneral::login($this);

		$this->_mockForReturnFalse('Videos.VideoBlockSetting', 'deleteVideoBlockSetting');

		$frameId = '6';
		$blockId = '4';

		//アクション実行
		$url = NetCommonsUrl::actionUrl(array(
			'plugin' => $this->plugin,
			'controller' => $this->_controller,
			'action' => 'delete',
			'frame_id' => $frameId,
			'block_id' => $blockId
		));
		$data = array(
			'Block' => array(
				'id' => $blockId,
				'key' => 'block_2',
			),
		);
		$params = array(
			'method' => 'delete',
			'return' => 'view',
			'data' => $data,
		);
		//$this->testAction($url, $params);
		$return = 'json';
		$result = $this->_testNcAction($url, $params, 'BadRequestException', $return);

		// チェック
		// 不正なリクエスト
		$this->assertEquals(400, $result['code']);

		//ログアウト
		TestAuthGeneral::logout($this);
	}
}
