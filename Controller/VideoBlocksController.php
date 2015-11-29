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
class VideoBlocksController extends VideosAppController {

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
		'Videos.VideoBlockSetting',
		'Blocks.Block',
		//'Frames.Frame',
	);

/**
 * use component
 *
 * @var array
 */
	public $components = array(
		'Blocks.BlockTabs' => array(
			'mainTabs' => array('block_index', 'frame_settings'),
			'blockTabs' => array('block_settings', 'role_permissions'),
		),
		'NetCommons.Permission' => array(
			//アクセスの権限
			'allow' => array(
				'index,add,edit,delete' => 'block_editable',
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
		'Blocks.BlockForm',
		'Likes.Like',
	);

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
					'Block.room_id' => Current::read('Room.id'),
				),
				'fields' => array(
					'*',
					'Size.size_byte',
				),
			)
		);

		$videoBlockSetting = $this->Paginator->paginate('VideoBlockSetting');
		if (! $videoBlockSetting) {
			$this->view = 'Blocks.Blocks/not_found';
			return;
		}

		//$results['videoBlockSettings'] = $videoBlockSetting;

		//$results = $this->camelizeKeyRecursive($results);
		//$this->set($results);
		$this->set('videoBlockSettings', $videoBlockSetting);

		$this->request->data['Frame'] = Current::read('Frame');
	}

/**
 * ブロック設定 登録
 *
 * @return CakeResponse
 */
	public function add() {
		$this->view = 'edit';

		if ($this->request->isPost()) {
			//登録処理
			if ($this->VideoBlockSetting->saveVideoBlockSetting($this->data)) {
				$this->redirect(NetCommonsUrl::backToIndexUrl('default_setting_action'));
				return;
			}
			$this->NetCommons->handleValidationError($this->VideoBlockSetting->validationErrors);

		} else {
			//表示処理(初期データセット)
			$this->request->data = $this->VideoBlockSetting->createVideoBlockSetting();
			$this->request->data['Frame'] = Current::read('Frame');
		}


/*
		// 初期値 取得
		$videoBlockSetting = $this->VideoBlockSetting->getVideoBlockSetting();

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
					$this->redirect('/videos/video_blocks/index/' . $this->viewVars['frameId']);
				}
				return;
			}
		}

		$results = array(
			'videoBlockSetting' => $videoBlockSetting['VideoBlockSetting'],
			'block' => $block['Block'],
		);

		// キーをキャメル変換
		//$results = $this->camelizeKeyRecursive($results);

		$this->set($results);
*/
	}

/**
 * ブロック設定 編集
 *
 * @return CakeResponse
 */
	public function edit() {
		if ($this->request->isPut()) {
			//登録処理
			if ($this->VideoBlockSetting->saveVideoBlockSetting($this->data)) {
				$this->redirect(NetCommonsUrl::backToIndexUrl('default_setting_action'));
				return;
			}
			$this->NetCommons->handleValidationError($this->VideoBlockSetting->validationErrors);

		} else {
			//表示処理(初期データセット)
			if (! $videoBlockSetting = $this->VideoBlockSetting->getVideoBlockSetting()) {
				$this->throwBadRequest();
				return false;
			}
			$this->request->data = Hash::merge($this->request->data, $videoBlockSetting);
			$this->request->data['Frame'] = Current::read('Frame');
		}


//		if (! $this->NetCommonsBlock->validateBlockId()) {
//			$this->setAction('throwBadRequest');
//			return false;
//		}
/*		$blockId = (int)$this->params['pass'][1];
		$this->set('blockId', $blockId);

		// ブロック取得
		if (!$block = $this->Block->findById($blockId)) {
			$this->setAction('throwBadRequest');
			return false;
		};

		// 取得
		$videoBlockSetting = $this->VideoBlockSetting->getVideoBlockSetting();

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
					$this->redirect('/videos/video_blocks/index/' . $this->viewVars['frameId']);
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
*/
	}

/**
 * ブロック設定 削除
 *
 * @return CakeResponse
 */
	public function delete() {
		if ($this->request->isDelete()) {
			if ($this->VideoBlockSetting->deleteVideoBlockSetting($this->data)) {
				$this->redirect(NetCommonsUrl::backToIndexUrl('default_setting_action'));
				return;
			}
		}

		$this->throwBadRequest();

/*
//		if (! $this->NetCommonsBlock->validateBlockId()) {
//			$this->throwBadRequest();
//			return false;
//		}
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
				$this->redirect('/videos/video_blocks/index/' . $this->viewVars['frameId']);
			}
			return;
		}
		$this->throwBadRequest();
*/
	}
}
