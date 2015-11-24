<?php
/**
 * VideoBlockSettingExceptionTest Case
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
 * VideoBlockSettingExceptionTest Case
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Videos\Test\Case\Model
 */
class VideoBlockSettingExceptionTest extends VideoTestBase {

/**
 * VideoBlockSettingデータ保存 例外テスト
 *
 * @return void
 */
	public function testSaveVideoBlockSettingException() {
		$this->setExpectedException('InternalErrorException');

		// saveVideoBlockSetting で保存する $data 取得
		$data = $this->_getVideoBlockSettingTestData();

		// modelモック
		$modelMock = $this->getMockForModel('Videos.VideoBlockSetting', ['save']);
		$modelMock->expects($this->any())
			->method('save')
			->will($this->returnValue(false));

		$modelMock->saveVideoBlockSetting($data);
	}

/**
 * VideoBlockSettingデータ削除 例外テスト
 *
 * @return void
 */
	public function testDeteVideoBlockSettingException() {
		$this->setExpectedException('InternalErrorException');

		$blockId = 1;
		// ブロック取得
		$block = $this->Block->findById($blockId);

		// modelモック
		$modelMock = $this->getMockForModel('Videos.VideoBlockSetting', ['deleteAll']);
		$modelMock->expects($this->any())
			->method('deleteAll')
			->will($this->returnValue(false));

		$modelMock->deleteVideoBlockSetting($block);
	}

/**
 * VideoBlockSettingデータ削除 Video例外テスト
 *
 * @return void
 */
	public function testDeteVideoBlockSettingVideoException() {
		$this->setExpectedException('InternalErrorException');

		$blockId = 1;
		// ブロック取得
		$block = $this->Block->findById($blockId);

		// modelモック
		$modelMock = $this->getMockForModel('Videos.Video', ['deleteAll']);
		$modelMock->expects($this->any())
			->method('deleteAll')
			->will($this->returnValue(false));
		$this->VideoBlockSetting->Video = $modelMock;

		$this->VideoBlockSetting->deleteVideoBlockSetting($block);
	}

/**
 * VideoBlockSettingデータ削除 Comment例外テスト
 *
 * @return void
 */
	public function testDeteVideoBlockSettingCommentException() {
		$this->setExpectedException('InternalErrorException');

		$blockId = 1;
		// ブロック取得
		$block = $this->Block->findById($blockId);

		// modelモック
		$modelMock = $this->getMockForModel('Comments.Comment', ['deleteAll']);
		$modelMock->expects($this->any())
			->method('deleteAll')
			->will($this->returnValue(false));
		$this->VideoBlockSetting->Comment = $modelMock;

		$this->VideoBlockSetting->deleteVideoBlockSetting($block);
	}

/**
 * VideoBlockSettingデータ削除 ContentComment 例外テスト
 *
 * @return void
 */
	public function testDeteVideoBlockSettingContentCommentException() {
		$this->setExpectedException('InternalErrorException');

		$blockId = 1;
		// ブロック取得
		$block = $this->Block->findById($blockId);

		// modelモック
		$modelMock = $this->getMockForModel('ContentComments.ContentComment', ['deleteAll']);
		$modelMock->expects($this->any())
			->method('deleteAll')
			->will($this->returnValue(false));
		$this->VideoBlockSetting->ContentComment = $modelMock;

		$this->VideoBlockSetting->deleteVideoBlockSetting($block);
	}

/**
 * VideoBlockSettingデータ削除 TagsContent 例外テスト
 *
 * @return void
 */
	public function testDeteVideoBlockSettingTagsContentException() {
		$this->setExpectedException('InternalErrorException');

		$blockId = 1;
		// ブロック取得
		$block = $this->Block->findById($blockId);

		// modelモック
		$modelMock = $this->getMockForModel('Tags.TagsContent', ['deleteAll']);
		$modelMock->expects($this->any())
			->method('deleteAll')
			->will($this->returnValue(false));
		$this->VideoBlockSetting->TagsContent = $modelMock;

		$this->VideoBlockSetting->deleteVideoBlockSetting($block);
	}

/**
 * VideoBlockSettingデータ削除 Tag 例外テスト
 *
 * @return void
 */
	public function testDeteVideoBlockSettingTagException() {
		$this->setExpectedException('InternalErrorException');

		$blockId = 1;
		// ブロック取得
		$block = $this->Block->findById($blockId);

		// modelモック
		$modelMock = $this->getMockForModel('Tags.Tag', ['deleteAll']);
		$modelMock->expects($this->any())
			->method('deleteAll')
			->will($this->returnValue(false));
		$this->VideoBlockSetting->Tag = $modelMock;

		$this->VideoBlockSetting->deleteVideoBlockSetting($block);
	}

/**
 * VideoBlockSettingデータ削除 Like 例外テスト
 *
 * @return void
 */
	public function testDeteVideoBlockSettingLikeException() {
		$this->setExpectedException('InternalErrorException');

		$blockId = 1;
		// ブロック取得
		$block = $this->Block->findById($blockId);

		// modelモック
		$modelMock = $this->getMockForModel('Likes.Like', ['deleteAll']);
		$modelMock->expects($this->any())
			->method('deleteAll')
			->will($this->returnValue(false));
		$this->VideoBlockSetting->Like = $modelMock;

		$this->VideoBlockSetting->deleteVideoBlockSetting($block);
	}

/**
 * blockRolePermissionデータ保存 例外テスト
 *
 * @return void
 */
	public function testSaveBlockRolePermissionException() {
		$this->setExpectedException('InternalErrorException');

		$blockKey = 'block_1';

		// 取得
		$videoBlockSetting = $this->VideoBlockSetting->getVideoBlockSetting($blockKey);

		$netCommonsBlock = new NetCommonsBlockComponent(new ComponentCollection());
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
		$data = $videoBlockSetting;
		$data['BlockRolePermission']['content_creatable'] = $permissions['BlockRolePermissions']['content_creatable'];
		$data['BlockRolePermission']['content_creatable']['room_administrator']['block_key'] = $blockKey;
		$data['BlockRolePermission']['content_creatable']['chief_editor']['block_key'] = $blockKey;
		$data['BlockRolePermission']['content_creatable']['editor']['block_key'] = $blockKey;
		$data['BlockRolePermission']['content_creatable']['general_user']['block_key'] = $blockKey;
		$data['BlockRolePermission']['content_creatable']['visitor']['block_key'] = $blockKey;

		// modelモック
		$modelMock = $this->getMockForModel('Videos.VideoBlockSetting', ['save']);
		$modelMock->expects($this->any())
			->method('save')
			->will($this->returnValue(false));

		$modelMock->saveBlockRolePermission($data);
	}

/**
 * blockRolePermissionデータ保存 BlockRolePermission例外テスト
 *
 * @return void
 */
	public function testSaveBlockRolePermissionBlockRolePermissionException() {
		$this->setExpectedException('InternalErrorException');

		$blockKey = 'block_1';

		// 取得
		$videoBlockSetting = $this->VideoBlockSetting->getVideoBlockSetting($blockKey);

		$netCommonsBlock = new NetCommonsBlockComponent(new ComponentCollection());
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
		$data = $videoBlockSetting;
		$data['BlockRolePermission']['content_creatable'] = $permissions['BlockRolePermissions']['content_creatable'];
		$data['BlockRolePermission']['content_creatable']['room_administrator']['block_key'] = $blockKey;
		$data['BlockRolePermission']['content_creatable']['chief_editor']['block_key'] = $blockKey;
		$data['BlockRolePermission']['content_creatable']['editor']['block_key'] = $blockKey;
		$data['BlockRolePermission']['content_creatable']['general_user']['block_key'] = $blockKey;
		$data['BlockRolePermission']['content_creatable']['visitor']['block_key'] = $blockKey;

		// modelモック
		$modelMock = $this->getMockForModel('Blocks.BlockRolePermission', ['saveMany']);
		$modelMock->expects($this->any())
			->method('saveMany')
			->will($this->returnValue(false));
		$this->VideoBlockSetting->BlockRolePermission = $modelMock;

		$this->VideoBlockSetting->saveBlockRolePermission($data);
	}
}
