<?php
/**
 * Video Model
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('VideosAppModel', 'Videos.Model');
App::uses('UploadBehavior', 'Upload.Model/Behavior'); //FileUpload

/**
 * Video Model
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Videos\Model
 */
class Video extends VideosAppModel {

/**
 * @var string file field name 動画ファイル
 */
	const VIDEO_FILE_FIELD = 'video_file';

/**
 * @var string file field name サムネイル
 */
	const THUMBNAIL_FIELD = 'thumbnail';

/**
 * @var string 動画 拡張子
 */
	const VIDEO_EXTENSION = 'mpeg,mpg,avi,mov,wmv,flv,mp4';

/**
 * @var string 動画 MIMEタイプ
 */
	const VIDEO_MIME_TYPE = 'video/mpeg,video/mpg,video/avi,video/quicktime,video/x-ms-wmv,video/x-ms-asf,video/x-flv,video/mp4';

/**
 * @var string サムネイル 拡張子
 */
	const THUMBNAIL_EXTENSION = 'jpg,png,gif';

/**
 * @var string サムネイル MIMEタイプ
 */
	const THUMBNAIL_MIME_TYPE = 'image/jpeg,image/png,image/gif';

/**
 * @var string ffmpeg パス
 */
	const FFMPEG_PATH = '/usr/bin/ffmpeg';		// for CentOS, Ubuntu 12.04LTS
	//const FFMPEG_PATH = '/usr/bin/avconv';	// for Ubuntu

/**
 * @var string ffmpeg オプション
 */
	const FFMPEG_OPTION = '-acodec libmp3lame -ab 128k -ar 44100 -ac 2 -vcodec libx264 -r 30 -b 500k';

/**
 * @var bool ffmpeg 有効フラグ
 */
	protected static $__isFfmpegEnable = null;

/**
 * use behaviors
 *
 * @var array
 */
	public $actsAs = array(
		//'ContentComments.ContentComment',
		'ContentComments.ContentCommentCount',
		//		'Files.YAUpload' => array(		// FileUpload
		//			self::VIDEO_FILE_FIELD => array(
		//				//UploadBefavior settings
		//			),
		//			self::THUMBNAIL_FIELD => array(
		//				//UploadBefavior settings
		//			),
		//		),
		'Likes.Like',					// いいね
		'NetCommons.OriginalKey',		// 自動でkeyセット
		//'NetCommons.Publishable',		// 自動でis_active, is_latestセット
		'Tags.Tag',
		'Videos.Video',					// 動画変換
		'Videos.VideoFile',				// FileUpload
		'Videos.VideoValidation',		// Validation rules
		'Workflow.Workflow',
		'Workflow.WorkflowComment',
		'Files.Attachment' => [
			Video::VIDEO_FILE_FIELD,
			Video::THUMBNAIL_FIELD,
		],
	);

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array();

/**
 * Called during validation operations, before validation. Please note that custom
 * validation rules can be defined in $validate.
 *
 * @param array $options Options passed from Model::save().
 * @return bool True if validate operation should continue, false to abort
 * @link http://book.cakephp.org/2.0/ja/models/callback-methods.html#beforevalidate
 * @see Model::save()
 */
	public function beforeValidate($options = array()) {
		// サムネイル 任意 対応
		if (isset($this->data['Video'][self::THUMBNAIL_FIELD]) &&
			isset($this->data['Video'][self::THUMBNAIL_FIELD]['size']) &&
			$this->data['Video'][self::THUMBNAIL_FIELD]['size'] === 0) {

			unset($this->data['Video'][self::THUMBNAIL_FIELD]);
		}

		if (self::isFfmpegEnable()) {
			$this->validate = $this->rules();
		} else {
			$this->validate = $this->rulesFfmpegOff($options);
		}

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
			'foreignKey' => 'block_id',
			'conditions' => '',
			'fields' => 'language_id',
			'order' => ''
		),
		//		'FileMp4' => array(
		//			'className' => 'Files.FileModel',
		//			'foreignKey' => 'mp4_id',
		//			'conditions' => '',
		//			'fields' => '',
		//			'order' => ''
		//		),
		//		'FileThumbnail' => array(
		//			'className' => 'Files.FileModel',
		//			'foreignKey' => 'thumbnail_id',
		//			'conditions' => '',
		//			'fields' => '',
		//			'order' => ''
		//		),
		'User' => array(
			'className' => 'Users.User',
			'foreignKey' => 'created_user',
			'conditions' => '',
			'fields' => 'handlename',
			'order' => ''
		),
	);

