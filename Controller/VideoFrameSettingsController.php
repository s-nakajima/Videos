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
		'Videos.VideoFrameSetting',
	);

/**
 * use component
 *
 * @var array
 */
	public $components = array(
		//'NetCommons.NetCommonsBlock',
		'Blocks.BlockTabs' => array(
			'mainTabs' => array('block_index', 'frame_settings'),
			'blockTabs' => array('block_settings', 'role_permissions'),
		),
		'NetCommons.Permission' => array(
			//アクセスの権限
			'allow' => array(
				'edit' => 'page_editable',
			),
		),
	);

/**
 * use helpers
 *
 * @var array
 */
	public $helpers = array(
		'NetCommons.DisplayNumber',
	);

/**
 * 表示方法変更
 *
 * @return CakeResponse
 */
	public function edit() {
		if ($this->request->isPut() || $this->request->isPost()) {
			if ($this->VideoFrameSetting->saveVideoFrameSetting($this->data)) {
				$this->redirect(NetCommonsUrl::backToPageUrl());
				return;
			}
			$this->NetCommons->handleValidationError($this->VideoFrameSetting->validationErrors);

		} else {
			$this->request->data = $this->VideoFrameSetting->getVideoFrameSetting(true);
			$this->request->data['Frame'] = Current::read('Frame');
		}

			// 取得
//		$videoFrameSetting = $this->VideoFrameSetting->getVideoFrameSetting(
//			$this->viewVars['frameKey'],
//			$this->viewVars['roomId']
//		);
//		$videoFrameSetting = $this->VideoFrameSetting->getVideoFrameSetting(true);
//
//		if ($this->request->isPost()) {
//			// 更新時間を再セット
//			unset($videoFrameSetting['VideoFrameSetting']['modified']);
//			$data = Hash::merge(
//				$videoFrameSetting,
//				$this->data,
//				array('VideoFrameSetting' => array('frame_key' => $this->viewVars['frameKey'])),
//				array('Frame' => array('id' => $this->viewVars['frameId']))
//			);
//
//			// 保存
//			if (!$videoFrameSetting = $this->VideoFrameSetting->saveVideoFrameSetting($data)) {
//				// エラー処理
//				if (!$this->handleValidationError($this->VideoFrameSetting->validationErrors)) {
//					$this->log($this->validationErrors, 'debug');
//					return;
//				}
//
//				// 正常処理
//			} else {
//				// ajax以外は、リダイレクト
//				if (!$this->request->is('ajax')) {
//					// 一覧へ戻る
//					$url = isset($this->viewVars['current']['page']) ? '/' . $this->viewVars['current']['page']['permalink'] : null;
//					$this->redirect($url);
//				}
//				return;
//			}
//		}
//
//		$results = array('videoFrameSetting' => $videoFrameSetting['VideoFrameSetting']);
//
//		// キーをキャメル変換
//		$results = $this->camelizeKeyRecursive($results);
//
//		$this->set($results);
	}
}