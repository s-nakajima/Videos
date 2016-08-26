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
		'Videos.VideoSetting',
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
		$options = $this->VideoSetting->getBlockIndexSettings();
		$this->Paginator->settings = array(
			'VideoSetting' => $options
		);

		if (! $videoSetting = $this->Paginator->paginate('VideoSetting')) {
			$this->view = 'Blocks.Blocks/not_found';
			return;
		}

		$this->set('videoSettings', $videoSetting);

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
			if ($this->VideoSetting->saveVideoSetting($this->data) &&
				$this->VideoFrameSetting->saveVideoFrameSetting($this->data)) {
				return $this->redirect(NetCommonsUrl::backToIndexUrl('default_setting_action'));
			}
			$this->NetCommons->handleValidationError($this->VideoSetting->validationErrors);

		} else {
			//表示処理(初期データセット)
			$this->request->data = $this->VideoSetting->createVideoSetting();
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
			if ($this->VideoSetting->saveVideoSetting($this->data)) {
				return $this->redirect(NetCommonsUrl::backToIndexUrl('default_setting_action'));
			}
			$this->NetCommons->handleValidationError($this->VideoSetting->validationErrors);

		} else {
			//表示処理(初期データセット)
			if (! $videoSetting = $this->VideoSetting->getVideoSetting()) {
				$this->throwBadRequest();
				return false;
			}
			$this->request->data = Hash::merge($this->request->data, $videoSetting);
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
			if ($this->VideoSetting->deleteVideoSetting($this->data)) {
				return $this->redirect(NetCommonsUrl::backToIndexUrl('default_setting_action'));
			}
		}

		$this->throwBadRequest();
	}
}
