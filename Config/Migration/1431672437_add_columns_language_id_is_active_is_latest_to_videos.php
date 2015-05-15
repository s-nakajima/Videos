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
class AddColumnsLanguageIdIsActiveIsLatestToVideos extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'add_columns_language_id_is_active_is_latest_to_videos';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_field' => array(
				'videos' => array(
					'language_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 6, 'after' => 'key'),
					'is_active' => array('type' => 'boolean', 'null' => false, 'default' => null, 'after' => 'status'),
					'is_latest' => array('type' => 'boolean', 'null' => false, 'default' => null, 'after' => 'is_active'),
				),
			),
		),
		'down' => array(
			'drop_field' => array(
				'videos' => array('language_id', 'is_active', 'is_latest'),
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
