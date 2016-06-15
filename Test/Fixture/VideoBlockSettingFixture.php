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
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary', 'comment' => 'ID |  |  | '),
		'block_key' => array('type' => 'string', 'null' => false, 'default' => null, 'key' => 'index', 'collate' => 'utf8mb4_general_ci', 'comment' => 'block key | ブロックKey | blocks.key | ', 'charset' => 'utf8'),
		'use_like' => array('type' => 'boolean', 'null' => false, 'default' => '1', 'comment' => 'use like | 高く評価を利用 |  | '),
		'use_unlike' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'comment' => 'use like | 低く評価も利用 |  | '),
		'use_comment' => array('type' => 'boolean', 'null' => false, 'default' => '1', 'comment' => 'コメント機能 0:使わない 1:使う'),
		'use_workflow' => array('type' => 'boolean', 'null' => false, 'default' => '1', 'comment' => 'コンテンツの承認機能 0:使わない 1:使う'),
		'auto_play' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'comment' => 'auto play | 自動再生 |  | '),
		'use_comment_approval' => array('type' => 'boolean', 'null' => false, 'default' => '1', 'comment' => 'コメントの承認機能 0:使わない 1:使う'),
		'created_user' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'comment' => 'created user | 作成者 | users.id | '),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => 'created datetime | 作成日時 |  | '),
		'modified_user' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'comment' => 'modified user | 更新者 | users.id | '),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => 'modified datetime | 更新日時 |  | '),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'fk_video_block_settings_blocks1_idx' => array('column' => 'block_key', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8mb4_general_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => '1',
			'block_key' => 'block_1',
			'use_like' => 1,
			'use_unlike' => 1,
			'use_comment' => 1,
			'use_workflow' => 1,
			'auto_play' => 1,
			'use_comment_approval' => 1,
		),
		array(
			'id' => '2',
			'block_key' => 'block_2',
			'use_like' => 1,
			'use_unlike' => 1,
			'use_comment' => 1,
			'use_workflow' => 1,
			'auto_play' => 1,
			'use_comment_approval' => 1,
		),
	);

/**
 * Initialize the fixture.
 *
 * @return void
 */
	public function init() {
		//		for ($i = 11; $i <= 20; $i++) {
		//			$this->records[$i] = array(
		//				'id' => $i,
		//				'language_id' => '2',
		//				'room_id' => '1',
		//				'plugin_key' => 'test_plugin',
		//				'key' => 'block_' . $i,
		//				'name' => 'Block name ' . $i,
		//				'public_type' => '1',
		//			);
		//		}
		for ($i = 101; $i <= 200; $i++) {
			$this->records[$i] = array(
				'id' => $i,
				'block_key' => 'block_' . $i,
				'use_like' => 1,
				'use_unlike' => 1,
				'use_comment' => 1,
				'use_workflow' => 1,
				'auto_play' => 1,
				'use_comment_approval' => 1,
			);
		}

		parent::init();
	}
}
