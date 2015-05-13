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
class DeleteColumsOggIdVideoPlayerBufferTimeToVideos extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'delete_colums_ogg_id_video_player_buffer_time_to_videos';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'drop_field' => array(
				'video_block_settings' => array('video_player', 'buffer_time'),
				'videos' => array('ogg_id'),
			),
		),
		'down' => array(
			'create_field' => array(
				'video_block_settings' => array(
					'video_player' => array('type' => 'integer', 'null' => false, 'default' => '1', 'length' => 4, 'comment' => 'video player | 動画再生プレイヤー 1:jPlayer、2:HTML5 |  | '),
					'buffer_time' => array('type' => 'integer', 'null' => false, 'default' => '4', 'comment' => 'buffer time | バッファ時間(秒) |  | '),
				),
				'videos' => array(
					'ogg_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'comment' => 'ogg id | OGGファイルID |  | '),
				),
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
