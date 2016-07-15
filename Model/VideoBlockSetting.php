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
App::uses('Video', 'Videos.Model');
App::uses('BlockSettingBehavior', 'Blocks.Model/Behavior');

/**
 * VideoBlockSetting Model
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Videos\Model
 */
class VideoBlockSetting extends VideosAppModel {

/**
 * Custom database table name
 *
 * @var string
 */
	public $useTable = 'blocks';

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
 * @see CategoryBehavior
 */
	public $actsAs = array(
		'Blocks.Block' => array(
			'name' => 'VideoBlockSetting.name',
			// save, delete時にloadModels()
			// delete時にblock_id, block_keyで紐づいてるデータ削除
			'loadModels' => array(
				'BlockSetting' => 'Block.BlockSetting',
				'Category' => 'Categories.Category',
				'CategoryOrder' => 'Categories.CategoryOrder',
				'ContentComment' => 'ContentComments.ContentComment',
				'Like' => 'Likes.Like',
				'Tag' => 'Tags.Tag',
				'Video' => 'Videos.Video',
				// メール関連
				'MailSetting' => 'Mails.MailSetting',
				'MailSettingFixedPhrase' => 'Mails.MailSettingFixedPhrase',
				'MailQueue' => 'Mails.MailQueue',
				'MailQueueUser' => 'Mails.MailQueueUser',
			)
		),
		'Blocks.BlockSetting' => array(
			BlockSettingBehavior::FIELD_USE_WORKFLOW,
			BlockSettingBehavior::FIELD_USE_LIKE,
			BlockSettingBehavior::FIELD_USE_UNLIKE,
			BlockSettingBehavior::FIELD_USE_COMMENT,
			BlockSettingBehavior::FIELD_USE_COMMENT_APPROVAL,
			'auto_play',
			'total_size',
		),
		'Categories.Category',
		'NetCommons.OriginalKey',
		'Blocks.BlockRolePermission',
	);

/**
 * hasOne associations
 *
 * @var array
 */
	public $hasOne = array(
		'Block' => array(
			'className' => 'Blocks.Block',
			'foreignKey' => 'id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
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
			'language_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
					'message' => __d('net_commons', 'Invalid request.'),
					'required' => false,
				),
			),
			'room_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
					'message' => __d('net_commons', 'Invalid request.'),
					'required' => false,
				),
			),
			'name' => array(
				'notBlank' => array(
					'rule' => array('notBlank'),
					'message' => sprintf(
						__d('net_commons', 'Please input %s.'), __d('videos', 'Channel name')
					),
					'required' => true,
				),
			)
		));

		return parent::beforeValidate($options);
	}

/**
 * VideoBlockSettingデータ新規作成
 *
 * @return array
 */
	public function createVideoBlockSetting() {
		$videoBlockSetting = $this->createAll(array(
			$this->alias => array(
				'name' => __d('videos', 'New channel %s', date('YmdHis')),
			),
		));
		/** @see BlockSettingBehavior::getBlockSetting() */
		/** @see BlockSettingBehavior::_createBlockSetting() */
		return Hash::merge($videoBlockSetting, $this->getBlockSetting());
	}

/**
 * VideoBlockSettingデータ取得
 *
 * @return array
 */
	public function getVideoBlockSetting() {
		$conditions = array(
			$this->alias . '.key' => Current::read('Block.key'),
			$this->alias . '.language_id' => Current::read('Language.id'),
		);

		$videoBlockSetting = $this->find('first', array(
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
			return false;
		}

		try {
			// $useTable = 'blocks';を指定したので blockを保存すると、BlockBehavior::BlockのbeforeSaveで重複登録される
			if (! $this->save(null, false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			// idなし = 新規登録
			if (!Hash::get($data, $this->alias . '.id')) {
				// 重複したBlockデータを削除
				if (! $this->delete($this->id)) {
					throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
				}
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
			'Video' => 'Videos.Video',
		));

		//トランザクションBegin
		$this->begin();

		$blockKey = $data[$this->alias]['key'];

		// 多言語コンテンツ削除対応
		// 各IDの配列
		$blockIds = $this->__getIds($this->Block, 'key', $blockKey);
		$likeIds = $this->__getIds($this->Like, 'block_key', $blockKey);
		$tagIds = $this->__getIds($this->Tag, 'block_id', $blockIds);

		// コンテンツキーの配列
		$contentKeys = $this->__getContentKeys($blockIds);

		// アップロードファイル
		$uploadFiles = $this->UploadFile->find('all', array(
			'recursive' => 1,
			'conditions' => array($this->UploadFile->alias . '.content_key' => $contentKeys),
			'callbacks' => false,
		));

		try {
			// Blockの削除は、BlockBehavior::deleteBlock()で行うため、この時点では削除しない
			//			// VideoBlockSetting削除
			//			if (! $this->deleteAll(array($this->alias . '.block_key' => $blockKey), false)) {
			//				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			//			}

			// 動画とサムネイルのデータと物理ファイル削除
			foreach ($uploadFiles as $uploadFile) {
				foreach ($uploadFile['UploadFilesContent'] as $uploadFilesContent) {
					$this->UploadFile->removeFile($uploadFilesContent['content_id'],
						$uploadFilesContent['upload_file_id']);
				}
			}

			// アップロードファイル 削除
			$conditions = array($this->UploadFile->alias . '.content_key' => $contentKeys);
			if (! $this->UploadFile->deleteAll($conditions, false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			// タグコンテンツ 削除
			$conditions = array($this->TagsContent->alias . '.tag_id' => $tagIds);
			if (! $this->TagsContent->deleteAll($conditions, false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			// いいねユーザー 削除
			$conditions = array($this->LikesUser->alias . '.like_id' => $likeIds);
			if (! $this->LikesUser->deleteAll($conditions, false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			//Blockデータ削除
			/** @see BlockBehavior::deleteBlock() */
			$this->deleteBlock($blockKey);

			//トランザクションCommit
			$this->commit();

		} catch (Exception $ex) {
			//トランザクションRollback
			$this->rollback($ex);
		}

		return true;
	}

/**
 * コンテンツIDの配列
 *
 * @param Model $model モデル
 * @param string $filed フィールド名
 * @param string $value 値
 * @return array
 */
	private function __getIds(Model $model, $filed, $value) {
		$ids = $model->find('list', array(
			'recursive' => -1,
			'conditions' => array($model->alias . '.' . $filed => $value),
			'callbacks' => false,
		));
		$ids = array_keys($ids);
		return $ids;
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
