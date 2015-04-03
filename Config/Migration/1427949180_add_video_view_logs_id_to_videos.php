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
class AddVideoViewLogsIdToVideos extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'add_video_view_logs_id_to_videos';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_field' => array(
				'video_view_logs' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary', 'comment' => 'ID |  |  | '),
					'indexes' => array(
						'PRIMARY' => array('column' => array('id', 'modified'), 'unique' => 1),
					),
				),
			),
			'alter_field' => array(
				'video_view_logs' => array(
					'modified' => array('type' => 'datetime', 'null' => false, 'default' => null, 'key' => 'primary', 'comment' => 'modified datetime | 更新日時 |  | '),
				),
			),
		),
		'down' => array(
			'drop_table' => array(
				'access_counter_frame_settings', 'access_counters', 'announcements', 'bbs_frame_settings', 'bbs_posts', 'bbs_posts_users', 'bbses', 'block_role_permissions', 'blocks', 'boxes', 'boxes_pages', 'comments', 'containers', 'containers_pages', 'default_role_permissions', 'edumap', 'edumap_social_media', 'edumap_students', 'edumap_visibility_settings', 'faq_orders', 'faqs', 'files', 'files_plugins', 'files_rooms', 'files_users', 'frames', 'groups', 'groups_languages', 'groups_users', 'hello_worlds', 'iframe_frame_settings', 'iframes', 'languages', 'languages_pages', 'notepad_part_settings', 'notepad_settings', 'notepads', 'notepads_blocks', 'online_frame_settings', 'pages', 'plugins', 'plugins_roles', 'plugins_rooms', 'roles', 'roles_rooms', 'roles_rooms_users', 'roles_user_attributes', 'room_role_permissions', 'room_roles', 'rooms', 'rss_reader_frame_settings', 'rss_reader_items', 'rss_readers', 'site_settings', 'spaces', 'user_attributes', 'user_attributes_users', 'user_select_attributes', 'user_select_attributes_users', 'users'
			),
			'drop_field' => array(
				'video_view_logs' => array('id', 'indexes' => array('PRIMARY')),
			),
			'alter_field' => array(
				'video_view_logs' => array(
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => 'modified datetime | 更新日時 |  | '),
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
