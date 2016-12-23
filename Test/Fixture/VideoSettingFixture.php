<?php
/**
 * VideoSettingFixture
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

/**
 * VideoSettingFixture
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Videos\Test\Fixture
 */
class VideoSettingFixture extends CakeTestFixture {

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'block_key' => 'block_1',
			'total_size' => '0',
		),
		array(
			'block_key' => 'block_2',
			'total_size' => '0',
		),
	);

/**
 * Initialize the fixture.
 *
 * @return void
 */
	public function init() {
		require_once App::pluginPath('Videos') . 'Config' . DS . 'Schema' . DS . 'schema.php';
		$this->fields = (new VideosSchema())->tables[Inflector::tableize($this->name)];
		parent::init();
	}

}
