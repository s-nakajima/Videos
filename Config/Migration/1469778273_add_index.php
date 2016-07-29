<?php
/**
 * Migration file
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

/**
 * Videos CakeMigration
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Videos\Config\Migration
 */
class AddIndex extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'add_index';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'drop_field' => array(
				'video_frame_settings' => array('indexes' => array('fk_video_frame_settings_frames1_idx')),
				'videos' => array('indexes' => array('fk_videos_blocks1_idx')),
			),
			'create_field' => array(
				'video_frame_settings' => array(
					'indexes' => array(
						'frame_key' => array('column' => 'frame_key', 'unique' => 0, 'length' => array('191')),
					),
				),
				'videos' => array(
					'indexes' => array(
						'block_id' => array('column' => array('block_id', 'language_id'), 'unique' => 0),
					),
				),
			),
		),
		'down' => array(
			'create_field' => array(
				'video_frame_settings' => array(
					'indexes' => array(
						'fk_video_frame_settings_frames1_idx' => array('column' => 'frame_key', 'unique' => 0),
					),
				),
				'videos' => array(
					'indexes' => array(
						'fk_videos_blocks1_idx' => array('column' => 'block_id', 'unique' => 0),
					),
				),
			),
			'drop_field' => array(
				'video_frame_settings' => array('indexes' => array('frame_key')),
				'videos' => array('indexes' => array('block_id')),
			),
		),
	);

/**
 * Before migration callback
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function before($direction) {
		return true;
	}

/**
 * After migration callback
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function after($direction) {
		return true;
	}
}
