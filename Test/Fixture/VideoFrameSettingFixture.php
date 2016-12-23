<?php
/**
 * VideoFrameSettingFixture
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

/**
 * VideoFrameSettingFixture
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Videos\Test\Fixture
 */
class VideoFrameSettingFixture extends CakeTestFixture {

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => '6',
			'frame_key' => 'frame_3',
			'display_order' => 'title',
			'display_number' => 20,
		),
		array(
			'id' => '2',
			'frame_key' => 'frame_1',
			'display_order' => 'new',
			'display_number' => 10,
		),
		array(
			'id' => '4',
			'frame_key' => 'frame_2',
			'display_order' => 'play',
			'display_number' => 10,
		),
		array(
			'id' => '8',
			'frame_key' => 'frame_4',
			'display_order' => 'like',
			'display_number' => 10,
		),
		//メイン(別ルーム(room_id=5))
		array(
			'id' => '1',
			'frame_key' => 'frame_8',
			'display_order' => 'play',
			'display_number' => 10,
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
