<?php
/**
 * Video Test Case
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 * @package app.Plugin.Videos.Test.Model.Case
 */

App::uses('Video', 'Videos.Model');

/**
 * Video Test Case
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package app.Plugin.Videos.Model
 */
class VideoTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @var array
 */
	public $fixtures = array(
		'plugin.videos.video',
		'plugin.videos.language',
		'plugin.videos.block',
		'plugin.videos.blocks_language',
		'plugin.videos.videos_block',
		'plugin.videos.part',
		'plugin.videos.languages_part',
		'plugin.videos.video_part_setting'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Video = ClassRegistry::init('Videos.Video');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Video);

		parent::tearDown();
	}

/**
 * testFindById
 *
 * @return void
 */
	public function testFindById() {
		$id = 1;
		$rtn = $this->Video->findById($id);
		$this->assertTrue(is_array($rtn));
	}

}
