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
 * beforeFilter
 *
 * @return void
 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow();
	}

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
					'index',
					'display',
					'content',
					'authority',
					'video',
					'tag',
				)
			),
		),
	);

/**
 * 一覧表示
 *
 * @return CakeResponse
 */
	public function index() {
	}

/**
 * 表示方法変更
 *
 * @return CakeResponse
 */
	public function display() {
		// 取得
		$videoFrameSetting = $this->VideoFrameSetting->getVideoFrameSetting(
			$this->viewVars['frameKey']);

		if ($this->request->isPost()) {

			// 作成時間,更新時間を再セット
			unset($videoFrameSetting['VideoFrameSetting']['created'], $videoFrameSetting['VideoFrameSetting']['modified']);
			$data = Hash::merge($videoFrameSetting, $this->data);

			// 保存
			if (!$videoFrameSetting = $this->VideoFrameSetting->saveVideoFrameSetting($data)) {
				if (!$this->handleValidationError($this->VideoFrameSetting->validationErrors)) {
					return;
				}
			}
		}

		$results = array(
			'videoFrameSetting' => $videoFrameSetting['VideoFrameSetting'],
		);

		$this->set($results);
	}

/**
 * コンテンツ
 *
 * @return CakeResponse
 */
	public function content() {
	}

/**
 * 権限設定
 *
 * @return CakeResponse
 */
	public function authority() {
	}

/**
 * 動画
 *
 * @return CakeResponse
 */
	public function video() {
	}

/**
 * 動画
 *
 * @return CakeResponse
 */
	public function tag() {
	}
}