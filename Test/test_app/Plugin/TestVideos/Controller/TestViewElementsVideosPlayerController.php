<?php
/**
 * View/Elements/Videos/playerテスト用Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AppController', 'Controller');

/**
 * View/Elements/Videos/playerテスト用Controller
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Videos\Test\test_app\Plugin\TestVideos\Controller
 */
class TestViewElementsVideosPlayerController extends AppController {

/**
 * player
 *
 * @return void
 */
	public function player() {
		$this->autoRender = true;
	}

}
