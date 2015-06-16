<?php
/**
 * VideoBlockSettingTest Case
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('VideoTestBase', 'Videos.Test/Case/Model');
App::uses('Controller', 'Controller');

/**
 * VideoBlockSettingTest Case
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Videos\Test\Case\Model
 */
class VideoBlockSettingTest extends VideoTestBase {

/**
 * VideoBlockSettingデータ取得 テスト
 *
 * @return void
 */
	public function testGetVideoBlockSetting() {
		$blockKey = 'block_1';
		$roomId = 1;
		$videoBlockSetting = $this->VideoBlockSetting->getVideoBlockSetting($blockKey, $roomId);

		$this->assertInternalType('array', $videoBlockSetting);
	}

/**
 * VideoBlockSettingデータ取得 新規データ取得 テスト
 * $blockKey = nullで検索によって、取得データなし時の動作テスト
 *
 * @return void
 */
	public function testGetVideoBlockSettingCreate() {
		$blockKey = null;
		$roomId = 1;
		$videoBlockSetting = $this->VideoBlockSetting->getVideoBlockSetting($blockKey, $roomId);

		$this->assertInternalType('array', $videoBlockSetting);
	}

/**
 * VideoBlockSettingデータ保存 テスト
 *
 * @return void
 */
	public function testSaveVideoBlockSetting() {
		// saveVideoBlockSetting で保存する $data 取得
		$data = $this->_getVideoBlockSettingTestData();

		$videoBlockSetting = $this->VideoBlockSetting->saveVideoBlockSetting($data);

		$this->assertInternalType('array', $videoBlockSetting);
	}

/**
 * VideoBlockSettingデータ削除テスト
 *
 * @return void
 */
	public function testDeteVideoBlockSetting() {
		$blockId = 1;
		// ブロック取得
		$block = $this->Block->findById($blockId);

		$rtn = $this->VideoBlockSetting->deleteVideoBlockSetting($block);

		$this->assertTrue($rtn);
	}

/**
 * blockRolePermissionデータ保存 テスト
 *
 * @return void
 */
	public function testSaveBlockRolePermission() {
		$blockKey = 'block_1';
		$roomId = 1;

		// 取得
		$videoBlockSetting = $this->VideoBlockSetting->getVideoBlockSetting(
			$blockKey,
			$roomId
		);

		$netCommonsBlock = new NetCommonsBlockComponent(new ComponentCollection());
		$controller = new Controller();
		$controller->viewVars['languageId'] = 2;
		$controller->viewVars['roomId'] = $roomId;
		$netCommonsBlock->initialize($controller);

		$permissions = $netCommonsBlock->getBlockRolePermissions(
			$blockKey,
			array('content_creatable', 'content_publishable', 'content_comment_creatable', 'content_comment_publishable')
		);

		// 更新時間を再セット
		unset($videoBlockSetting['VideoBlockSetting']['modified']);
		$data = $videoBlockSetting;
		$data['BlockRolePermission']['content_creatable'] = $permissions['BlockRolePermissions']['content_creatable'];
		$data['BlockRolePermission']['content_creatable']['room_administrator']['block_key'] = $blockKey;
		$data['BlockRolePermission']['content_creatable']['chief_editor']['block_key'] = $blockKey;
		$data['BlockRolePermission']['content_creatable']['editor']['block_key'] = $blockKey;
		$data['BlockRolePermission']['content_creatable']['general_user']['block_key'] = $blockKey;
		$data['BlockRolePermission']['content_creatable']['visitor']['block_key'] = $blockKey;

		$videoBlockSetting = $this->VideoBlockSetting->saveBlockRolePermission($data);

		$this->assertInternalType('array', $videoBlockSetting);
	}
}
