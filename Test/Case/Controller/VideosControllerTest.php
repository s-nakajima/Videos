<?php
/**
 * VideosController Test Case
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 * @package app.Plugin.Videos.Test.Controller.Case
 */

App::uses('VideosController', 'Videos.Controller');

/**
 * VideosController Test Case
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package app.Plugin.Videos.Test.Controller.Case
 */
class VideosControllerTest extends ControllerTestCase {

/**
 * Fixtures
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @var array
 */
	public $fixtures = array(
		'app.Session',
		'app.SiteSetting',
		'app.SiteSettingValue',
	);

/**
 * setUp
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @return void
 */
	public function setUp() {
		parent::setUp();
	}

/**
 * tearDown method
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @return void
 */
	public function tearDown() {
		parent::tearDown();
	}

/**
 * test index
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @return void
 */
	public function testIndex() {
		$frameId = 1;
		$this->testAction('/videos/videos/index/' . $frameId . '/', array('method' => 'get'));
		$this->assertTextNotContains('ERROR', $this->view);
	}

/**
 * test view
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @return void
 */
	public function testView() {
		$frameId = 1;
		$this->testAction('/videos/videos/view/' . $frameId . '/', array('method' => 'get'));
		$this->assertTextNotContains('ERROR', $this->view);
	}

}
