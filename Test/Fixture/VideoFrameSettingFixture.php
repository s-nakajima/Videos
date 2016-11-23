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
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary', 'comment' => 'ID'),
		'frame_key' => array('type' => 'string', 'null' => false, 'default' => null, 'key' => 'index', 'collate' => 'utf8_general_ci', 'comment' => 'フレームKey', 'charset' => 'utf8'),
		'display_order' => array('type' => 'string', 'null' => true, 'default' => 'new', 'length' => 11, 'collate' => 'utf8_general_ci', 'comment' => '表示順 new:新着順、title:タイトル順、play:再生回数順、like:評価順', 'charset' => 'utf8'),
		'display_number' => array('type' => 'integer', 'null' => false, 'default' => '5', 'unsigned' => false, 'comment' => '表示件数'),
		'created_user' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'comment' => '作成者'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => '作成日時'),
		'modified_user' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'comment' => '更新者'),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => '更新日時'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'fk_video_frame_settings_frames1_idx' => array('column' => 'frame_key', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

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

}
