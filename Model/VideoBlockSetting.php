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
 * @param bool $isBlockSetting ブロック設定画面か
 * @return mixed On success Model::$data if its not empty or true, false on failure
 * @throws InternalErrorException
 */
	public function saveVideoBlockSetting($data, $isBlockSetting) {
		//トランザクションBegin
		$this->begin();

		if ($isBlockSetting) {
			$this->loadModels(array(
				'Block' => 'Blocks.Block',
			));
			$this->Block->validate = array(
				'name' => array(
					'notBlank' => array(
						'rule' => array('notBlank'),
						'message' => sprintf(__d('net_commons', 'Please input %s.'), __d('videos', 'Channel name')),
						'required' => true,
					),
				)
			);
		}

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
			'Video' => 'Videos.Video',
		));

		//トランザクションBegin
		$this->begin();

		//$blockKey = Current::read('Block.key');
		$blockKey = $data[$this->alias]['block_key'];

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
			// VideoBlockSetting削除
			if (! $this->deleteAll(array($this->alias . '.block_key' => $blockKey), false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			// 動画とサムネイルのデータと物理ファイル削除
			foreach ($uploadFiles as $uploadFile) {
				foreach ($uploadFile['UploadFilesContent'] as $uploadFilesContent) {
					$this->UploadFile->removeFile($uploadFilesContent['content_id'], $uploadFilesContent['upload_file_id']);
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
