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
class BlockRolePermissionsController extends VideosAppController {

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
		'NetCommons.NetCommonsFrame',
		'NetCommons.NetCommonsRoomRole' => array(
			//コンテンツの権限設定
			'allowedActions' => array(
				'contentPublishable' => array(
					'edit',
				)
			),
		),
	);

/**
 * use helpers
 *
 * @var array
 */
	public $helpers = array(
		//'NetCommons.Token'
	);

/**
 * beforeFilter
 *
 * @return void
 */
	public function beforeFilter() {
		parent::beforeFilter();
		//$this->Auth->allow();

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
		// 取得
		$videoBlockSetting = $this->VideoBlockSetting->getVideoBlockSetting(
			$this->viewVars['blockKey'],
			$this->viewVars['roomId']
		);

		$results = array(
			'videoBlockSetting' => $videoBlockSetting['VideoBlockSetting'],
		);

		// キーをキャメル変換
		$results = $this->camelizeKeyRecursive($results);

		$this->set($results);
	}
}