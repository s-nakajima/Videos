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
					'add',
					'edit',
					'delete',
					//'video',
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
 * ブロック一覧表示
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
 * コンテンツ登録
 *
 * @return CakeResponse
 */
	public function add() {
		$this->view = 'Blocks/edit';
		$this->set('blockId', null);

		// 初期値 取得
		$videoBlockSetting = $this->VideoBlockSetting->getVideoBlockSetting(
			null,
			$this->viewVars['roomId']
		);

		// ブロック 初期値 取得
		$block = $this->Block->create();

		if ($this->request->isPost()) {

			//frameの取得
			$frame = $this->Frame->findById($this->viewVars['frameId']);
			if (!$frame) {
				$this->throwBadRequest();
				return;
			}

			// 更新時間を再セット
			$data = Hash::merge(
				$videoBlockSetting,
				$block,
				$this->data,
				array('Frame' => array('id' => $this->viewVars['frameId'])),
				array('Block' => array(
					'room_id' => $frame['Frame']['room_id'],
					'language_id' => $frame['Frame']['language_id'],
					'plugin_key' => 'videos',
				))
			);

			// 保存
			if (!$videoBlockSetting = $this->VideoBlockSetting->saveVideoBlockSetting($data)) {
				if (!$this->handleValidationError($this->VideoBlockSetting->validationErrors)) {
					$this->log($this->validationErrors, 'debug');
					return;
				}
			}

			// ajax以外は、リダイレクト
			if (!$this->request->is('ajax')) {
				$this->redirect('/videos/blocks/index/' . $this->viewVars['frameId']);
			}
			return;
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
 * コンテンツ編集
 *
 * @return CakeResponse
 */
	public function edit() {
		if (! $this->validateBlockId()) {
			$this->throwBadRequest();
			return false;
		}
		$blockId = (int)$this->params['pass'][1];
		$this->set('blockId', $blockId);

		// ブロック取得
		$block = $this->Block->findById($blockId);

		// 取得
		$videoBlockSetting = $this->VideoBlockSetting->getVideoBlockSetting(
			$block['Block']['key'],
			$this->viewVars['roomId']
		);

		if ($this->request->isPost()) {

			// 更新時間を再セット
			unset($videoBlockSetting['VideoBlockSetting']['modified']);
			$data = Hash::merge(
				$videoBlockSetting,
				$block,
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

			// ajax以外は、リダイレクト
			if (!$this->request->is('ajax')) {
				$this->redirect('/videos/blocks/index/' . $this->viewVars['frameId']);
			}
			return;
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
		if (! $this->validateBlockId()) {
			$this->throwBadRequest();
			return false;
		}
		$blockId = (int)$this->params['pass'][1];
		//$this->set('blockId', $blockId);

		if ($this->request->isDelete()) {
			// ブロック取得
			$block = $this->Block->findById($blockId);
			$data = Hash::merge(
				$block,
				$this->data,
				array('VideoBlockSetting' => array('block_key' => $block['Block']['key']))
			);

			// 削除
			if (!$this->VideoBlockSetting->deleteVideoBlockSetting($data)) {
				$this->throwBadRequest();
				return;
			}

			// ajax以外は、リダイレクト
			if (!$this->request->is('ajax')) {
				$this->redirect('/videos/blocks/index/' . $this->viewVars['frameId']);
			}
			return;
		}
		$this->throwBadRequest();
	}

/**
 * 動画一覧
 *
 * @return CakeResponse
 */
	public function video() {
	}
}
