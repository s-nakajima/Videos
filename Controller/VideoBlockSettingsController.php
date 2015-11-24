<?php
/**
 * ブロック設定 Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('VideosAppController', 'Videos.Controller');

/**
 * ブロック設定 Controller
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
		//'NetCommons.NetCommonsFrame',
//		'NetCommons.NetCommonsRoomRole' => array(
//			//コンテンツの権限設定
//			'allowedActions' => array(
//				'blockEditable' => array('index', 'add', 'edit', 'delete'),
//			),
//		),
		'Paginator',
	);

/**
 * beforeFilter
 *
 * @return void
 */
	public function beforeFilter() {
		parent::beforeFilter();
		// 権限判定が必要
		$this->Auth->deny('index', 'add', 'edit', 'delete');

		$this->layout = 'NetCommons.setting';
		//$results = $this->camelizeKeyRecursive($this->NetCommonsFrame->data);
		//$this->set($results);

		//タブの設定
		$this->initTabs('block_index', 'block_settings');
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
				// 暫定対応(;'∀') ファイル容量はtableに項目として持つかどうか、6/15以降に決める
				'joins' => array (
					array (
						'type' => 'LEFT',
						//'table' => '(	SELECT count(*) cnt, b.key, sum(f.size) size_byte' .
						'table' => '( SELECT b.key, SUM(f.size) size_byte' .
									' FROM videos v, blocks b, files f' .
									' WHERE v.block_id = b.id' .
									' AND (v.mp4_id = f.id OR v.thumbnail_id = f.id)' .
									" AND b.plugin_key = '" . $this->request->params['plugin'] . "'" .
									' GROUP BY b.key )',
						'alias' => 'Size',
						'conditions' => 'VideoBlockSetting.block_key = Size.key',
					)
				),
				'conditions' => array(
					'Block.key = VideoBlockSetting.block_key',
					'Block.language_id' => $this->viewVars['languageId'],
					'Block.room_id' => $this->viewVars['roomId'],
				),
				'fields' => array(
					'*',
					'Size.size_byte',
				),
			)
		);

		try {
			if (! $videoBlockSetting = $this->Paginator->paginate('VideoBlockSetting')) {
				$this->view = 'not_found';
				return;
			}
		} catch (Exception $ex) {
			if (isset($this->request['paging']) && $this->params['named']) {
				$this->redirect('/videos/video_block_settings/index/' . $this->viewVars['frameId']);
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
 * ブロック設定 登録
 *
 * @return CakeResponse
 */
	public function add() {
		$this->view = 'VideoBlockSettings/edit';

		// 初期値 取得
		$videoBlockSetting = $this->VideoBlockSetting->getVideoBlockSetting(null);

		// ブロック 初期値 取得
		$block = $this->Block->create(array(
			'name' => __d('videos', 'New channel %s', date('YmdHis')),
		));

		if ($this->request->isPost()) {

			//frameの取得
			$frame = $this->Frame->findById($this->viewVars['frameId']);
			if (!$frame) {
				$this->throwBadRequest();
				return;
			}

			$data = Hash::merge(
				$videoBlockSetting,
				$block,
				$this->data,
				array('Frame' => array('id' => $this->viewVars['frameId'])),
				array('Block' => array(
					'room_id' => $frame['Frame']['room_id'],
					'language_id' => $frame['Frame']['language_id'],
					'plugin_key' => $this->request->params['plugin'],
				))
			);

			// 保存
			if (!$videoBlockSetting = $this->VideoBlockSetting->saveVideoBlockSetting($data)) {
				// エラー処理
				if (!$this->handleValidationError($this->VideoBlockSetting->validationErrors)) {
					$videoBlockSetting['VideoBlockSetting'] = $this->data['VideoBlockSetting'];
					// 入力値セット   "1","0"をbool型に変換
					$videoBlockSetting = $this->VideoBlockSetting->convertBool($videoBlockSetting);
				}

				// 正常処理
			} else {
				// ajax以外は、リダイレクト
				if (!$this->request->is('ajax')) {
					$this->redirect('/videos/video_block_settings/index/' . $this->viewVars['frameId']);
				}
				return;
			}
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
 * ブロック設定 編集
 *
 * @return CakeResponse
 */
	public function edit() {
		if (! $this->NetCommonsBlock->validateBlockId()) {
			$this->throwBadRequest();
			return false;
		}
		$blockId = (int)$this->params['pass'][1];
		$this->set('blockId', $blockId);

		// ブロック取得
		if (!$block = $this->Block->findById($blockId)) {
			$this->throwBadRequest();
			return false;
		};

		// 取得
		$videoBlockSetting = $this->VideoBlockSetting->getVideoBlockSetting($block['Block']['key']);

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
			if (!$this->VideoBlockSetting->saveVideoBlockSetting($data)) {
				// エラー処理
				if (!$this->handleValidationError($this->VideoBlockSetting->validationErrors)) {
					$videoBlockSetting['VideoBlockSetting'] = $this->data['VideoBlockSetting'];
					// 入力値セット   "1","0"をbool型に変換
					$videoBlockSetting = $this->VideoBlockSetting->convertBool($videoBlockSetting);
				}

				// 正常処理
			} else {
				// ajax以外は、リダイレクト
				if (!$this->request->is('ajax')) {
					$this->redirect('/videos/video_block_settings/index/' . $this->viewVars['frameId']);
				}
				return;
			}
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
 * ブロック設定 削除
 *
 * @return CakeResponse
 */
	public function delete() {
		if (! $this->NetCommonsBlock->validateBlockId()) {
			$this->throwBadRequest();
			return false;
		}
		$blockId = (int)$this->params['pass'][1];

		if ($this->request->isDelete()) {
			// ブロック取得
			$block = $this->Block->findById($blockId);
			$data = Hash::merge(
				$block,
				$this->data
			);

			// 削除
			if (!$this->VideoBlockSetting->deleteVideoBlockSetting($data)) {
				$this->throwBadRequest();
				return;
			}

			// ajax以外は、リダイレクト
			if (!$this->request->is('ajax')) {
				$this->redirect('/videos/video_block_settings/index/' . $this->viewVars['frameId']);
			}
			return;
		}
		$this->throwBadRequest();
	}
}
