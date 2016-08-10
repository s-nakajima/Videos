<?php
/**
 * VideoSettingForPaginatorFixture
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('VideoSettingFixture', 'Videos.Test/Fixture');

/**
 * VideoSettingForPaginatorFixture
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Videos\Test\Fixture
 */
class VideoSettingForPaginatorFixture extends VideoSettingFixture {

/**
 * Model name
 *
 * @var string
 */
	public $name = 'VideoSetting';

/**
 * Full Table Name
 *
 * @var string
 */
	public $table = 'video_settings';

/**
 * Initialize the fixture.
 *
 * @return void
 */
	public function init() {
		for ($i = 11; $i <= 20; $i++) {
			$this->records[$i] = array(
				'block_key' => 'block_' . $i,
				'total_size' => '0',
			);
		}
		for ($i = 101; $i <= 200; $i++) {
			$this->records[$i] = array(
				'block_key' => 'block_' . $i,
				'total_size' => '0',
			);
		}

		parent::init();
	}

}
