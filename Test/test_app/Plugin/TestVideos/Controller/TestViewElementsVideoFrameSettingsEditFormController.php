<?php
/**
 * View/Elements/VideoFrameSettings/edit_formテスト用Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AppController', 'Controller');

/**
 * View/Elements/VideoFrameSettings/edit_formテスト用Controller
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Videos\Test\test_app\Plugin\TestVideos\Controller
 */
class TestViewElementsVideoFrameSettingsEditFormController extends AppController {

/**
 * edit_form
 *
 * @return void
 */
	public function edit_form() {
		$this->autoRender = true;
	}

}
