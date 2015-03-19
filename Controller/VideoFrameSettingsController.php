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
	//public $uses = array();

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
				'contentPublishable' => array('edit')
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
}