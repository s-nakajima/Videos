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
 * file field name 動画ファイル
 *
 * @var string
 */
	const VIDEO_FILE_FIELD = 'videoFile';

/**
 * file field name サムネイル
 *
 * @var string
 */
	const THUMBNAIL_FIELD = 'thumbnail';

/**
 * 動画 拡張子
 *
 * @var string
 */
	const VIDEO_EXTENSION = 'mpeg,mpg,avi,mov,wmv,flv,mp4';

/**
 * 動画 MIMEタイプ
 *
 * @var string
 */
	const VIDEO_MIME_TYPE = 'video/mpeg,video/mpg,video/avi,video/quicktime,video/x-ms-wmv,video/x-flv,video/mp4';

/**
 * サムネイル 拡張子
 *
 * @var string
 */
	const THUMBNAIL_EXTENSION = 'jpg,png,gif';

/**
 * サムネイル MIMEタイプ
 *
 * @var string
 */
	const THUMBNAIL_MIME_TYPE = 'image/jpeg,image/png,image/gif';

/**
 * ffmpeg 有効フラグ
 * レンタルサーバ等、ffmpegを利用できない場合、falseにする
 *
 * @var bool
 */
	const FFMPEG_ENABLE = true;

/**
 * ffmpeg パス
 *
 * @var string
 */
	// for CentOS
	//const FFMPEG_PATH = '/usr/bin/ffmpeg';
	// for Ubuntu
	//const FFMPEG_PATH = 'avconv';
	const FFMPEG_PATH = 'ffmpeg';

/**
 * ffmpeg オプション
 *
 * @var string
 */
	const FFMPEG_OPTION = '-acodec libmp3lame -ab 128k -ar 44100 -ac 2 -vcodec libx264 -r 30 -b 500k';

/**
 * use behaviors
 *
 * @var array
 */
	public $actsAs = array(
		'ContentComments.ContentComment',
		'Files.YAUpload' => array(		// FileUpload
			self::VIDEO_FILE_FIELD => array(
				//UploadBefavior settings
			),
			self::THUMBNAIL_FIELD => array(
				//UploadBefavior settings
			),
		),
		'Likes.Like',					// いいね
		'NetCommons.OriginalKey',		// 自動でkeyセット
		'NetCommons.Publishable',		// 自動でis_active, is_latestセット
		'Tags.Tag',
		'Videos.Video',					// 動画変換
		'Videos.VideoFile',				// FileUpload
		'Videos.VideoValidation',		// Validation rules
	);

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array();

	//The Associations below have been created with all possible keys, those that are not needed can be removed

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
		// ffmpeg = on
		if (self::FFMPEG_ENABLE) {
			$this->validate = $this->rules();
		} else {
			$this->validate = $this->rulesFfmpegOff();
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
		'FileMp4' => array(
			'className' => 'Files.FileModel',
			'foreignKey' => 'mp4_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'FileThumbnail' => array(
			'className' => 'Files.FileModel',
			'foreignKey' => 'thumbnail_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
	);

/**
 * hasOne associations
 *
 * @var array
 */
	public $hasOne = array(
		'UserAttributesUser' => array(
			'className' => 'Users.UserAttributesUser',
			'foreignKey' => false,
			'conditions' => array(
				'UserAttributesUser.user_id = Video.created_user',
				'UserAttributesUser.language_id = Block.language_id',
				'UserAttributesUser.key = "nickname"',
			),
			'fields' => 'value',
		),
	);

/**
 * After find callback. Can be used to modify any results returned by find.
 *
 * @param mixed $results The results of the find operation
 * @param bool $primary Whether this model is being queried directly (vs. being queried as an association)
 * @return mixed An array value will replace the value of $results - any other value will be ignored.
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
				$results[$key][$alias]['video_time_edit'] = $this->convSecToHourEdit($row['video_time']);
			}
		}
		return $results;
	}

/**
 * Before save method. Called before all saves
 *
 * Handles setup of file uploads
 *
 * @param array $options Options passed from Model::save().
 * @return bool
 */
	public function beforeSave($options = array()) {
		if (!self::FFMPEG_ENABLE) {
			// 再生時間
			if (isset($this->data[$this->alias]['video_time']) && gettype($this->data[$this->alias]['video_time']) == 'string') {
				//時：分：秒を秒に変更
				$times = explode(":", $this->data[$this->alias]['video_time']);
				$this->data[$this->alias]['video_time'] = intval(trim($times[0])) * 3600 + intval($times[1]) * 60 + $times[2];
			}
		}
		return parent::beforeSave($options);
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
		// 登録・更新・削除時のみ利用する。これの内部処理で master に切替。get時は slave1等
		$this->loadModels(array(
			'Video' => 'Videos.Video',
		));

		$video['Video'] = $data['Video'];
		//再生回数 + 1
		$video['Video']['play_number']++;

		//トランザクションBegin
		$dataSource = $this->getDataSource();
		$dataSource->begin();

		try {
			// 値をセット
			$this->set($video);

			// 動画データ保存 コールバックoff
			$video = $this->save(null, array(
				'validate' => false,
				'callbacks' => false,
			));

			if (!$video) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			};

			$dataSource->commit();
		} catch (InternalErrorException $ex) {
			$dataSource->rollback();
			CakeLog::write(LOG_ERR, $ex);
			throw $ex;
		}
		return $video[$this->alias]['play_number'];
	}

