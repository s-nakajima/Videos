<?php
/**
 * メール設定データのMigration
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsMigration', 'NetCommons.Config/Migration');

/**
 * メール設定データのMigration
 *
 * @package NetCommons\Mails\Config\Migration
 */
class VideoMailSettingRecords extends NetCommonsMigration {

/**
 * プラグインキー
 *
 * @var string
 */
	const PLUGIN_KEY = 'videos';

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'mail_setting_records';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(),
		'down' => array(),
	);

/**
 * plugin data
 *
 * @var array $migration
 */
	public $records = array(
		'MailSetting' => array(
			//コンテンツ通知 - 設定
			array(
				'plugin_key' => self::PLUGIN_KEY,
				'block_key' => null,
				'is_mail_send' => false,
			),
		),
		'MailSettingFixedPhrase' => array(
			//コンテンツ通知 - 定型文
			// * 英語
			array(
				'language_id' => '1',
				'plugin_key' => self::PLUGIN_KEY,
				'block_key' => null,
				'type_key' => 'contents',
				'mail_fixed_phrase_subject' => '', //デフォルト(__d('mails', 'MailSetting.mail_fixed_phrase_subject'))
				'mail_fixed_phrase_body' => '', //デフォルト(__d('mails', 'MailSetting.mail_fixed_phrase_body'))
			),
			// * 日本語
			array(
				'language_id' => '2',
				'plugin_key' => self::PLUGIN_KEY,
				'block_key' => null,
				'type_key' => 'contents',
				'mail_fixed_phrase_subject' => '',
				'mail_fixed_phrase_body' => '{X-PLUGIN_NAME}に投稿されたのでお知らせします。
ルーム名:{X-ROOM}
チャンネル名:{X-BLOCK_NAME}
動画タイトル:{X-SUBJECT}
投稿者:{X-USER}
投稿日時:{X-TO_DATE}
タグ:{X-TAGS}

{X-BODY}

{X-WORKFLOW_COMMENT}

この投稿内容を確認するには下記のリンクをクリックして下さい。
{X-URL}',
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
		$this->loadModels(array(
			'MailSetting' => 'Mails.MailSetting',
			'MailSettingFixedPhrase' => 'Mails.MailSettingFixedPhrase',
		));
		foreach ($this->records as $model => $records) {
			if ($direction == 'up') {
				if ($model == 'MailSettingFixedPhrase') {
					// mail_setting_id セット
					$data = $this->MailSetting->find('first', array(
						'recursive' => -1,
						'conditions' => array('plugin_key' => self::PLUGIN_KEY),
						'callbacks' => false,
					));
					foreach ($records as &$record) {
						$record['mail_setting_id'] = $data['MailSetting']['id'];
					}
				}
				if (!$this->updateRecords($model, $records)) {
					return false;
				}
			} elseif ($direction == 'down') {
				$conditions = array(
					'plugin_key' => self::PLUGIN_KEY,
					'block_key' => null,
				);
				if (!$this->MailSettingFixedPhrase->deleteAll($conditions, false, false)) {
					return false;
				}
				if (!$this->MailSetting->deleteAll($conditions, false, false)) {
					return false;
				}
			}
		}
		return true;
	}
}
