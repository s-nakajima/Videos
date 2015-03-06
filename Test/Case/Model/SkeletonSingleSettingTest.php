<?php
/**
 * VideosSetting Test Case
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 * @package app.Plugin.Videos.Test.Model.Case
 */

App::uses('VideosSetting', 'Videos.Model');

/**
 * VideosSetting Test Case
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package app.Plugin.Videos.Model
 */
class VideosSettingTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @var array
 */
	public $fixtures = array(
		'plugin.videos.videos_block',
		'plugin.videos.video_setting',
		'plugin.videos.block',
		'plugin.videos.language',
		'plugin.videos.blocks_language'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->VideoSetting = ClassRegistry::init('Videos.VideoSetting');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->VideoSetting);

		parent::tearDown();
	}

/**
 * testFindById
 *
 * @return void
 */
	public function testFindById() {
		$id = 1;
		$rtn = $this->VideoSetting->findById($id);
		$this->assertTrue(is_array($rtn));
	}

}
