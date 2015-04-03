<?php
/**
 * VideoBlockSettingFixture
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

/**
 * VideoBlockSettingFixture
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Videos\Test\Fixture
 */
class VideoBlockSettingFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
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
			'fk_video_block_settings_blocks1_idx' => array('column' => 'block_key', 'unique' => 0)
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
			'block_key' => 'Lorem ipsum dolor sit amet',
			'use_like' => 1,
			'use_unlike' => 1,
			'use_comment' => 1,
			'agree' => 1,
			'mail_notice' => 1,
			'auto_video_convert' => 1,
			'video_player' => 1,
			'auto_play' => 1,
			'buffer_time' => 1,
			'comment_agree' => 1,
			'comment_agree_mail_notice' => 1,
			'created_user' => 1,
			'created' => '2015-04-01 17:08:40',
			'modified_user' => 1,
			'modified' => '2015-04-01 17:08:40'
		),
	);

}