/**
 * 登録Videoデータ保存
 *
 * @param array $data received post data
 * @param int $roomId rooms.id
 * @return mixed On success Model::$data if its not empty or true, false on failure
 * @throws InternalErrorException
 */
	public function addSaveVideo($data, $roomId) {
		// 登録・更新・削除時のみ利用する。これの内部処理で master に切替。get時は slave1等
		$this->loadModels(array(
			'Video' => 'Videos.Video',
			'Comment' => 'Comments.Comment',
			'FileModel' => 'Files.FileModel',
		));

		//トランザクションBegin
		$dataSource = $this->getDataSource();
		$dataSource->begin();

		try {
			// 値をセット
			$this->set($data);

			// 入力チェック
			$this->validates(array('add'));
			if ($this->validationErrors) {
				$this->log($this->Video->validationErrors, 'debug');
				return false;
			}

			// ファイルチェック 動画ファイル
			if (!$data = $this->validateVideoFile($data, self::VIDEO_FILE_FIELD, $this->alias, 'mp4_id', 0)) {
				return false;
			}

			// ステータスチェック
			if (!$this->Comment->validateByStatus($data, array('plugin' => $this->plugin, 'caller' => $this->name))) {
				$this->validationErrors = Hash::merge($this->validationErrors, $this->Comment->validationErrors);
				return false;
			}

			// 動画ファイルを一旦登録
			$data = $this->saveVideoFile($data, self::VIDEO_FILE_FIELD, $this->alias, 'mp4_id', 0);

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

			// 動画変換とデータ保存
			if (!$this->saveConvertVideo($data, $video, $roomId)) {
				return false;
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
 * 登録Videoデータ保存 動画を自動変換しない
 *
 * @param array $data received post data
 * @return mixed On success Model::$data if its not empty or true, false on failure
 * @throws InternalErrorException
 */
	public function addNoConvertSaveVideo($data) {
		// 登録・更新・削除時のみ利用する。これの内部処理で master に切替。get時は slave1等
		$this->loadModels(array(
			'Video' => 'Videos.Video',
			'Comment' => 'Comments.Comment',
			'FileModel' => 'Files.FileModel',
		));

		//トランザクションBegin
		$dataSource = $this->getDataSource();
		$dataSource->begin();

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
		// 登録・更新・削除時のみ利用する。これの内部処理で master に切替。get時は slave1等
		$this->loadModels(array(
			'Video' => 'Videos.Video',
			'Comment' => 'Comments.Comment',
			'FileModel' => 'Files.FileModel',
		));

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

			// ステータスチェック
			if (!$this->Comment->validateByStatus($data, array('plugin' => $this->plugin, 'caller' => $this->name))) {
				$this->validationErrors = Hash::merge($this->validationErrors, $this->Comment->validationErrors);
				return false;
			}

			// ファイルの登録 サムネイル
			$data = $this->saveVideoFile($data, self::THUMBNAIL_FIELD, $this->alias, 'thumbnail_id', 1);

			// 値をセット
			$this->set($data);

			//コメントの登録
			if ($this->Comment->data) {
				if (!$this->Comment->save(null, false)) {
					throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
				}
			}
			// 動画データ登録
			$video = $this->save(null, false);
			if (!$video) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
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
 * Videoデータ削除
 *
 * @param array $data received post data
 * @return mixed On success Model::$data if its not empty or true, false on failure
 * @throws InternalErrorException
 */
	public function deleteVideo($data) {
		$this->loadModels(array(
			'Comment' => 'Comments.Comment',
			'ContentComment' => 'ContentComments.ContentComment',
			'FileModel' => 'Files.FileModel',		// FileUpload
			'Like' => 'Likes.Like',
			'TagsContent' => 'Tags.TagsContent',
			'Video' => 'Videos.Video',
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

			// 承認コメント削除
			if (! $this->Comment->deleteAll(array($this->Comment->alias . '.content_key' => $data['Video']['key']), false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

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
