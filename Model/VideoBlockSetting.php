<?php
/**
 * VideoBlockSetting Model
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('VideosAppModel', 'Videos.Model');

/**
 * VideoBlockSetting Model
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Videos\Model
 */
class VideoBlockSetting extends VideosAppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array();

/**
 * use behaviors
 *
 * @var array
 */
	public $actsAs = array(
		'Blocks.Block' => array(
			'name' => 'Block.name',
			'loadModels' => array(
				'WorkflowComment' => 'Workflow.WorkflowComment',
			)
		),
		'Blocks.BlockRolePermission',
	);

/**
 * Called during validation operations, before validation. Please note that custom
 * validation rules can be defined in $validate.
 *
 * @param array $options Options passed from Model::save().
 * @return bool True if validate operation should continue, false to abort
 * @link http://book.cakephp.org/2.0/en/models/callback-methods.html#beforevalidate
 * @see Model::save()
 */
	public function beforeValidate($options = array()) {
		$this->validate = Hash::merge($this->validate, array(
			'block_key' => array(
				'notBlank' => array(
					'rule' => array('notBlank'),
					'message' => __d('net_commons', 'Invalid request.'),
				),
			),
			'use_like' => array(
				'boolean' => array(
					'rule' => array('boolean'),
					'message' => __d('net_commons', 'Invalid request.'),
				),
			),
			'use_unlike' => array(
				'boolean' => array(
					'rule' => array('boolean'),
					'message' => __d('net_commons', 'Invalid request.'),
				),
			),
			'use_comment' => array(
				'boolean' => array(
					'rule' => array('boolean'),
					'message' => __d('net_commons', 'Invalid request.'),
				),
			),
			'agree' => array(
				'boolean' => array(
					'rule' => array('boolean'),
					'message' => __d('net_commons', 'Invalid request.'),
				),
			),
			'mail_notice' => array(
				'boolean' => array(
					'rule' => array('boolean'),
					'message' => __d('net_commons', 'Invalid request.'),
				),
			),
			'auto_play' => array(
				'boolean' => array(
					'rule' => array('boolean'),
					'message' => __d('net_commons', 'Invalid request.'),
				),
			),
			'comment_agree' => array(
				'boolean' => array(
					'rule' => array('boolean'),
					'message' => __d('net_commons', 'Invalid request.'),
				),
			),
			'comment_agree_mail_notice' => array(
				'boolean' => array(
					'rule' => array('boolean'),
					'message' => __d('net_commons', 'Invalid request.'),
				),
			),
		));

		return parent::beforeValidate($options);
	}

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Block' => array(
			'className' => 'Blocks.Block',
			'foreignKey' => false,
			'conditions' => array(
				'Block.key = VideoBlockSetting.block_key',
			),
			'fields' => '',
			'order' => ''
		),
	);

/**
 * Create Faq data
 *
 * @return array
 */
	public function createVideoBlockSetting() {
		$this->VideoBlockSetting = ClassRegistry::init('Videos.VideoBlockSetting');


		$videoBlockSetting = $this->createAll(array(
			'Block' => array(
				'name' => __d('videos', 'New channel %s', date('YmdHis')),
			),
		));
		$videoBlockSetting = Hash::merge($videoBlockSetting, $this->VideoBlockSetting->create());

		return $videoBlockSetting;
	}

/**
 * VideoBlockSettingデータ取得
 *
 * @return array
 */
	public function getVideoBlockSetting() {
		$conditions = array(
			$this->alias . '.block_key' => Current::read('Block.key'),
		);

		if (! $videoBlockSetting = $this->find('first', array(
			//'recursive' => -1,
			'recursive' => 0,
			//'joins' => $joins,
			'conditions' => $conditions,
			'order' => $this->alias . '.id DESC'
		))
		) {
			//初期値を設定
			$videoBlockSetting = $this->create();

			// "1","0"をbool型に変換
			$videoBlockSetting = $this->convertBool($videoBlockSetting);
		}

		return $videoBlockSetting;
	}

/**
 * "1","0"をbool型に変換
 *
 * @param array $videoBlockSetting videoBlockSettingデータ
 * @return array videoBlockSettingデータ
 */
	public function convertBool($videoBlockSetting) {
		// 暫定対応(;'∀')
		// $videoBlockSetting = $this->create(); の戻り値、boolean型が"1","0"のまま。
		// $videoBlockSetting = $this->find('first', array()); の戻り値は、boolean型だとtrue,false。
		// anglarJSでcheckboxを制御する場合、boolean型のtrue,false。 cakephpでcheckboxを制御する場合、formhelperのdefaultに設定する値は、"1","0"と違うため、変換が必要

		// bool項目
		$boolKeys = array(
			'use_like',
			'use_unlike',
			'use_comment',
			'agree',
			'mail_notice',
			'auto_play',
			'comment_agree',
			'comment_agree_mail_notice',
		);
		// 値にbool項目があったら、boolean型に変換する
		foreach ($boolKeys as $boolKey) {
			if (array_key_exists($boolKey, $videoBlockSetting['VideoBlockSetting'])) {
				$videoBlockSetting['VideoBlockSetting'][$boolKey] = $videoBlockSetting['VideoBlockSetting'][$boolKey] == '1';
			}
		}

		return $videoBlockSetting;
	}

