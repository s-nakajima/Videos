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
			'mainTabs' => array('block_index', 'frame_settings'),
			'blockTabs' => array('block_settings', 'role_permissions'),
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
		if (! $videoBlockSetting = $this->VideoBlockSetting->getVideoBlockSetting()) {
			$this->throwBadRequest();
			return false;
		}

		$permissions = $this->Workflow->getBlockRolePermissions(
			array('content_creatable', 'content_publishable', 'content_comment_creatable', 'content_comment_publishable')
		);
		$this->set('roles', $permissions['Roles']);

		if ($this->request->isPost()) {
			if ($this->VideoBlockSetting->saveBlockRolePermission($this->request->data)) {
				$this->redirect(NetCommonsUrl::backToIndexUrl('default_setting_action'));
				return;
			}
			$this->NetCommons->handleValidationError($this->VideoBlockSetting->validationErrors);
			$this->request->data['BlockRolePermission'] = Hash::merge(
				$permissions['BlockRolePermissions'],
				$this->request->data['BlockRolePermission']
			);

		} else {
			$this->request->data['VideoBlockSetting'] = $videoBlockSetting['VideoBlockSetting'];
			$this->request->data['Block'] = $videoBlockSetting['Block'];
			$this->request->data['blockRolePermission'] = $permissions['BlockRolePermissions'];
			$this->request->data['Frame'] = Current::read('Frame');
		}

/*
		if ($this->request->isPost()) {
//			// 更新時間を再セット
//			unset($videoBlockSetting['VideoBlockSetting']['modified']);
//			$data = Hash::merge(
//				$videoBlockSetting,
//				$this->data
//			);

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
			'BlockRolePermissions' => $permissions['BlockRolePermissions'],
			//'Roles' => $permissions['Roles'],
			'VideoBlockSetting' => $videoBlockSetting['VideoBlockSetting'],
		);

		// キーをキャメル変換
		//$results = $this->camelizeKeyRecursive($results);

		$this->set($results);
		*/
	}
}