/**
 * Called after each find operation. Can be used to modify any results returned by find().
 * Return value should be the (modified) results.
 *
 * @param mixed $results The results of the find operation
 * @param bool $primary Whether this model is being queried directly (vs. being queried as an association)
 * @return mixed Result of the find operation
 * @link http://book.cakephp.org/2.0/ja/models/callback-methods.html#afterfind
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 */
	public function afterFind($results, $primary = false) {
		foreach ($results as $key => &$rows) {
			foreach ($rows as $alias => $row) {
				if (! isset($row['video_time'])) {
					continue;
				}
				// 秒を時：分：秒に変更
				$results[$key][$alias]['video_time_view'] = $this->convSecToHour($row['video_time']);
				//$results[$key][$alias]['video_time_edit'] = $this->convSecToHourEdit($row['video_time']);
			}
		}
		return $results;
	}

/**
 * UserIdと権限から参照可能なEntryを取得するCondition配列を返す
 *
 * @param int $blockId ブロックId
 * @param int $userId アクセスユーザID
 * @param array $permissions 権限
 * @param datetime $currentDateTime 現在日時
 * @return array condition
 */
	public function getConditions($blockId, $userId, $permissions, $currentDateTime) {
		// contentReadable falseなら何も見えない
		if ($permissions['content_readable'] === false) {
			$conditions = array('Video.id' => 0); // ありえない条件でヒット0にしてる
			return $conditions;
		}

		// デフォルト絞り込み条件
		$conditions = array(
			'Video.block_id' => $blockId
		);

		$conditions = $this->getWorkflowConditions($conditions);

		return $conditions;
	}

/**
 * Videoデータ取得
 *
 * @param array $conditions Conditions data
 * @param array $fields fields
 * @return array
 */
	public function getVideo($conditions = array(), $fields = null) {
		$video = $this->find('first', array(
			'recursive' => 1,
			'fields' => $fields,
			'conditions' => $conditions,
			'order' => $this->alias . '.id DESC'
		));

		return $video;
	}

/**
 * 複数Videoデータ取得
 *
 * @param array $conditions Conditions data
 * @return array
 */
	public function getVideos($conditions = array()) {
		// モデルからビヘイビアをはずす
		$this->Behaviors->unload('Tags.Tag');

		$videos = $this->find('all', array(
			'recursive' => 1,
			'fields' => array(
				'*',
				'ContentCommentCnt.cnt',	// Behaviorでコンテンツコメント数取得
			),
			'conditions' => $conditions,
			'order' => $this->alias . '.id DESC'
		));

		return $videos;
	}

