<?php
/**
 * メール設定 Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('VideosAppController', 'Videos.Controller');

/**
 * メール設定 Controller
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Videos\Controller
 */
class VideoMailSettingsController extends VideosAppController {

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
 * @see MailSetting
 */
	public $uses = array(
		'Mails.MailSetting',
	);

/**
 * use component
 *
 * @var array
 * @see BlockTabsComponent
 */
	public $components = array(
		'NetCommons.Permission' => array(
			//アクセスの権限
			'allow' => array(
				// 暫定対応
				//'edit' => 'mail_editable',
				'edit' => 'block_editable',
			),
		),
	);

/**
 * use helpers
 *
 * @var array
 */
	public $helpers = array(
		'Blocks.BlockRolePermissionForm',
		'Blocks.BlockTabs' => array(
			'mainTabs' => array('block_index', 'frame_settings'),
			'blockTabs' => array('block_settings', 'mail_settings', 'role_permissions'),
		),
	);

/**
 * メール設定 登録,編集
 *
 * @return CakeResponse
 */
	public function edit() {
		$permissions = $this->Workflow->getBlockRolePermissions(
			array('mail_content_receivable')
		);
		// valiadteエラー時にも使ってる
		$this->set('roles', $permissions['Roles']);

		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->MailSetting->saveMailSetting($this->request->data)) {
				$this->redirect(NetCommonsUrl::backToIndexUrl('default_setting_action'));
				return;
			}
			$this->NetCommons->handleValidationError($this->MailSetting->validationErrors);
			$this->request->data['BlockRolePermission'] = Hash::merge(
				$permissions['BlockRolePermissions'],
				$this->request->data['BlockRolePermission']
			);

		} else {
			if (! $mailSetting = $this->MailSetting->getMailSettingPlugin()) {
				// データなし = 新規登録扱い
				$mailSetting = $this->MailSetting->createMailSetting();
				$mailSetting['MailSetting']['mail_fixed_phrase_subject'] = __d('mails', 'MailSetting.mail_fixed_phrase_subject');
				$mailSetting['MailSetting']['mail_fixed_phrase_body'] = __d('videos', 'MailSetting.mail_fixed_phrase_body');
			}

			$this->request->data['MailSetting'] = $mailSetting['MailSetting'];
			$this->request->data['BlockRolePermission'] = $permissions['BlockRolePermissions'];
			$this->request->data['Frame'] = Current::read('Frame');
		}
	}
}