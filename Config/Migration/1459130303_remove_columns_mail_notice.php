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
class RemoveColumnsMailNotice extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'remove_columns_mail_notice';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_field' => array(
				'video_block_settings' => array(
					'use_workflow' => array('type' => 'boolean', 'null' => false, 'default' => '1', 'comment' => 'コンテンツの承認機能 0:使わない 1:使う', 'after' => 'use_comment'),
					'use_comment_approval' => array('type' => 'boolean', 'null' => false, 'default' => '1', 'comment' => 'コメントの承認機能 0:使わない 1:使う', 'after' => 'auto_play'),
				),
			),
			'drop_field' => array(
				'video_block_settings' => array('agree', 'mail_notice', 'comment_agree', 'comment_agree_mail_notice'),
			),
			'alter_field' => array(
				'video_block_settings' => array(
					'use_comment' => array('type' => 'boolean', 'null' => false, 'default' => '1', 'comment' => 'コメント機能 0:使わない 1:使う'),
				),
			),
		),
		'down' => array(
			'drop_field' => array(
				'video_block_settings' => array('use_workflow', 'use_comment_approval'),
			),
			'create_field' => array(
				'video_block_settings' => array(
					'agree' => array('type' => 'boolean', 'null' => false, 'default' => '1', 'comment' => 'auto agree | 動画投稿の自動承認 |  | '),
					'mail_notice' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'comment' => 'mail notice | メール通知 |  | '),
					'comment_agree' => array('type' => 'boolean', 'null' => false, 'default' => '1', 'comment' => 'auto comment agree | コメントの自動承認 |  | '),
					'comment_agree_mail_notice' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'comment' => 'comment agree mail notice | コメント承認メール通知 |  | '),
				),
			),
			'alter_field' => array(
				'video_block_settings' => array(
					'use_comment' => array('type' => 'boolean', 'null' => false, 'default' => '1', 'comment' => 'use comment | コメントを利用 |  | '),
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
