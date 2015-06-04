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
	public $validate = array(
		'block_key' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'use_like' => array(
			'boolean' => array(
				'rule' => array('boolean'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'use_unlike' => array(
			'boolean' => array(
				'rule' => array('boolean'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'use_comment' => array(
			'boolean' => array(
				'rule' => array('boolean'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'agree' => array(
			'boolean' => array(
				'rule' => array('boolean'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'mail_notice' => array(
			'boolean' => array(
				'rule' => array('boolean'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'auto_play' => array(
			'boolean' => array(
				'rule' => array('boolean'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'comment_agree' => array(
			'boolean' => array(
				'rule' => array('boolean'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'comment_agree_mail_notice' => array(
			'boolean' => array(
				'rule' => array('boolean'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

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
 * VideoBlockSettingデータ取得
 *
 * @param string $blockKey blocks.key
 * @param int $roomId rooms.id
 * @return array
 */
	public function getVideoBlockSetting($blockKey, $roomId) {
		$conditions = array(
			$this->alias . '.block_key' => $blockKey,
		);

		$joins = array(
			array(
				'type' => 'inner',
				'table' => 'blocks',
				'alias' => 'Block',
				'conditions' => array(
					$this->alias . '.block_key = Block.key',
					'Block.room_id = ' . $roomId,
				),
			),
		);

		if (!$videoBlockSetting = $this->find('first', array(
			'recursive' => -1,
			'joins' => $joins,
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
		// anglarjsでcheckboxを制御する場合、boolean型のtrue,false。 cakephpでcheckboxを制御する場合、formhelperのdefaultに設定する値は、"1","0"と違うため、変換が必要

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
		$this->loadModels(array(
			'VideoBlockSetting' => 'Videos.VideoBlockSetting',
			'Block' => 'Blocks.Block',
		));

		//トランザクションBegin
		$dataSource = $this->getDataSource();
		$dataSource->begin();

		try {
			// 値をセット
			$this->set($data);

			// 入力チェック
			$this->validates();
			if ($this->validationErrors) {
				return false;
			}

			// 暫定対応(;'∀')
			// ブロック名必須チェック追加
			$this->Block->validate['name'] = array(
				'notEmpty' => array(
					'rule' => array('notEmpty'),
					'message' => sprintf(__d('net_commons', 'Please input %s.'), __d('videos', 'channel')),
					'required' => true,
				),
			);

			// ブロック入力チェック
			if (!$this->Block->validateBlock($data)) {
				return false;
			}

			//ブロックの保存
			$block = $this->Block->saveByFrameId($data['Frame']['id'], $data['Block']);

			// block.keyを含める
			$data = Hash::merge(
				$data,
				array('VideoBlockSetting' => array('block_key' => $block['Block']['key']))
			);

			// 値をセット
			$this->set($data);

			$videoBlockSetting = $this->save(null, false);
			if (!$videoBlockSetting) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			$dataSource->commit();

			$videoBlockSetting = Hash::merge(
				$videoBlockSetting,
				array('Block' => array('id' => $block['Block']['id']))
			);

		} catch (InternalErrorException $ex) {
			$dataSource->rollback();
			CakeLog::write(LOG_ERR, $ex);
			throw $ex;
		}
		return $videoBlockSetting;
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
			'Block' => 'Blocks.Block',
			'Comment' => 'Comments.Comment',
			'ContentComment' => 'ContentComments.ContentComment',
			'FileModel' => 'Files.FileModel',		// FileUpload
			'Like' => 'Likes.Like',
			'Tag' => 'Tags.Tag',
			'TagsContent' => 'Tags.TagsContent',
			'VideoBlockSetting' => 'Videos.VideoBlockSetting',
			'Video' => 'Videos.Video',
		));

		//トランザクションBegin
		$dataSource = $this->getDataSource();
		$dataSource->begin();

		try {
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

			// VideoBlockSetting削除
			if (! $this->deleteAll(array($this->alias . '.block_key' => $data['Block']['key']), false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			//Blockデータ削除
			$this->Block->deleteBlock($data['Block']['key']);

			// 動画削除
			if (! $this->Video->deleteAll(array($this->Video->alias . '.block_id' => $blockIds), false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			// ファイル 削除 暫定として対応しない(;'∀')
			// 本来、データと物理ファイル削除。共通処理が完成したら、実装する

			// 承認コメント削除
			if (! $this->Comment->deleteAll(array($this->Comment->alias . '.block_key' => $data['Block']['key']), false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

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

			$dataSource->commit();

		} catch (InternalErrorException $ex) {
			$dataSource->rollback();
			CakeLog::write(LOG_ERR, $ex);
			throw $ex;
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
		$this->loadModels(array(
			'BlockRolePermission' => 'Blocks.BlockRolePermission',
			'VideoBlockSetting' => 'Videos.VideoBlockSetting',
		));

		//トランザクションBegin
		$dataSource = $this->getDataSource();
		$dataSource->begin();

		try {
			// 値をセット
			$this->set($data);

			// 入力チェック VideoBlockSetting
			$this->validates();
			if ($this->validationErrors) {
				return false;
			}
			// 入力チェック blockRolePermission
			foreach ($data[$this->BlockRolePermission->alias] as $value) {
				if (! $this->BlockRolePermission->validateBlockRolePermissions($value)) {
					$this->validationErrors = Hash::merge($this->validationErrors, $this->BlockRolePermission->validationErrors);
					return false;
				}
			}

			// 保存 VideoBlockSetting
			$videoBlockSetting = $this->save(null, false);
			if (!$videoBlockSetting) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}
			// 保存 blockRolePermission
			foreach ($data[$this->BlockRolePermission->alias] as $value) {
				if (! $this->BlockRolePermission->saveMany($value, ['validate' => false])) {
					throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
				}
			}

			$dataSource->commit();

		} catch (InternalErrorException $ex) {
			$dataSource->rollback();
			CakeLog::write(LOG_ERR, $ex);
			throw $ex;
		}
		return $videoBlockSetting;
	}
}
