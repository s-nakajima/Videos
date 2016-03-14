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
		'Videos.VideoFrameSetting',
	);

/**
 * use component
 *
 * @var array
 */
	public $components = array(
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
		'Blocks.BlockTabs' => array(
			'mainTabs' => array('block_index', 'frame_settings'),
			'blockTabs' => array('block_settings', 'mail_settings', 'role_permissions'),
		),
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
				'joins' => array (
					array (
						'type' => 'LEFT',
						'table' => '( SELECT' .
									'     blocks.key,' .
									'     SUM(files.size) size_byte' .
									' FROM' .
									'     video_block_settings block_settings,' .
									'     blocks blocks,' .
									'     videos videos,' .
									'     upload_files files' .
									' WHERE' .
									'         block_settings.block_key = blocks.key' .
									'     AND blocks.id = videos.block_id' .
									"     AND blocks.plugin_key = '" . $this->request->params['plugin'] . "'" .
									'     AND videos.is_latest = 1' .
									'     AND videos.key = files.content_key' .
							'     GROUP BY blocks.key )',
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

		if (! $videoBlockSetting = $this->Paginator->paginate('VideoBlockSetting')) {
			$this->view = 'Blocks.Blocks/not_found';
			return;
		}

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

		if ($this->request->is('post')) {
			//BlockSetting, FrameSetting登録処理
			if ($this->VideoBlockSetting->saveVideoBlockSetting($this->data) &&
				$this->VideoFrameSetting->saveVideoFrameSetting($this->data)) {
				$this->redirect(NetCommonsUrl::backToIndexUrl('default_setting_action'));
				return;
			}
			$this->NetCommons->handleValidationError($this->VideoBlockSetting->validationErrors);

		} else {
			//表示処理(初期データセット)
			$this->request->data = $this->VideoBlockSetting->createVideoBlockSetting();
			$this->request->data = Hash::merge($this->request->data, $this->VideoFrameSetting->getVideoFrameSetting(true));
			$this->request->data['Frame'] = Current::read('Frame');
		}
	}

/**
 * ブロック設定 編集
 *
 * @return CakeResponse
 */
	public function edit() {
		if ($this->request->is('put')) {
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
			$this->request->data = Hash::merge($this->request->data, $this->VideoFrameSetting->getVideoFrameSetting(true));
			$this->request->data['Frame'] = Current::read('Frame');
			// チャンネル名をBlockテーブルにセットしているため下記必須
			$this->request->data['Block'] = Current::read('Block');
		}
	}

/**
 * ブロック設定 削除
 *
 * @return CakeResponse
 */
	public function delete() {
		if ($this->request->is('delete')) {
			if ($this->VideoBlockSetting->deleteVideoBlockSetting($this->data)) {
				$this->redirect(NetCommonsUrl::backToIndexUrl('default_setting_action'));
				return;
			}
		}

		$this->throwBadRequest();
	}
}
