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
 * @see CategoryEditComponent
 */
	public $components = array(
		'Categories.CategoryEdit',
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
		'Blocks.BlockIndex',
		'Blocks.BlockTabs' => array(
			'mainTabs' => array('block_index', 'frame_settings'),
			'blockTabs' => array('block_settings', 'mail_settings', 'role_permissions'),
		),
		'Likes.Like',
	);

/**
 * beforeFilter
 *
 * @return void
 */
	public function beforeFilter() {
		parent::beforeFilter();

		//CategoryEditComponent外す
		if (in_array($this->params['action'], ['index', 'delete'], true)) {
			$this->Components->unload('Categories.CategoryEdit');
		}
	}

/**
 * ブロック一覧表示
 *
 * @return CakeResponse
 * @throws Exception
 */
	public function index() {
		/** @see BlockBehavior::getBlockIndexSettings() */
		$options = $this->VideoBlockSetting->getBlockIndexSettings();
		$pluginOptions = array(
			'joins' => array (
				array (
					'type' => 'LEFT',
					'table' => '( SELECT' .
						'     blocks.key,' .
						'     SUM(files.size) size_byte' .
						' FROM' .
						'     ' . $this->VideoBlockSetting->tablePrefix . 'blocks blocks,' .
						'     ' . $this->VideoBlockSetting->tablePrefix . 'videos videos,' .
						'     ' . $this->VideoBlockSetting->tablePrefix . 'upload_files files' .
						' WHERE' .
						"         blocks.plugin_key = '" . $this->request->params['plugin'] . "'" .
						'     AND blocks.language_id = ' . Current::read('Language.id') .
						'     AND blocks.room_id = ' . Current::read('Room.id') .
						'     AND blocks.id = videos.block_id' .
						'     AND videos.is_latest = 1' .
						'     AND videos.key = files.content_key' .
						'     GROUP BY blocks.key )',
					'alias' => 'Size',
					'conditions' => 'VideoBlockSetting.key = Size.key',
				),
			),
			'fields' => array(
				'*',
			),
		);
		$options['joins'] = array_merge($options['joins'], $pluginOptions['joins']);
		$options['fields'] = $pluginOptions['fields'];
		$this->Paginator->settings = array(
			'VideoBlockSetting' => $options
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
				return $this->redirect(NetCommonsUrl::backToIndexUrl('default_setting_action'));
			}
			$this->NetCommons->handleValidationError($this->VideoBlockSetting->validationErrors);

		} else {
			//表示処理(初期データセット)
			$this->request->data = $this->VideoBlockSetting->createVideoBlockSetting();
			$this->request->data = Hash::merge($this->request->data,
											$this->VideoFrameSetting->getVideoFrameSetting(true));
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
				return $this->redirect(NetCommonsUrl::backToIndexUrl('default_setting_action'));
			}
			$this->NetCommons->handleValidationError($this->VideoBlockSetting->validationErrors);

		} else {
			//表示処理(初期データセット)
			if (! $videoBlockSetting = $this->VideoBlockSetting->getVideoBlockSetting()) {
				$this->throwBadRequest();
				return false;
			}
			$this->request->data = Hash::merge($this->request->data, $videoBlockSetting);
			$this->request->data = Hash::merge($this->request->data,
											$this->VideoFrameSetting->getVideoFrameSetting(true));
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
				return $this->redirect(NetCommonsUrl::backToIndexUrl('default_setting_action'));
			}
		}

		$this->throwBadRequest();
	}
}
