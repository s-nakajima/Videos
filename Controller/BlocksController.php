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
class BlocksController extends VideosAppController {

/**
 * use model
 *
 * @var array
 */
	public $uses = array(
		//'Videos.VideoFrameSetting',
		'Videos.VideoBlockSetting',
		'Blocks.Block',
		'Frames.Frame',
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
					'index',
					'edit',
					'delete',
					'video',
				)
			),
		),
		'Paginator',
	);

/**
 * use helpers
 *
 * @var array
 */
	public $helpers = array(
		//'NetCommons.Token',
		//'NetCommons.Date',
	);

/**
 * beforeFilter
 *
 * @return void
 */
	public function beforeFilter() {
		parent::beforeFilter();
		//$this->Auth->allow();
		$this->Auth->deny('index');

		$this->layout = 'NetCommons.setting';
		$results = $this->camelizeKeyRecursive($this->NetCommonsFrame->data);
		$this->set($results);
	}

/**
 * 一覧表示
 *
 * @return CakeResponse
 * @throws Exception
 */
	public function index() {
		$this->Paginator->settings = array(
			'VideoBlockSetting' => array(
				'order' => array('VideoBlockSetting.id' => 'desc'),
				'conditions' => array(
					'Block.key = VideoBlockSetting.block_key',
					'Block.language_id' => $this->viewVars['languageId'],
					'Block.room_id' => $this->viewVars['roomId'],
				),
			)
		);

		try {
			if (! $videoBlockSetting = $this->Paginator->paginate('VideoBlockSetting')) {
				$this->view = 'Blocks/not_found';
				return;
			}
		} catch (Exception $ex) {
			if (isset($this->request['paging']) && $this->params['named']) {
				$this->redirect('/videos/blocks/index/' . $this->viewVars['frameId']);
				return;
			}
			CakeLog::error($ex);
			throw $ex;
		}

		$results['videoBlockSettings'] = $videoBlockSetting;

		$results = $this->camelizeKeyRecursive($results);
		$this->set($results);
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
