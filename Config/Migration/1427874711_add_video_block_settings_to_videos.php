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
class AddVideoBlockSettingsToVideos extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'add_video_block_settings_to_videos';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_table' => array(
				'video_block_settings' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary', 'comment' => 'ID |  |  | '),
					'block_key' => array('type' => 'string', 'null' => false, 'default' => null, 'key' => 'index', 'collate' => 'utf8_general_ci', 'comment' => 'block key | ブロックKey | blocks.key | ', 'charset' => 'utf8'),
					'use_like' => array('type' => 'boolean', 'null' => false, 'default' => '1', 'comment' => 'use like | 高く評価を利用 |  | '),
					'use_unlike' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'comment' => 'use like | 低く評価も利用 |  | '),
					'use_comment' => array('type' => 'boolean', 'null' => false, 'default' => '1', 'comment' => 'use comment | コメントを利用 |  | '),
					'agree' => array('type' => 'boolean', 'null' => false, 'default' => '1', 'comment' => 'auto agree | 動画投稿の自動承認 |  | '),
					'mail_notice' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'comment' => 'mail notice | メール通知 |  | '),
					'auto_video_convert' => array('type' => 'boolean', 'null' => false, 'default' => '1', 'comment' => 'auto video convert | 自動動画変換 |  | '),
					'video_player' => array('type' => 'integer', 'null' => false, 'default' => '1', 'length' => 4, 'comment' => 'video player | 動画再生プレイヤー 1:jPlayer、2:HTML5 |  | '),
					'auto_play' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'comment' => 'auto play | 自動再生 |  | '),
					'buffer_time' => array('type' => 'integer', 'null' => false, 'default' => '4', 'comment' => 'buffer time | バッファ時間(秒) |  | '),
					'comment_agree' => array('type' => 'boolean', 'null' => false, 'default' => '1', 'comment' => 'auto comment agree | コメントの自動承認 |  | '),
					'comment_agree_mail_notice' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'comment' => 'comment agree mail notice | コメント承認メール通知 |  | '),
					'created_user' => array('type' => 'integer', 'null' => true, 'default' => null, 'comment' => 'created user | 作成者 | users.id | '),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => 'created datetime | 作成日時 |  | '),
					'modified_user' => array('type' => 'integer', 'null' => true, 'default' => null, 'comment' => 'modified user | 更新者 | users.id | '),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => 'modified datetime | 更新日時 |  | '),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'fk_video_block_settings_blocks1_idx' => array('column' => 'block_key', 'unique' => 0),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
			),
			'drop_field' => array(
				'video_frame_settings' => array('display_like', 'display_unlike', 'display_comment', 'videos_number', 'files_size', 'agree', 'mail_notice', 'auto_video_convert', 'video_player', 'auto_play', 'buffer_time', 'comment_agree', 'comment_agree_mail_notice', 'authority'),
				'videos' => array('mp4_file_size', 'ogg_file_size', 'comments_number', 'likes_number', 'unlikes_number'),
			),
		),
		'down' => array(
			'drop_table' => array(
				'video_block_settings',
			),
			'create_field' => array(
				'video_frame_settings' => array(
					'display_like' => array('type' => 'boolean', 'null' => false, 'default' => '1', 'comment' => 'display like | 高く評価を利用 |  | '),
					'display_unlike' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'comment' => 'display like | 低く評価も利用 |  | '),
					'display_comment' => array('type' => 'boolean', 'null' => false, 'default' => '1', 'comment' => 'display comment | コメントを利用 |  | '),
					'videos_number' => array('type' => 'integer', 'null' => false, 'default' => '0', 'comment' => 'video number | 動画数 | |'),
					'files_size' => array('type' => 'integer', 'null' => false, 'default' => '0', 'comment' => 'file size | ファイル容量 | |'),
					'agree' => array('type' => 'boolean', 'null' => false, 'default' => '1', 'comment' => 'auto agree | 動画投稿の自動承認 |  | '),
					'mail_notice' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'comment' => 'mail notice | メール通知 |  | '),
					'auto_video_convert' => array('type' => 'boolean', 'null' => false, 'default' => '1', 'comment' => 'auto video convert | 自動動画変換 |  | '),
					'video_player' => array('type' => 'integer', 'null' => false, 'default' => '1', 'length' => 4, 'comment' => 'video player | 動画再生プレイヤー 1:jPlayer、2:HTML5 |  | '),
					'auto_play' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'comment' => 'auto play | 自動再生 |  | '),
					'buffer_time' => array('type' => 'integer', 'null' => false, 'default' => '4', 'comment' => 'buffer time | バッファ時間(秒) |  | '),
					'comment_agree' => array('type' => 'boolean', 'null' => false, 'default' => '1', 'comment' => 'auto comment agree | コメントの自動承認 |  | '),
					'comment_agree_mail_notice' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'comment' => 'comment agree mail notice | コメント承認メール通知 |  | '),
					'authority' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 4, 'comment' => 'authority | 動画投稿権限 |  | '),
				),
				'videos' => array(
					'mp4_file_size' => array('type' => 'integer', 'null' => true, 'default' => null, 'comment' => 'mp4 file size | MP4ファイル容量 |  | '),
					'ogg_file_size' => array('type' => 'integer', 'null' => true, 'default' => null, 'comment' => 'ogg file size | OGGファイル容量 |  | '),
					'comments_number' => array('type' => 'integer', 'null' => false, 'default' => '0', 'comment' => 'comment number | コメント数 |  | '),
					'likes_number' => array('type' => 'integer', 'null' => false, 'default' => '0', 'comment' => 'like number | 高く評価数 |  | '),
					'unlikes_number' => array('type' => 'integer', 'null' => false, 'default' => '0', 'comment' => 'unlike number | 低く評価数 |  | '),
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
