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
			'blockTabs' => array(
				'block_settings',
				'role_permissions',
				'mail_settings' => array(
					'url' => array(
						'plugin' => 'videos',
						'controller' => 'video_mail_settings',
						'action' => 'edit',
					),
					// 暫定対応
					//'label' => __d('mails', 'メール設定'),
					'label' => 'メール設定',
				),
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
		$permissions = $this->Workflow->getBlockRolePermissions(
			array('content_creatable', 'content_publishable', 'content_comment_creatable', 'content_comment_publishable')
		);
		$this->set('roles', $permissions['Roles']);

		if ($this->request->is('post')) {
			if ($this->VideoBlockSetting->saveVideoBlockSetting($this->request->data)) {
				$this->redirect(NetCommonsUrl::backToIndexUrl('default_setting_action'));
				return;
			}
			$this->NetCommons->handleValidationError($this->VideoBlockSetting->validationErrors);
			$this->request->data['BlockRolePermission'] = Hash::merge(
				$permissions['BlockRolePermissions'],
				$this->request->data['BlockRolePermission']
			);

		} else {
			if (! $videoBlockSetting = $this->VideoBlockSetting->getVideoBlockSetting()) {
				$this->throwBadRequest();
				return false;
			}
			$this->request->data['VideoBlockSetting'] = $videoBlockSetting['VideoBlockSetting'];
			$this->request->data['Block'] = $videoBlockSetting['Block'];
			$this->request->data['BlockRolePermission'] = $permissions['BlockRolePermissions'];
			$this->request->data['Frame'] = Current::read('Frame');
		}
	}
}