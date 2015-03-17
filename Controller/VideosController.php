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

App::uses(
	'VideosAppController',
	'Videos.Controller',
	'Videos.Video'
);

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
 * index
 *
 * @return CakeResponse
 */
	public function index() {
		return $this->render('Videos/index');
	}

/**
 * view
 *
 * @return CakeResponse
 */
	public function view() {
		return $this->render('Videos/view');
	}

}