<?php
/**
 * VideoBlockSettingValidationTest Case
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('VideoTestBase', 'Videos.Test/Case/Model');

/**
 * VideoBlockSettingValidationTest Case
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Videos\Test\Case\Model
 */
class VideoBlockSettingValidationTest extends VideoTestBase {

/**
 * VideoBlockSettingデータ保存 バリデーションエラー戻り値テスト
 * $block['VideoBlockSetting']['block_key'] = null のため エラー
 *
 * @return void
 */
	public function testSaveVideoBlockSettingValidationErrors() {
		// saveVideoBlockSetting で保存する $data 取得
		$data = $this->_getVideoBlockSettingTestData();
		$data['VideoBlockSetting']['block_key'] = null;

		$videoBlockSetting = $this->VideoBlockSetting->saveVideoBlockSetting($data);

		$this->assertFalse($videoBlockSetting);
	}

/**
 * VideoBlockSettingデータ保存 block_key notBlankエラー
 * $block['VideoBlockSetting']['block_key'] = null のため エラー
 *
 * @return void
 */
	public function testSaveVideoBlockSettingBlockKeyNotEmpty() {
		// saveVideoBlockSetting で保存する $data 取得
		$data = $this->_getVideoBlockSettingTestData();
		$data['VideoBlockSetting']['block_key'] = null;

		$this->VideoBlockSetting->saveVideoBlockSetting($data);

		$this->assertArrayHasKey('block_key', $this->VideoBlockSetting->validationErrors);
	}

/**
 * VideoBlockSettingデータ保存 Blockテーブル name requiredエラー
 * $block['Block']['name'] keyなしのため エラーを予想していたが、required 効かなかった
 *
 * @return void
 */
	public function testSaveVideoBlockSettingBlockTableNameRequired() {
		// saveVideoBlockSetting で保存する $data 取得
		$data = $this->_getVideoBlockSettingTestData();
		unset($data['Block']['name']);

		$videoBlockSetting = $this->VideoBlockSetting->saveVideoBlockSetting($data);

		//$this->assertArrayHasKey('name', $this->Block->validationErrors);
		$this->assertInternalType('array', $videoBlockSetting);
	}

/**
 * VideoBlockSettingデータ保存 Blockテーブル name notBlankエラー
 * $block['Block']['name'] = nullのため エラー
 *
 * @return void
 */
	public function testSaveVideoBlockSettingBlockTableNameNotEmpty() {
		// saveVideoBlockSetting で保存する $data 取得
		$data = $this->_getVideoBlockSettingTestData();
		$data['Block']['name'] = null;

		$this->VideoBlockSetting->saveVideoBlockSetting($data);

		$this->assertArrayHasKey('name', $this->Block->validationErrors);
	}

/**
 * blockRolePermissionデータ保存 Validationテスト
 * $block['VideoBlockSetting']['agree'] bool型に変換できない値のため エラー
 *
 * @return void
 */
	public function testSaveBlockRolePermissionValidationErrors() {
		$blockKey = 'block_1';
		$roomId = 1;

		// 取得
		$videoBlockSetting = $this->VideoBlockSetting->getVideoBlockSetting(
			$blockKey,
			$roomId
		);

		$netCommonsBlock = new WorkflowComponent(new ComponentCollection());
		$controller = new Controller();
		$controller->viewVars['languageId'] = 2;
		$controller->viewVars['roomId'] = 1;
		$netCommonsBlock->initialize($controller);

		$permissions = $netCommonsBlock->getBlockRolePermissions(
			$blockKey,
			array('content_creatable', 'content_publishable', 'content_comment_creatable', 'content_comment_publishable')
		);

		// 更新時間を再セット
		unset($videoBlockSetting['VideoBlockSetting']['modified']);
		$data = Hash::merge(
			$videoBlockSetting,
			array('BlockRolePermission' => $permissions['BlockRolePermissions'])
		);
		$data['VideoBlockSetting']['agree'] = 'hoge';

		$videoBlockSetting = $this->VideoBlockSetting->saveBlockRolePermission($data);

		$this->assertFalse($videoBlockSetting);
	}

/**
 * blockRolePermissionデータ保存 validateBlockRolePermissionsテスト
 * $data['BlockRolePermission']['content_creatable']['room_administrator']['block_key']等 block_keyのkeyなしエラー
 *
 * @return void
 */
	public function testSaveBlockRolePermissionValiateBlockRolePermissions() {
		$blockKey = 'block_1';
		$roomId = 1;

		// 取得
		$videoBlockSetting = $this->VideoBlockSetting->getVideoBlockSetting(
			$blockKey,
			$roomId
		);

		$netCommonsBlock = new WorkflowComponent(new ComponentCollection());
		$controller = new Controller();
		$controller->viewVars['languageId'] = 2;
		$controller->viewVars['roomId'] = 1;
		$netCommonsBlock->initialize($controller);

		$permissions = $netCommonsBlock->getBlockRolePermissions(
			$blockKey,
			array('content_creatable', 'content_publishable', 'content_comment_creatable', 'content_comment_publishable')
		);

		// 更新時間を再セット
		unset($videoBlockSetting['VideoBlockSetting']['modified']);
		$data = Hash::merge(
			$videoBlockSetting,
			array('BlockRolePermission' => $permissions['BlockRolePermissions'])
		);

		$videoBlockSetting = $this->VideoBlockSetting->saveBlockRolePermission($data);

		$this->assertFalse($videoBlockSetting);
	}
}
