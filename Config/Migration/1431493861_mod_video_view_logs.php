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
class ModVideoViewLogs extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'mod_video_view_logs';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'alter_field' => array(
				'video_view_logs' => array(
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => 'modified datetime | 更新日時 |  | '),
				),
			),
			'drop_field' => array(
				'video_view_logs' => array('indexes' => array('PRIMARY')),
			),
			'create_field' => array(
				'video_view_logs' => array(
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
				),
			),
		),
		'down' => array(
			'alter_field' => array(
				'video_view_logs' => array(
					'modified' => array('type' => 'datetime', 'null' => false, 'default' => null, 'key' => 'primary', 'comment' => 'modified datetime | 更新日時 |  | '),
				),
			),
			'create_field' => array(
				'video_view_logs' => array(
					'indexes' => array(
						'PRIMARY' => array(),
					),
				),
			),
			'drop_field' => array(
				'video_view_logs' => array('indexes' => array('PRIMARY')),
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
