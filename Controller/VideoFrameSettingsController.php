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
		'Blocks.Block',
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
			$this->viewVars['frameKey'],
			$this->viewVars['roomId']
		);

		if ($this->request->isPost()) {

			// 更新時間を再セット
			unset($videoFrameSetting['VideoFrameSetting']['modified']);
			$data = Hash::merge(
				$videoFrameSetting,
				$this->data,
				array('Frame' => array('id' => $this->viewVars['frameId']))
			);

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

		// キーをキャメル変換
		$results = $this->camelizeKeyRecursive($results);

		$this->set($results);
	}

/**
 * コンテンツ
 *
 * @return CakeResponse
 */
	public function content() {
		// 取得
		$videoFrameSetting = $this->VideoFrameSetting->getVideoFrameSetting(
			$this->viewVars['frameKey'],
			$this->viewVars['roomId']
		);

		// ブロック取得
		$block = $this->Block->findById($this->viewVars['blockId']);

		if ($this->request->isPost()) {

			// --- VideoFrameSetting
			// 更新時間を再セット
			unset($videoFrameSetting['VideoFrameSetting']['modified']);
			$data = Hash::merge(
				$videoFrameSetting,
				$this->data,
				array('Frame' => array('id' => $this->viewVars['frameId']))
			);

			// 保存
			if (!$videoFrameSetting = $this->VideoFrameSetting->saveVideoFrameSetting($data)) {
				if (!$this->handleValidationError($this->VideoFrameSetting->validationErrors)) {
					return;
				}
			}
			// $videoFrameSetting = $this->save(null, false); の戻り値、boolean型が"1","0"のまま(*´Д｀)
			// $videoFrameSetting = $this->find('first', array()); の戻り値は、boolean型だとtrue,false。
			// 暫定対応。再取得
			$videoFrameSetting = $this->VideoFrameSetting->getVideoFrameSetting(
				$this->viewVars['frameKey'],
				$this->viewVars['roomId']
			);

			/* アウチ！Blockモデルにsaveメソッドが無いよ(;'∀')
			// --- Block
			// 更新時間を再セット
			unset($block['Block']['modified']);
			$data = Hash::merge(
				$block,
				array('Block' => $this->data['Block'])
			);

			// 保存
			if (!$videoFrameSetting = $this->Block->saveBlock($data)) {
				if (!$this->handleValidationError($this->Block->validationErrors)) {
					return;
				}
			} */

		}

		$results = array(
			'videoFrameSetting' => $videoFrameSetting['VideoFrameSetting'],
			'block' => $block['Block'],
		);

		// 後で対応する(;'∀')
		// キーをキャメル変換
		//$results = $this->camelizeKeyRecursive($results);

		$this->set($results);
	}

/**
 * コンテンツ削除
 *
 * @return CakeResponse
 */
	public function delete() {
		$this->view = 'VideoFrameSettings/index';
	}

/**
 * 権限設定
 *
 * @return CakeResponse
 */
	public function authority() {
		// 取得
		$videoFrameSetting = $this->VideoFrameSetting->getVideoFrameSetting(
			$this->viewVars['frameKey'],
			$this->viewVars['roomId']
		);

		if ($this->request->isPost()) {

			// 動画投稿権限取得
			$authoritys = $this->data['authority'];
			foreach ($authoritys as $key => $value) {
				if ($value === '0') {
					unset($authoritys[$key]);
				}
			}
			if (empty($authoritys)) {
				// Cheif editor ID 暫定対応(;'∀')
				$authority = 4;
			} else {
				$authority = min(array_keys($authoritys));
			}

			// 更新時間を再セット
			unset($videoFrameSetting['VideoFrameSetting']['modified']);
			$data = Hash::merge(
				$videoFrameSetting,
				$this->data,
				array('VideoFrameSetting' => array('authority' => $authority)),
				array('Frame' => array('id' => $this->viewVars['frameId']))
			);

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

		// キーをキャメル変換
		$results = $this->camelizeKeyRecursive($results);

		$this->set($results);
	}

/**
 * 動画
 *
 * @return CakeResponse
 */
	public function video() {
	}

/**
 * タグ
 *
 * @return CakeResponse
 */
	public function tag() {
	}
}