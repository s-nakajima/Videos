<?php
/**
 * Videos Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('VideosAppController', 'Videos.Controller');

/**
 * Videos Controller
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Videos\Controller
 */
class VideosController extends VideosAppController {

/**
 * use model
 *
 * @var array
 */
	public $uses = array(
		'Comments.Comment',
	);

/**
 * use components
 *
 * @var array
 */
	public $components = array(
		'NetCommons.NetCommonsBlock',
		'NetCommons.NetCommonsFrame',
		'NetCommons.NetCommonsWorkflow',
		'NetCommons.NetCommonsRoomRole' => array(
			//コンテンツの権限設定
			'allowedActions' => array(
				'contentEditable' => array('edit', 'delete')
			),
		),
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
 * 一覧表示
 *
 * @return void
 */
	public function index() {
	}

/**
 * 詳細表示
 *
 * @return CakeResponse
 */
	public function view() {
	}

/**
 * 登録
 *
 * @return CakeResponse
 */
	public function add() {
		$this->__init();
	}

/**
 * 編集
 *
 * @return CakeResponse
 */
	public function edit() {
		$this->__init();
	}

/**
 * 削除
 *
 * @return CakeResponse
 */
	public function delete() {
	}

/**
 * __init
 *
 * @return array
 */
	private function __init() {
		$comments = $this->Comment->getComments(
			array(
				'plugin_key' => 'videos',
				'content_key' => null,
			)
		);
		$results['comments'] = $comments;

		$results['contentStatus'] = null;
		$this->set($results);
	}
}