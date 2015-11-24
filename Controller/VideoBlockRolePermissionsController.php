<?php
/**
 * 権限設定 Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('VideosAppController', 'Videos.Controller');

/**
 * 権限設定 Controller
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Videos\Controller
 */
class VideoBlockRolePermissionsController extends VideosAppController {

/**
 * layout
 *
 * @var array
 */
	public $layout = 'NetCommons.setting';

/**
 * use model
 *
 * @var array
 */
	public $uses = array(
		'Videos.VideoBlockSetting',
	);

/**
 * use component
 *
 * @var array
 */
	public $components = array(
		'Blocks.BlockTabs' => array(
			'mainTabs' => array(
				'block_index' => array('url' => array('controller' => 'video_blocks')),
				'frame_settings' => array('url' => array('controller' => 'video_frame_settings')),
			),
			'blockTabs' => array(
				'block_settings' => array('url' => array('controller' => 'video_blocks')),
				'role_permissions' => array('url' => array('controller' => 'video_block_role_permissions')),
			),
		),
		'NetCommons.Permission' => array(
			//アクセスの権限
			'allow' => array(
				'edit' => 'block_permission_editable',
			),
		),
	);

/**
 * use helpers
 *
 * @var array
 */
	public $helpers = array(
		'Blocks.BlockRolePermissionForm'
	);

/**
 * 権限設定 編集
 *
 * @return CakeResponse
 */
	public function edit() {
		// 取得
		$videoBlockSetting = $this->VideoBlockSetting->getVideoBlockSetting();

		$permissions = $this->Workflow->getBlockRolePermissions(
			array('content_creatable', 'content_publishable', 'content_comment_creatable', 'content_comment_publishable')
		);
		$this->set('roles', $permissions['Roles']);

		if ($this->request->isPost()) {
			// 更新時間を再セット
			unset($videoBlockSetting['VideoBlockSetting']['modified']);
			$data = Hash::merge(
				$videoBlockSetting,
				$this->data
			);

			$this->VideoBlockSetting->saveBlockRolePermission($data);
			if ($this->handleValidationError($this->VideoBlockSetting->validationErrors)) {
				// 正常時
				if (! $this->request->is('ajax')) {
					$this->redirect('/videos/video_blocks/index/' . $this->viewVars['frameId']);
				}
				return;
			}
		}

		$results = array(
			'blockRolePermissions' => $permissions['BlockRolePermissions'],
			'roles' => $permissions['Roles'],
			'videoBlockSetting' => $videoBlockSetting['VideoBlockSetting'],
		);

		// キーをキャメル変換
		$results = $this->camelizeKeyRecursive($results);

		$this->set($results);
	}
}