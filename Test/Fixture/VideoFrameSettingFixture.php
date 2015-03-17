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
			'id' => 1,
			'frame_key' => 'Lorem ipsum dolor sit amet',
			'display_like' => 1,
			'display_unlike' => 1,
			'display_comment' => 1,
			'videos_number' => 1,
			'files_size' => 1,
			'agree' => 1,
			'mail_notice' => 1,
			'auto_video_convert' => 1,
			'video_player' => 1,
			'auto_play' => 1,
			'buffer_time' => 1,
			'comment_agree' => 1,
			'comment_agree_mail_notice' => 1,
			'display_order' => 'Lorem ips',
			'display_number' => 1,
			'authority' => 1,
			'created_user' => 1,
			'created' => '2015-03-17 09:41:48',
			'modified_user' => 1,
			'modified' => '2015-03-17 09:41:48'
		),
	);

}
