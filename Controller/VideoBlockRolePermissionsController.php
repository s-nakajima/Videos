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
		'NetCommons.NetCommonsBlock',
		'NetCommons.NetCommonsRoomRole' => array(
			//コンテンツの権限設定
			'allowedActions' => array(
				'contentPublishable' => array('edit')
			),
		),
	);

/**
 * beforeFilter
 *
 * @return void
 */
	public function beforeFilter() {
		parent::beforeFilter();
		// 権限判定が必要
		$this->Auth->deny('edit');

		$this->layout = 'NetCommons.setting';
		$results = $this->camelizeKeyRecursive($this->NetCommonsFrame->data);
		$this->set($results);

		//タブの設定
		$this->initTabs('block_index', 'role_permissions');
	}

/**
 * 権限設定 編集
 *
 * @return CakeResponse
 */
	public function edit() {
		if (! $this->NetCommonsBlock->validateBlockId()) {
			$this->throwBadRequest();
			return false;
		}
		$blockId = (int)$this->params['pass'][1];
		$this->set('blockId', $blockId);

		// 取得
		$videoBlockSetting = $this->VideoBlockSetting->getVideoBlockSetting(
			$this->viewVars['blockKey'],
			$this->viewVars['roomId']
		);

		$permissions = $this->NetCommonsBlock->getBlockRolePermissions(
			$this->viewVars['blockKey'],
			array('content_creatable', 'content_comment_creatable', 'content_comment_publishable')
		);

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
					$this->redirect('/videos/video_block_settings/index/' . $this->viewVars['frameId']);
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