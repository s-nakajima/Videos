<?php
/**
 * Schema file
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

/**
 * VideosSchema CakeSchema
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Videos\Config\Schema
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class VideosSchema extends CakeSchema {

/**
 * Database connection
 *
 * @var string
 */
	public $connection = 'master';

/**
 * before
 *
 * @param array $event event
 * @return bool
 */
	public function before($event = array()) {
		return true;
	}

/**
 * after
 *
 * @param array $event event
 * @return bool
 */
	public function after($event = array()) {
	}

/**
 * video_frame_settings table
 *
 * @var array
 */
	public $video_frame_settings = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary', 'comment' => 'ID'),
		'frame_key' => array('type' => 'string', 'null' => false, 'default' => null, 'key' => 'index', 'collate' => 'utf8_general_ci', 'comment' => 'フレームKey', 'charset' => 'utf8'),
		'display_order' => array('type' => 'string', 'null' => true, 'default' => 'new', 'length' => 11, 'collate' => 'utf8_general_ci', 'comment' => '表示順 new:新着順、title:タイトル順、play:再生回数順、like:評価順', 'charset' => 'utf8'),
		'display_number' => array('type' => 'integer', 'null' => false, 'default' => '10', 'unsigned' => false, 'comment' => '表示件数'),
		'created_user' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'comment' => '作成者'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => '作成日時'),
		'modified_user' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'comment' => '更新者'),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => '更新日時'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'frame_key' => array('column' => 'frame_key', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

/**
 * video_settings table
 *
 * @var array
 */
	public $video_settings = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary', 'comment' => 'ID'),
		'block_key' => array('type' => 'string', 'null' => false, 'default' => null, 'key' => 'index', 'collate' => 'utf8_general_ci', 'comment' => 'ブロックKey', 'charset' => 'utf8'),
		'total_size' => array('type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false),
		'created_user' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'comment' => '作成者'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => '作成日時'),
		'modified_user' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'comment' => '更新者'),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => '更新日時'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'fk_video_settings_blocks1_idx' => array('column' => 'block_key', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

/**
 * videos table
 *
 * @var array
 */
	public $videos = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary', 'comment' => 'ID'),
		'key' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'キー', 'charset' => 'utf8'),
		'language_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 6, 'unsigned' => false),
		'is_origin' => array('type' => 'boolean', 'null' => false, 'default' => '1', 'comment' => 'オリジナルかどうか'),
		'is_translation' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'comment' => '翻訳したかどうか'),
		'is_original_copy' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'comment' => 'オリジナルのコピー。言語を新たに追加したときに使用する'),
		'block_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index', 'comment' => 'ブロックID'),
		'category_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'comment' => 'カテゴリーID'),
		'title_icon' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'title' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'タイトル', 'charset' => 'utf8'),
		'video_time' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'comment' => '動画時間'),
		'play_number' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'comment' => '再生回数'),
		'description' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '説明', 'charset' => 'utf8'),
		'status' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 4, 'unsigned' => false, 'comment' => '公開状況  1:公開中、2:公開申請中、3:下書き中、4:差し戻し'),
		'is_active' => array('type' => 'boolean', 'null' => false, 'default' => null),
		'is_latest' => array('type' => 'boolean', 'null' => false, 'default' => null),
		'created_user' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'comment' => '作成者'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => '作成日時'),
		'modified_user' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'comment' => '更新者'),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => '更新日時'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'block_id' => array('column' => array('block_id', 'language_id'), 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

}
