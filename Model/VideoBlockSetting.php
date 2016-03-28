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
 * @see BlockBehavior
 * @see OriginalKeyBehavior
 * @see MailSettingBehavior::beforeDelete()
 */
	public $actsAs = array(
		'Blocks.Block' => array(
			'name' => 'Block.name',
			// save, delete時にloadModels()
			// delete時にblock_id, block_keyで紐づいてるデータ削除
			'loadModels' => array(
				'ContentComment' => 'ContentComments.ContentComment',
				'Like' => 'Likes.Like',
				'Tag' => 'Tags.Tag',
				'Video' => 'Videos.Video',
				'WorkflowComment' => 'Workflow.WorkflowComment',
			)
		),
		'Mails.MailSetting',			// 自動でメール設定の削除
		'NetCommons.OriginalKey',
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
 * beforeValidate
 *
 * @param array $options Options passed from Model::save().
 * @return bool True if validate operation should continue, false to abort
 * @link http://book.cakephp.org/2.0/ja/models/callback-methods.html#beforevalidate
 * @see Model::save()
 */
	public function beforeValidate($options = array()) {
		$this->validate = Hash::merge($this->validate, array(
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
			'use_workflow' => array(
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
			'use_comment_approval' => array(
				'boolean' => array(
					'rule' => array('boolean'),
					'message' => __d('net_commons', 'Invalid request.'),
				),
			),
		));

		return parent::beforeValidate($options);
	}

/**
 * VideoBlockSettingデータ新規作成
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

/**
 * VideoBlockSettingデータ削除
 *
 * @param array $data received post data
 * @return mixed On success Model::$data if its not empty or true, false on failure
 * @throws InternalErrorException
 */
	public function deleteVideoBlockSetting($data) {
		$this->loadModels(array(
			'Like' => 'Likes.Like',
			'LikesUser' => 'Likes.LikesUser',
			'Tag' => 'Tags.Tag',
			'TagsContent' => 'Tags.TagsContent',
			'UploadFile' => 'Files.UploadFile',
			'UploadFilesContent' => 'Files.UploadFilesContent',
			'Video' => 'Videos.Video',
		));

		//トランザクションBegin
		$this->begin();

		// 多言語コンテンツ削除対応
		// 対象のブロックIDの配列
		$blockIds = $this->__getBlockIds($data['Block']['key']);

		// いいねIDの配列
		$likeIds = $this->Like->find('list', array(
			'recursive' => -1,
			'conditions' => array($this->Like->alias . '.block_key' => $data['Block']['key']),
			'callbacks' => false,
		));
		$likeIds = array_keys($likeIds);

		// タグIDの配列
		$tagIds = $this->Tag->find('list', array(
			'recursive' => -1,
			'conditions' => array($this->Tag->alias . '.block_id' => $blockIds),
			'callbacks' => false,
		));
		$tagIds = array_keys($tagIds);

		// コンテンツキーの配列
		$contentKeys = $this->__getContentKeys($blockIds);

		// アップロードファイルIDの配列
		$uploadFileIds = $this->UploadFile->find('list', array(
			'recursive' => -1,
			'conditions' => array($this->UploadFile->alias . '.content_key' => $contentKeys),
			'callbacks' => false,
		));
		$uploadFileIds = array_keys($uploadFileIds);

		try {
			// VideoBlockSetting削除
			if (! $this->deleteAll(array($this->alias . '.block_key' => $data['Block']['key']), false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			// ファイル 削除 暫定として対応しない(;'∀')
			// 本来、データと物理ファイル削除。共通処理が完成したら、実装する

			// タグコンテンツ 削除
			if (! $this->TagsContent->deleteAll(array($this->TagsContent->alias . '.tag_id' => $tagIds), false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			// いいねユーザー 削除
			if (! $this->LikesUser->deleteAll(array($this->LikesUser->alias . '.like_id' => $likeIds), false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			// アップロードファイルコンテンツ 削除
			if (! $this->UploadFilesContent->deleteAll(array($this->UploadFilesContent->alias . '.upload_file_id' => $uploadFileIds), false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			// アップロードファイル 削除
			if (! $this->UploadFile->deleteAll(array($this->UploadFile->alias . '.content_key' => $contentKeys), false)) {
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
 * ブロックIDの配列
 *
 * @param string $blockKey ブロックキー
 * @return array
 */
	private function __getBlockIds($blockKey) {
		$blockIds = $this->Block->find('list', array(
			'recursive' => -1,
			'conditions' => array($this->Block->alias . '.key' => $blockKey),
			'callbacks' => false,
		));
		$blockIds = array_keys($blockIds);
		return $blockIds;
	}

/**
 * コンテンツキーの配列
 *
 * @param array $blockIds ブロックID複数
 * @return array
 */
	private function __getContentKeys($blockIds) {
		$contentKeys = $this->Video->find('list', array(
			'fields' => array($this->Video->alias . '.key'),
			'recursive' => -1,
			'conditions' => array($this->Video->alias . '.block_id' => $blockIds),
			'callbacks' => false,
		));
		$contentKeys = array_values($contentKeys);
		return $contentKeys;
	}
}