/**
 * 再生回数 + 1 で更新
 *
 * @param array $data received post data
 * @return mixed On success Model::$data if its not empty or true, false on failure
 * @throws InternalErrorException
 */
	public function updateCountUp($data) {
		$video['Video'] = $data['Video'];
		//再生回数 + 1
		$video['Video']['play_number']++;

		//トランザクションBegin
		$this->begin();

		// 値をセット
		$this->set($video);

		try {

			// コールバックoff
			$validate = array(
				'validate' => false,
				'callbacks' => false,
			);

			// 動画データ保存
			if (! $video = $this->save(null, $validate)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			//トランザクションCommit
			$this->commit();

		} catch (Exception $ex) {
			//トランザクションRollback
			$this->rollback($ex);
		}

		return $video[$this->alias]['play_number'];
	}

/**
 * 登録Videoデータ保存
 *
 * @param array $data received post data
 * @return mixed On success Model::$data if its not empty or true, false on failure
 * @throws InternalErrorException
 */
	public function addSaveVideo($data) {
		//トランザクションBegin
		$this->begin();

		//バリデーション
		$this->set($data);
		if (! $this->validates(array('add'))) {
			$this->rollback();
			return false;
		}

		try {

//			// 入力チェック
//			$this->validates(array('add'));
//			if ($this->validationErrors) {
//				$this->log($this->Video->validationErrors, 'debug');
//				return false;
//			}

//			// ファイルチェック 動画ファイル
//			if (!$data = $this->validateVideoFile($data, self::VIDEO_FILE_FIELD, $this->alias, 'mp4_id', 0)) {
//				return false;
//			}

			// ステータスチェック
//			if (!$this->Comment->validateByStatus($data, array('plugin' => $this->plugin, 'caller' => $this->name))) {
//				$this->validationErrors = Hash::merge($this->validationErrors, $this->Comment->validationErrors);
//				return false;
//			}

//			// 動画ファイルを一旦登録
//			$data = $this->saveVideoFile($data, self::VIDEO_FILE_FIELD, $this->alias, 'mp4_id', 0);

			// 値をセット
			$this->set($data);

			// 動画データ登録
			if (! $video = $this->save(null, false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			// 動画変換とデータ保存
//			if (!$this->saveConvertVideo($data, $video)) {
			if (!$this->saveConvertVideo($video)) {
				return false;
			}

			//トランザクションCommit
			$this->commit();

		} catch (Exception $ex) {
			//トランザクションRollback
			$this->rollback($ex);
		}

		return $video;
	}

/**
 * 登録Videoデータ保存 動画を自動変換しない
 *
 * @param array $data received post data
 * @return mixed On success Model::$data if its not empty or true, false on failure
 * @throws InternalErrorException
 */
	public function addNoConvertSaveVideo($data) {
//		// 登録・更新・削除時のみ利用する。これの内部処理で master に切替。get時は slave1等
//		$this->loadModels(array(
//			'Video' => 'Videos.Video',
////			'Comment' => 'Comments.Comment',
//			'FileModel' => 'Files.FileModel',
//		));

		//トランザクションBegin
		$this->begin();

		try {
			// 値をセット
			$this->set($data);

			// 入力チェック
			$this->validates(array('add'));
			if ($this->validationErrors) {
				return false;
			}

			// ファイルチェック 動画ファイル
			if (!$data = $this->validateVideoFile($data, self::VIDEO_FILE_FIELD, $this->alias, 'mp4_id', 0)) {
				return false;
			}

			// ファイルチェック サムネイル
			if (! $data = $this->validateVideoFile($data, self::THUMBNAIL_FIELD, $this->alias, 'thumbnail_id', 1)) {
				return false;
			}

			// ステータスチェック
			if (!$this->Comment->validateByStatus($data, array('plugin' => $this->plugin, 'caller' => $this->name))) {
				$this->validationErrors = Hash::merge($this->validationErrors, $this->Comment->validationErrors);
				return false;
			}

			// ファイルの登録 動画ファイル
			$data = $this->saveVideoFile($data, self::VIDEO_FILE_FIELD, $this->alias, 'mp4_id', 0);

			// ファイルの登録 サムネイル
			$data = $this->saveVideoFile($data, self::THUMBNAIL_FIELD, $this->alias, 'thumbnail_id', 1);

			// 値をセット
			$this->set($data);

			// 動画データ登録
			$video = $this->save(null, false);
			if (!$video) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}
			//コメントの登録
			if ($this->Comment->data) {
				// コンテンツキーをセット
				$this->Comment->data[$this->Comment->name]['content_key'] = $video['Video']['key'];

				if (!$this->Comment->save(null, false)) {
					throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
				}
			}

			$dataSource->commit();
		} catch (InternalErrorException $ex) {
			$dataSource->rollback();
			CakeLog::write(LOG_ERR, $ex);
			throw $ex;
		}
		return $video;
	}

/**
 * 編集Videoデータ保存
 *
 * @param array $data received post data
 * @return mixed On success Model::$data if its not empty or true, false on failure
 * @throws InternalErrorException
 */
	public function editSaveVideo($data) {
//		// 登録・更新・削除時のみ利用する。これの内部処理で master に切替。get時は slave1等
//		$this->loadModels(array(
//			'Video' => 'Videos.Video',
//			'Comment' => 'Comments.Comment',
//			'FileModel' => 'Files.FileModel',
//		));

		//トランザクションBegin
		$dataSource = $this->getDataSource();
		$dataSource->begin();

		try {
			// 値をセット
			$this->set($data);

			// 入力チェック
			$this->validates(array('edit'));
			if ($this->validationErrors) {
				return false;
			}

			// ファイルチェック サムネイル
			if (! $data = $this->validateVideoFile($data, self::THUMBNAIL_FIELD, $this->alias, 'thumbnail_id', 1)) {
				return false;
			}

//			// ステータスチェック
//			if (!$this->Comment->validateByStatus($data, array('plugin' => $this->plugin, 'caller' => $this->name))) {
//				$this->validationErrors = Hash::merge($this->validationErrors, $this->Comment->validationErrors);
//				return false;
//			}

			// ファイルの登録 サムネイル
			$data = $this->saveVideoFile($data, self::THUMBNAIL_FIELD, $this->alias, 'thumbnail_id', 1);

			// 値をセット
			$this->set($data);

			// 動画データ登録
			$video = $this->save(null, false);
			if (!$video) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

//			//コメントの登録
//			if ($this->Comment->data) {
//				if (!$this->Comment->save(null, false)) {
//					throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
//				}
//			}

			$dataSource->commit();
		} catch (InternalErrorException $ex) {
			$dataSource->rollback();
			CakeLog::write(LOG_ERR, $ex);
			throw $ex;
		}
		return $video;
	}

/**
 * Videoデータ削除
 *
 * @param array $data received post data
 * @return mixed On success Model::$data if its not empty or true, false on failure
 * @throws InternalErrorException
 */
	public function deleteVideo($data) {
		$this->loadModels(array(
//			'Comment' => 'Comments.Comment',
			'ContentComment' => 'ContentComments.ContentComment',
//			'FileModel' => 'Files.FileModel',		// FileUpload
			'Like' => 'Likes.Like',
			'TagsContent' => 'Tags.TagsContent',
//			'Video' => 'Videos.Video',
		));

		//トランザクションBegin
		$dataSource = $this->getDataSource();
		$dataSource->begin();

		try {
			// 動画削除
			if (! $this->deleteAll(array($this->alias . '.key' => $data['Video']['key']), false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			// ファイル 削除 暫定として対応しない(;'∀')
			// 本来、データと物理ファイル削除。共通処理が完成したら、実装する

//			// 承認コメント削除
//			if (! $this->Comment->deleteAll(array($this->Comment->alias . '.content_key' => $data['Video']['key']), false)) {
//				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
//			}

			// コンテンツコメント 削除
			if (! $this->ContentComment->deleteAll(array($this->ContentComment->alias . '.content_key' => $data['Video']['key']), false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			// タグコンテンツ 削除
			if (! $this->TagsContent->deleteAll(array($this->TagsContent->alias . '.content_id' => $data['Video']['id']), false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			// いいね 削除
			if (! $this->Like->deleteAll(array($this->Like->alias . '.content_key' => $data['Video']['key']), false)) {
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
}
