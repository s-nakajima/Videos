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
 * @see NetCommonsAppModel::$actAs
 */
	public $actsAs = array(
		'Blocks.Block' => array(
			'name' => 'Block.name',
			'loadModels' => array(
				//'Like' => 'Likes.Like',
				'WorkflowComment' => 'Workflow.WorkflowComment',
			)
		),
		//'NetCommons.OriginalKey',
		'Blocks.BlockRolePermission',
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
			//			'block_key' => array(
			//				'notBlank' => array(
			//					'rule' => array('notBlank'),
			//					'message' => __d('net_commons', 'Invalid request.'),
			//				),
			//			),
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
		//$videoBlockSetting = Hash::merge($videoBlockSetting, $this->VideoBlockSetting->create());
//var_dump($videoBlockSetting);
//$videoBlockSetting[$this->alias]['block_key'] = null;
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

		$videoBlockSetting = $this->find('first', array(
			//'recursive' => -1,
			'recursive' => 0,
			'conditions' => $conditions,
			'order' => $this->alias . '.id DESC'
		));

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
		//トランザクションBegin
		$this->begin();

		// 値をセット
		$this->set($data);
		if (! $this->validates()) {
			$this->rollback();
			return false;
		}

		try {
			if (! $videoBlockSetting = $this->save(null, false)) {
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

/**
 * VideoBlockSettingデータ削除
 *
 * @param array $data received post data
 * @return mixed On success Model::$data if its not empty or true, false on failure
 * @throws InternalErrorException
 */
	public function deleteVideoBlockSetting($data) {
		$this->loadModels(array(
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
