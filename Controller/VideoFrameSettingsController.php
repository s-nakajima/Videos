<?php
/**
 * VideoFrameSettings Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('VideosAppController', 'Videos.Controller');

/**
 * VideoFrameSettings Controller
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Videos\Controller
 */
class VideoFrameSettingsController extends VideosAppController {

/**
 * use model
 *
 * @var array
 */
	public $uses = array(
		'Videos.VideoFrameSetting',
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
				'blockEditable' => array('edit')
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
		//$this->Auth->allow();

		$this->layout = 'NetCommons.setting';
		$results = $this->camelizeKeyRecursive($this->NetCommonsFrame->data);
		$this->set($results);

		//タブの設定
		$this->initTabs('frame_settings', '');
	}

/**
 * 表示方法変更
 *
 * @return CakeResponse
 */
	public function edit() {
		// 取得
		$videoFrameSetting = $this->VideoFrameSetting->getVideoFrameSetting(
			$this->viewVars['frameKey'],
			$this->viewVars['roomId']
		);

		if ($this->request->isPost()) {
			// 更新時間を再セット
			unset($videoFrameSetting['VideoFrameSetting']['modified']);
			$data = Hash::merge(
				$videoFrameSetting,
				$this->data,
				array('VideoFrameSetting' => array('frame_key' => $this->viewVars['frameKey'])),
				array('Frame' => array('id' => $this->viewVars['frameId']))
			);

			// 保存
			if (!$videoFrameSetting = $this->VideoFrameSetting->saveVideoFrameSetting($data)) {
				if (!$this->handleValidationError($this->VideoFrameSetting->validationErrors)) {
					$this->log($this->validationErrors, 'debug');
					return;
				}
			}
		}

		$results = array('videoFrameSetting' => $videoFrameSetting['VideoFrameSetting']);

		// キーをキャメル変換
		$results = $this->camelizeKeyRecursive($results);

		$this->set($results);
	}
}