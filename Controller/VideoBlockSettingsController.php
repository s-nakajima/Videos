<?php
/**
 * VideoBlockSettings Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('VideosAppController', 'Videos.Controller');

/**
 * VideoBlockSettings Controller
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Videos\Controller
 */
class VideoBlockSettingsController extends VideosAppController {

/**
 * use model
 *
 * @var array
 */
	public $uses = array(
		//'Videos.VideoFrameSetting',
		'Videos.VideoBlockSetting',
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
					'edit',
					'delete',
					'video',
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
 * コンテンツ
 *
 * @return CakeResponse
 */
	public function edit() {
		// 取得
		$videoBlockSetting = $this->VideoBlockSetting->getVideoBlockSetting(
			$this->viewVars['blockKey'],
			$this->viewVars['roomId']
		);

		// ブロック取得
		$block = $this->Block->findById($this->viewVars['blockId']);

		if ($this->request->isPost()) {

			// --- VideoBlockSetting
			// 更新時間を再セット
			unset($videoBlockSetting['VideoBlockSetting']['modified']);
			$data = Hash::merge(
				$videoBlockSetting,
				$this->data,
				array('Frame' => array('id' => $this->viewVars['frameId']))
			);

			// 保存
			if (!$videoBlockSetting = $this->VideoBlockSetting->saveVideoBlockSetting($data)) {
				if (!$this->handleValidationError($this->VideoBlockSetting->validationErrors)) {
					$this->log($this->validationErrors, 'debug');
					return;
				}
			}

			// 暫定対応。再取得(;'∀')
			// $videoBlockSetting = $this->save(null, false); の戻り値、boolean型が"1","0"のまま
			// $videoBlockSetting = $this->find('first', array()); の戻り値は、boolean型だとtrue,false。
			$videoBlockSetting = $this->VideoBlockSetting->getVideoBlockSetting(
				$this->viewVars['blockKey'],
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
			if (!$videoBlockSetting = $this->Block->saveBlock($data)) {
				if (!$this->handleValidationError($this->Block->validationErrors)) {
					return;
				}
			} */

		}

		if (empty($block)) {
			$block = $this->Block->create();
		}
		$results = array(
			'videoBlockSetting' => $videoBlockSetting['VideoBlockSetting'],
			'block' => $block['Block'],
		);

		// キーをキャメル変換
		$results = $this->camelizeKeyRecursive($results);

		$this->set($results);
	}

/**
 * コンテンツ削除
 *
 * @return CakeResponse
 */
	public function delete() {
		$this->view = 'VideoBlockSettings/index';
	}

/**
 * 動画
 *
 * @return CakeResponse
 */
	public function video() {
	}
}
