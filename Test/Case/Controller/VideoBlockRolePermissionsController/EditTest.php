<?php
/**
 * VideoBlockRolePermissionsController::edit()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('BlockRolePermissionsControllerEditTest', 'Blocks.TestSuite');

/**
 * VideoBlockRolePermissionsController::edit()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Videos\Test\Case\Controller\VideoBlockRolePermissionsController
 */
class VideoBlockRolePermissionsControllerEditTest extends BlockRolePermissionsControllerEditTest {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.videos.video',
		'plugin.videos.video_block_setting',
		'plugin.videos.video_frame_setting',
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
	protected $_controller = 'video_block_role_permissions';

/**
 * 権限設定で使用するFieldsの取得
 *
 * @return array
 */
	private function __approvalFields() {
		$data = array(
			'VideoBlockSetting' => array(
				'use_workflow',
				'use_comment_approval',
				'approval_type',
			)
		);

		return $data;
	}

/**
 * テストDataの取得
 *
 * @return array
 */
	private function __data() {
		$blockId = '4';
		$blockKey = 'block_2';
		$blockName = 'Channel name';
		$data = array(
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
				'id' => 2,
				'block_key' => 'block_2',
				'use_workflow' => true,
				'use_comment_approval' => true,
				'approval_type' => true,
			)
		);

		return $data;
	}

/**
 * edit()アクションDataProvider
 *
 * ### 戻り値
 *  - approvalFields コンテンツ承認の利用有無のフィールド
 *  - exception Exception
 *  - return testActionの実行後の結果
 *
 * @return array
 */
	public function dataProviderEditGet() {
		return array(
			'editアクションのGETテスト:表示' => array(
				'approvalFields' => $this->__approvalFields(),
			),
		);
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
 * edit()アクションDataProvider
 *
 * ### 戻り値
 *  - data POSTデータ
 *  - exception Exception
 *  - return testActionの実行後の結果
 *
 * @return array
 */
	public function dataProviderEditPost() {
		return array(
			'editアクションのGETテスト:表示' => array(
				'data' => $this->__data(),
			),
		);
	}

/**
 * editアクションのPOST validateエラーテスト
 *
 * @return void
 */
	public function testEditPostValidateError() {
		//ログイン
		TestAuthGeneral::login($this);

		$data = $this->__data();
		$data['VideoBlockSetting']['use_workflow'] = 'xxx';

		$frameId = '6';
		$blockId = '4';
		$blockKey = 'block_2';
		$roomId = '1';
		$permissions = $this->_getPermissionData(true, Hash::check($data, '{s}.use_comment_approval'));

		$RolesRoomFixture = new RolesRoomFixture();
		$rolesRooms = Hash::extract($RolesRoomFixture->records, '{n}[room_id=' . $roomId . ']');

		$default['Block'] = array('id' => $blockId, 'key' => $blockKey);
		foreach ($permissions as $permission => $roles) {
			foreach ($roles as $role) {
				$rolesRoom = Hash::extract($rolesRooms, '{n}[role_key=' . $role . ']');
				$default['BlockRolePermission'][$permission][$role] = array(
					'roles_room_id' => $rolesRoom[0]['id'],
					'block_key' => $blockKey,
					'permission' => $permission,
					'value' => '1',
				);
			}
		}

		//テスト実施
		$url = array(
			'action' => 'edit',
			'frame_id' => $frameId,
			'block_id' => $blockId
		);
		$this->_testPostAction('post', Hash::merge($default, $data), $url);
		//debug($this->controller->validationErrors);

		//ログアウト
		TestAuthGeneral::logout($this);

		// チェック
		$this->assertEquals($this->controller->validationErrors['use_workflow'][0], __d('net_commons', 'Invalid request.'));
	}

}