/**
 * VideoBlockSettingデータ保存
 *
 * @param array $data received post data
 * @return mixed On success Model::$data if its not empty or true, false on failure
 * @throws InternalErrorException
 */
	public function saveVideoBlockSetting($data) {
//		$this->loadModels(array(
//			'VideoBlockSetting' => 'Videos.VideoBlockSetting',
//			//'Block' => 'Blocks.Block',
//		));

		//トランザクションBegin
		$this->begin();

		// 値をセット
		$this->set($data);
		if (! $this->validates()) {
			$this->rollback();
			return false;
		}

		try {
//			// 入力チェック
//			$this->validates();
//			if ($this->validationErrors) {
//				return false;
//			}

//			// ブロック名必須チェック追加
//			$this->Block->validate['name'] = array(
//				'notBlank' => array(
//					'rule' => array('notBlank'),
//					'message' => sprintf(__d('net_commons', 'Please input %s.'), __d('videos', 'channel')),
//					'required' => true,		// required 効かず
//				),
//			);
//
//			// ブロック入力チェック
//			if (!$this->Block->validateBlock($data)) {
//				$this->validationErrors = Hash::merge($this->validationErrors, $this->Block->validationErrors);
//				return false;
//			}

//			//ブロックの保存
//			$block = $this->Block->saveByFrameId($data['Frame']['id'], $data['Block']);

//			// block.keyを含める
//			$data = Hash::merge(
//				$data,
//				array('VideoBlockSetting' => array('block_key' => $block['Block']['key']))
//			);
//
//			// 値をセット
//			$this->set($data);

			if (! $videoBlockSetting = $this->save(null, false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			$this->commit();

//			$videoBlockSetting = Hash::merge(
//				$videoBlockSetting,
//				array('Block' => array('id' => $block['Block']['id']))
//			);

		} catch (Exception $ex) {
			//トランザクションRollback
			$this->rollback($ex);
		}
		return true;
	}

/**
 * VideoBlockSettingデータ削除
 *
 * @param array $data received post data
 * @return mixed On success Model::$data if its not empty or true, false on failure
 * @throws InternalErrorException
 */
	public function deleteVideoBlockSetting($data) {
		$this->loadModels(array(
//			'Block' => 'Blocks.Block',
//			'Comment' => 'Comments.Comment',
			'ContentComment' => 'ContentComments.ContentComment',
			'FileModel' => 'Files.FileModel',		// FileUpload
			'Like' => 'Likes.Like',
			'Tag' => 'Tags.Tag',
			'TagsContent' => 'Tags.TagsContent',
			'VideoBlockSetting' => 'Videos.VideoBlockSetting',
			'Video' => 'Videos.Video',
		));

		//トランザクションBegin
		$this->begin();

		// 多言語コンテンツ削除対応
		// 対象のブロックID一覧を取得
		$conditions = array(
			$this->Block->alias . '.key' => $data['Block']['key']
		);
		$blockIds = $this->Block->find('list', array(
			'recursive' => -1,
			'conditions' => $conditions,
		));
		$blockIds = array_keys($blockIds);

		$conditions = array(
			$this->Tag->alias . '.block_id' => $blockIds
		);
		$tagIds = $this->Tag->find('list', array(
			'recursive' => -1,
			'conditions' => $conditions,
		));
		$tagIds = array_keys($tagIds);

		try {
			// VideoBlockSetting削除
			if (! $this->deleteAll(array($this->alias . '.block_key' => $data['Block']['key']), false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			// 動画削除
			if (! $this->Video->deleteAll(array($this->Video->alias . '.block_id' => $blockIds), false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			// ファイル 削除 暫定として対応しない(;'∀')
			// 本来、データと物理ファイル削除。共通処理が完成したら、実装する

			// 承認コメント削除
//			if (! $this->Comment->deleteAll(array($this->Comment->alias . '.block_key' => $data['Block']['key']), false)) {
//				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
//			}

			// コンテンツコメント 削除
			if (! $this->ContentComment->deleteAll(array($this->ContentComment->alias . '.block_key' => $data['Block']['key']), false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			// タグコンテンツ 削除
			if (! $this->TagsContent->deleteAll(array($this->TagsContent->alias . '.tag_id' => $tagIds), false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			// タグ 削除
			if (! $this->Tag->deleteAll(array($this->Tag->alias . '.block_id' => $blockIds), false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			// いいね 削除
			if (! $this->Like->deleteAll(array($this->Like->alias . '.block_key' => $data['Block']['key']), false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			//Blockデータ削除
			$this->deleteBlock($data['Block']['key']);

			//トランザクションCommit
			$this->commit();

		} catch (Exception $ex) {
			//トランザクションRollback
			$this->rollback($ex);
		}

		return true;
	}

/**
 * blockRolePermissionデータ保存
 *
 * @param array $data received post data
 * @return mixed On success Model::$data if its not empty or true, false on failure
 * @throws InternalErrorException
 */
	public function saveBlockRolePermission($data) {
		//トランザクションBegin
		$this->begin();

		$this->set($data);
		if (! $this->validates()) {
			$this->rollback();
			return false;
		}

		try {

//			// 入力チェック blockRolePermission
//			foreach ($data[$this->BlockRolePermission->alias] as $value) {
//				if (! $this->BlockRolePermission->validateBlockRolePermissions($value)) {
//					$this->validationErrors = Hash::merge($this->validationErrors, $this->BlockRolePermission->validationErrors);
//					return false;
//				}
//			}
//
//			// 保存 VideoBlockSetting
//			$videoBlockSetting = $this->save(null, false);
//			if (!$videoBlockSetting) {
//				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
//			}
//			// 保存 blockRolePermission
//			foreach ($data[$this->BlockRolePermission->alias] as $value) {
//				if (! $this->BlockRolePermission->saveMany($value, ['validate' => false])) {
//					throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
//				}
//			}
			if (! $this->save(null, false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			//トランザクションCommit
			$this->commit();

		} catch (Exception $ex) {
			//トランザクションRollback
			$this->rollback($ex);
		}

		return true;
	}
}
