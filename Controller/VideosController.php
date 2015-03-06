<?php
/**
 * Videos Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 * @package app.Plugin.Videos.Controller
 */

App::uses('VideosAppController', 'Videos.Controller');

/**
 * Videos Controller
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package app.Plugin.Videos.Controller
 */
class VideosController extends VideosAppController {

/**
 * use model
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @var array
 */
	//public $uses = array();

/**
 * beforeFilter
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @return void
 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow();
	}

/**
 * index
 *
 * @param int $frameId frames.id
 * @param string $lang language
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @return CakeResponse
 */
	public function index($frameId = 0, $lang = '') {
		//フレーム初期化処理
		if (! $this->_initializeFrame($frameId, $lang)) {
			return $this->render(false);
		}

		return $this->render('Videos/index');
	}

/**
 * view
 *
 * @param int $frameId frames.id
 * @param string $lang language
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @return CakeResponse
 */
	public function view($frameId = 0, $lang = '') {
		return $this->render('Videos/view');
	}

}
