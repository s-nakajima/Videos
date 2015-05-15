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
class Videos extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'videos';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_table' => array(
				'video_frame_settings' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary', 'comment' => 'ID |  |  | '),
					'frame_key' => array('type' => 'string', 'null' => false, 'default' => null, 'key' => 'index', 'collate' => 'utf8_general_ci', 'comment' => 'frame key | フレームKey | frames.key | ', 'charset' => 'utf8'),
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
					'display_order' => array('type' => 'string', 'null' => true, 'default' => 'new', 'length' => 11, 'collate' => 'utf8_general_ci', 'comment' => 'display order | 表示順 new:新着順、title:タイトル順、play:再生回数順、like:評価順 |  | ', 'charset' => 'utf8'),
					'display_number' => array('type' => 'integer', 'null' => false, 'default' => '5', 'comment' => 'display number | 表示件数 |  | '),
					'authority' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 4, 'comment' => 'authority | 動画投稿権限 |  | '),
					'created_user' => array('type' => 'integer', 'null' => true, 'default' => null, 'comment' => 'created user | 作成者 | users.id | '),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => 'created datetime | 作成日時 |  | '),
					'modified_user' => array('type' => 'integer', 'null' => true, 'default' => null, 'comment' => 'modified user | 更新者 | users.id | '),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => 'modified datetime | 更新日時 |  | '),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'fk_video_frame_settings_frames1_idx' => array('column' => 'frame_key', 'unique' => 0),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'video_view_logs' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary', 'comment' => 'ID |  |  | '),
					'video_key' => array('type' => 'string', 'null' => false, 'default' => null, 'key' => 'index', 'collate' => 'utf8_general_ci', 'comment' => 'video key | 動画key | videos.key | ', 'charset' => 'utf8'),
					'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'comment' => 'user id | ユーザID | users.id | '),
					'created_user' => array('type' => 'integer', 'null' => true, 'default' => null, 'comment' => 'created user | 作成者 | users.id | '),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => 'created datetime | 作成日時 |  | '),
					'modified_user' => array('type' => 'integer', 'null' => true, 'default' => null, 'comment' => 'modified user | 更新者 | users.id | '),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => 'modified datetime | 更新日時 |  | '),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'fk_video_view_logs_frames1_idx' => array('column' => 'video_key', 'unique' => 0),
						'fk_video_view_logs1_idx' => array('column' => array('video_key', 'user_id'), 'unique' => 0),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'videos' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary', 'comment' => 'ID |  |  | '),
					'key' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'KEY |  |  | ', 'charset' => 'utf8'),
					'block_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index', 'comment' => 'block id |  ブロックID | blocks.id | '),
					'title' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'title | タイトル |  | ', 'charset' => 'utf8'),
					'mp4_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'comment' => 'mp4 id | MP4ファイルID |  | '),
					'mp4_file_size' => array('type' => 'integer', 'null' => true, 'default' => null, 'comment' => 'mp4 file size | MP4ファイル容量 |  | '),
					'ogg_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'comment' => 'ogg id | OGGファイルID |  | '),
					'ogg_file_size' => array('type' => 'integer', 'null' => true, 'default' => null, 'comment' => 'ogg file size | OGGファイル容量 |  | '),
					'thumbnail_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'comment' => 'thumbnail id | サムネイルファイルID |  | '),
					'video_time' => array('type' => 'integer', 'null' => false, 'default' => '0', 'comment' => 'video time | 動画時間 |  | '),
					'play_number' => array('type' => 'integer', 'null' => false, 'default' => '0', 'comment' => 'play number | 再生回数 |  | '),
					'comments_number' => array('type' => 'integer', 'null' => false, 'default' => '0', 'comment' => 'comment number | コメント数 |  | '),
					'likes_number' => array('type' => 'integer', 'null' => false, 'default' => '0', 'comment' => 'like number | 高く評価数 |  | '),
					'unlikes_number' => array('type' => 'integer', 'null' => false, 'default' => '0', 'comment' => 'unlike number | 低く評価数 |  | '),
					'description' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'description | 説明 |  | ', 'charset' => 'utf8'),
					'is_auto_translated' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'comment' => 'translation type. 0:original , 1:auto translation | 翻訳タイプ  0:オリジナル、1:自動翻訳 |  | '),
					'translation_engine' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'translation engine | 翻訳エンジン |  | ', 'charset' => 'utf8'),
					'status' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 4, 'comment' => 'public status, 1: public, 2: public pending, 3: draft during 4: remand | 公開状況  1:公開中、2:公開申請中、3:下書き中、4:差し戻し |  | '),
					'created_user' => array('type' => 'integer', 'null' => true, 'default' => null, 'comment' => 'created user | 作成者 | users.id | '),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => 'created datetime | 作成日時 |  | '),
					'modified_user' => array('type' => 'integer', 'null' => true, 'default' => null, 'comment' => 'modified user | 更新者 | users.id | '),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => 'modified datetime | 更新日時 |  | '),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'fk_videos_blocks1_idx' => array('column' => 'block_id', 'unique' => 0),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
			),
		),
		'down' => array(
			'drop_table' => array(
				'video_frame_settings',
				'video_view_logs',
				'videos',
			),
		),
	);

/**
 * recodes
 *
 * @var array $migration
 */
	public $records = array(
		'Plugin' => array(
			array(
				'language_id' => 2,
				'key' => 'videos',
				'namespace' => 'netcommons/videos',
				'name' => '動画',
				'type' => 1,
			),
		),

		'PluginsRole' => array(
			array(
				'role_key' => 'room_administrator',
				'plugin_key' => 'videos'
			),
		),

		'PluginsRoom' => array(
			array(
				'room_id' => '1',
				'plugin_key' => 'videos'
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
		if ($direction === 'down') {
			return true;
		}

		foreach ($this->records as $model => $records) {
			if (!$this->updateRecords($model, $records)) {
				return false;
			}
		}

		return true;
	}

/**
 * Update model records
 *
 * @param string $model model name to update
 * @param string $records records to be stored
 * @param string $scope ?
 * @return bool Should process continue
 */
	public function updateRecords($model, $records, $scope = null) {
		$Model = $this->generateModel($model);
		foreach ($records as $record) {
			$Model->create();
			if (!$Model->save($record, false)) {
				return false;
			}
		}

		return true;
	}
}
