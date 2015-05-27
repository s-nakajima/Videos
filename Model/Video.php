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
 * ffmpeg 有効フラグ
 * レンタルサーバ等、ffmpegを利用できない場合、falseにする
 *
 * @var string
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
				//'mimetypes' => array('video/mp4'),
				//'extensions' => array('mp4'),
			),
			self::THUMBNAIL_FIELD => array(
				//UploadBefavior settings
			),
		),
		'NetCommons.OriginalKey',		// 自動でkeyセット
		'NetCommons.Publishable',		// 自動でis_active, is_latestセット
		'Tags.Tag',
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
		$this->validate = Hash::merge($this->validate, array(
			'title' => array(
				'notEmpty' => array(
					'rule' => array('notEmpty'),
					'message' => sprintf(__d('net_commons', 'Please input %s.'), __d('videos', 'title')),
					'required' => true,
				),
			),
			'key' => array(
				'notEmpty' => array(
					'rule' => array('notEmpty'),
					//'message' => 'Your custom message here',
					//'allowEmpty' => false,
					//'required' => false,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
			),
			'block_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
					//'message' => 'Your custom message here',
					//'allowEmpty' => false,
					//'required' => false,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
			),
			'video_time' => array(
				'numeric' => array(
					'rule' => array('numeric'),
					//'message' => 'Your custom message here',
					//'allowEmpty' => false,
					//'required' => false,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
			),
			'play_number' => array(
				'numeric' => array(
					'rule' => array('numeric'),
					//'message' => 'Your custom message here',
					//'allowEmpty' => false,
					//'required' => false,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
			),
			'is_auto_translated' => array(
				'boolean' => array(
					'rule' => array('boolean'),
					//'message' => 'Your custom message here',
					//'allowEmpty' => false,
					//'required' => false,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
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
 * Videoデータ取得
 *
 * @param array $conditions Conditions data
 * @return array
 */
	public function getVideo($conditions = array()) {
		$video = $this->find('first', array(
			'recursive' => 1,
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
			'fields' => '*, ContentCommentCnt.cnt',	// Behaviorでコンテンツコメント数取得
			'conditions' => $conditions,
			'order' => $this->alias . '.id DESC'
		));

		return $videos;
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
			$this->validates();
			if ($this->validationErrors) {
				return false;
			}

			// ファイルチェック 動画ファイル
			if (!$data = $this->_validateVideoFile($data, self::VIDEO_FILE_FIELD, $this->alias, 'mp4_id', 0)) {
				return false;
			}

			// ステータスチェック
			if (!$this->Comment->validateByStatus($data, array('caller' => $this->name))) {
				$this->validationErrors = Hash::merge($this->validationErrors, $this->Comment->validationErrors);
				return false;
			}

			// ファイルの登録 動画ファイル
			$data = $this->_saveVideoFile($data, self::VIDEO_FILE_FIELD, $this->alias, 'mp4_id', 0);

			// 値をセット
			$this->set($data);

			// 動画データ登録
			$video = $this->save(null, false);
			if (!$video) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}
			//コメントの登録
			if ($this->Comment->data) {
				if (!$this->Comment->save(null, false)) {
					throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
				}
			}

			// 動画変換とデータ保存
			if (!$this->__saveConvertVideo($data, $video, $roomId)) {
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
			$this->validates();
			if ($this->validationErrors) {
				return false;
			}

			// ファイルチェック 動画ファイル
			if (!$data = $this->_validateVideoFile($data, self::VIDEO_FILE_FIELD, $this->alias, 'mp4_id', 0)) {
				return false;
			}

			// ファイルチェック サムネイル
			if (! $data = $this->_validateVideoFile($data, self::THUMBNAIL_FIELD, $this->alias, 'thumbnail_id', 1)) {
				return false;
			}

			// ステータスチェック
			if (!$this->Comment->validateByStatus($data, array('caller' => $this->name))) {
				$this->validationErrors = Hash::merge($this->validationErrors, $this->Comment->validationErrors);
				return false;
			}

			// ファイルの登録 動画ファイル
			$data = $this->_saveVideoFile($data, self::VIDEO_FILE_FIELD, $this->alias, 'mp4_id', 0);

			// ファイルの登録 サムネイル
			$data = $this->_saveVideoFile($data, self::THUMBNAIL_FIELD, $this->alias, 'thumbnail_id', 1);

			// 値をセット
			$this->set($data);

			// 動画データ登録
			$video = $this->save(null, false);
			if (!$video) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}
			//コメントの登録
			if ($this->Comment->data) {
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
			$this->validates();
			if ($this->validationErrors) {
				return false;
			}

			// ファイルチェック サムネイル
			if (! $data = $this->_validateVideoFile($data, self::THUMBNAIL_FIELD, $this->alias, 'thumbnail_id', 1)) {
				return false;
			}

			// ステータスチェック
			if (!$this->Comment->validateByStatus($data, array('caller' => $this->name))) {
				$this->validationErrors = Hash::merge($this->validationErrors, $this->Comment->validationErrors);
				return false;
			}

			// ファイルの登録 サムネイル
			$data = $this->_saveVideoFile($data, self::THUMBNAIL_FIELD, $this->alias, 'thumbnail_id', 1);

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
 * 動画変換とデータ保存
 *
 * @param array $data received post data
 * @param array $video Video
 * @param int $roomId rooms.id
 * @return bool true on success, false on error
 * @throws InternalErrorException
 */
	private function __saveConvertVideo($data, $video, $roomId) {
		// 元動画 取得
		$noConvert = $this->FileModel->findById($video['Video']['mp4_id']);

		// --- 動画変換
		if (! $data = $this->__convertVideo($data, $video, $noConvert, $roomId)) {
			$this->_deleteFile($data, $this->alias, 'mp4_id', 0);	//元動画 削除
			return false;
		}

		// --- 動画時間を取得
		if (!$videoTimeSec = $this->__getVideoTime($noConvert)) {
			$this->_deleteFile($data, $this->alias, 'mp4_id', 0);	//元動画 削除
			return false;
		}
		$data['Video']['video_time'] = $videoTimeSec;

		// --- サムネイル自動作成
		$data = $this->__generateThumbnail($data, $video[self::VIDEO_FILE_FIELD]['FilesPlugin']['plugin_key'], $noConvert, $roomId);

		// ファイルチェック サムネイル
		if (! $data = $this->_validateVideoFile($data, self::THUMBNAIL_FIELD, $this->alias, 'thumbnail_id', 1)) {
			$this->log($this->validationErrors, 'debug');
			//変換後動画、サムネイル 削除
			$this->_deleteFile($data, $this->alias, 'mp4_id', 0);
			$this->_deleteFile($data, $this->alias, 'thumbnail_id', 1);

			return false;
		}

		// ファイルの登録 サムネイル
		$data = $this->_saveVideoFile($data, self::THUMBNAIL_FIELD, $this->alias, 'thumbnail_id', 1);

		// --- 動画テーブルを更新
		// 値をセット
		$this->set($data);

		// 動画データ登録
		$video = $this->save(null, false);
		if (!$video) {
			throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
		}

		return true;
	}

/**
 * 動画変換
 *
 * @param array $data received post data
 * @param array $video Video
 * @param array $noConvert File
 * @param int $roomId rooms.id
 * @return mixed Array on success, false on error
 * @throws InternalErrorException
 */
	private function __convertVideo($data, $video, $noConvert, $roomId) {
		// --- 動画変換

		// アップロードファイルの受け取りと移動
		$noConvertPath = $noConvert['File']["path"];
		$noConvertSlug = $noConvert['File']["slug"];
		$noConvertExtension = $noConvert['File']["extension"];

		// サムネイル名は動画名で末尾jpgにしたものをセット
		$videoName = explode('.', $noConvert['File']["name"])[0];

		// アップロード済みのvideoFileの入力値を、$dataから除外
		unset($data['Video']['videoFile']);

		// mp4は変換しない
		if ($noConvertExtension != "mp4") {

			// 例）ffmpeg -y -i /var/www/html/movies/original/MOV_test_movie.MOV -acodec libmp3lame -ab 128k -ar 44100 -ac 2 -vcodec libx264 -r 30 -b 500k MOV_test_movie.mp4
			// 動画変換
			// 動画変換実施(元動画 > H.264)  コマンドインジェクション対策
			$strCmd = self::FFMPEG_PATH . ' -y -i ' . escapeshellarg($noConvertPath . $noConvertSlug . '.' . $noConvertExtension) . ' ' . self::FFMPEG_OPTION . " " . escapeshellarg($noConvertPath . $noConvertSlug . '.mp4') . ' 2>&1';
			exec($strCmd, $arr, $ret);

			// 変換エラー時
			if ($ret != 0) {
				$this->log("--- ffmpeg H.264 変換エラー", 'debug');
				$this->log($strCmd, 'debug');
				$this->log($arr, 'debug');
				$this->log($ret, 'debug');
				return false;
			}

			// Filesテーブルに変換後動画を登録。Delete->Insert
			$data[self::VIDEO_FILE_FIELD]['File']['type'] = 'video/mp4';
			$data[self::VIDEO_FILE_FIELD]['File']['mimetype'] = 'video/mp4';
			$data[self::VIDEO_FILE_FIELD]['File']['path'] = '{ROOT}' . 'videos' . '{DS}' . $roomId . '{DS}' . $video['Video']['id'] . '{DS}';
			$data[self::VIDEO_FILE_FIELD]['File']['name'] = $videoName . '.mp4';
			$data[self::VIDEO_FILE_FIELD]['File']['alt'] = $videoName . '.mp4';
			$data[self::VIDEO_FILE_FIELD]['File']['extension'] = 'mp4';
			$data[self::VIDEO_FILE_FIELD]['File']['tmp_name'] = $noConvertPath . $noConvertSlug . '.mp4';
			$data[self::VIDEO_FILE_FIELD]['File']['size'] = filesize($noConvertPath . $noConvertSlug . '.mp4');

			// ファイルチェック 変換後動画ファイル
			if (!$data = $this->_validateVideoFile($data, self::VIDEO_FILE_FIELD, $this->alias, 'mp4_id', 0)) {
				$this->log($this->validationErrors, 'debug');
				return false;
			}

			// ファイルの登録 変換後動画ファイル
			$data = $this->_saveVideoFile($data, self::VIDEO_FILE_FIELD, $this->alias, 'mp4_id', 0);

			// 元動画 ファイルのみ削除
			$file = new File($noConvertPath . $noConvertSlug . '.' . $noConvertExtension);
			$file->delete();
		}

		return $data;
	}

/**
 * 動画時間を取得
 *
 * @param array $noConvert File data
 * @return mixed int on success, false on error
 */
	private function __getVideoTime($noConvert) {
		// 元動画
		$noConvertPath = $noConvert['File']["path"];
		$noConvertSlug = $noConvert['File']["slug"];

		// 変換後の動画情報を取得 コマンドインジェクション対策
		$strCmd = self::FFMPEG_PATH . " -i " . escapeshellarg($noConvertPath . $noConvertSlug . '.mp4') . " 2>&1";
		exec($strCmd, $arrInfo, $retInfo);

		// 情報を取得出来なかった場合
		if ($retInfo != 1) {
			$this->log("--- ffmpeg 動画情報取得エラー", 'debug');
			$this->log($strCmd, 'debug');
			$this->log($arrInfo, 'debug');
			$this->log($retInfo, 'debug');
			return false;
		}

		//動画情報から時間を取得
		$videoTimeSec = 0;
		foreach ($arrInfo as $line) {
			//時間を取得(フォーマット：Duration: 00:00:00.0)
			preg_match("/Duration: [0-9]{2}:[0-9]{2}:[0-9]{2}\.\d+/s", $line, $matches);

			//時間を取得出来た場合
			if (count($matches) > 0) {
				//「:」で文字列分割
				$resultLine = explode(':', $matches[0]);

				//動画の時間を計算
				$videoTimeSec = intval(trim($resultLine[1])) * 3600 + intval($resultLine[2]) * 60 + $resultLine[3];
				break;
			}
		}

		return $videoTimeSec;
	}

/**
 * サムネイル自動作成
 *
 * @param array $data received post data
 * @param string $pluginKey plugin key
 * @param array $noConvert File data
 * @param int $roomId rooms.id
 * @return mixed Array on success, false on error
 * @throws InternalErrorException
 */
	private function __generateThumbnail($data, $pluginKey, $noConvert, $roomId) {
		// 元動画
		$noConvertPath = $noConvert['File']["path"];
		$noConvertSlug = $noConvert['File']["slug"];
		$videoName = explode('.', $noConvert['File']["name"])[0];

		// --- サムネイル自動作成
		// 動画変換実施(元動画 > サムネイル)
		$thumbnailSlug = Security::hash(
			$noConvertPath . $noConvertSlug . '.mp4' . mt_rand() . microtime(), 'md5'
		);

		// 例) ffmpeg -ss 1 -vframes 1 -i /var/www/html/movies/play/20130901_072755.mp4 -f image2 /var/www/html/movies/play/20130901_072755.jpg
		// サムネイルは変換後のmp4 から生成する。mts からサムネイルを生成した場合、うまく生成できなかった。ファイル形式によりサムネイル生成に制限がある可能性があるため。
		// コマンドインジェクション対策
		$strCmd = self::FFMPEG_PATH . ' -ss 1 -vframes 1 -i ' . escapeshellarg($noConvertPath . $noConvertSlug . ".mp4") . ' -f image2 ' . escapeshellarg($noConvertPath . $thumbnailSlug . '.jpg');
		exec($strCmd, $arrImage, $retImage);

		// 変換エラー時
		if ($retImage != 0) {
			$this->log("--- ffmpeg サムネイル 生成エラー", 'debug');
			$this->log($strCmd, 'debug');
			$this->log($arrImage, 'debug');
			$this->log($retImage, 'debug');
			// return はしない。
		}

		// サムネイルデータ準備
		$data['Video'][self::THUMBNAIL_FIELD]['name'] = $videoName . '.jpg';	// サムネイル名は動画名で末尾jpgにしたものをセット
		$data['Video'][self::THUMBNAIL_FIELD]['type'] = 'image/jpeg';
		$data['Video'][self::THUMBNAIL_FIELD]['tmp_name'] = $noConvertPath . $thumbnailSlug . '.jpg';
		$data['Video'][self::THUMBNAIL_FIELD]['error'] = UPLOAD_ERR_OK;
		$data['Video'][self::THUMBNAIL_FIELD]['size'] = filesize($noConvertPath . $thumbnailSlug . '.jpg');

		// Filesテーブルにサムネイルを登録
		$data[self::THUMBNAIL_FIELD]['File']['status'] = 1;
		$data[self::THUMBNAIL_FIELD]['File']['role_type'] = 'room_file_role';
		$data[self::THUMBNAIL_FIELD]['File']['name'] = $videoName . '.jpg';		// サムネイル名は動画名をjpgにしたものをセット
		$data[self::THUMBNAIL_FIELD]['File']['alt'] = $videoName . '.jpg';
		$data[self::THUMBNAIL_FIELD]['File']['mimetype'] = 'image/jpeg';
		$data[self::THUMBNAIL_FIELD]['File']['path'] = '{ROOT}' . 'videos' . '{DS}' . $roomId . '{DS}';		// 自動的に $video['Video']['id'] . '{DS}' が末尾に追記されるので、ここでは追記しない
		$data[self::THUMBNAIL_FIELD]['File']['extension'] = 'jpg';
		$data[self::THUMBNAIL_FIELD]['File']['tmp_name'] = $noConvertPath . $thumbnailSlug . '.jpg';
		$data[self::THUMBNAIL_FIELD]['File']['size'] = filesize($noConvertPath . $thumbnailSlug . '.jpg');
		$data[self::THUMBNAIL_FIELD]['File']['slug'] = $thumbnailSlug;
		$data[self::THUMBNAIL_FIELD]['File']['original_name'] = $thumbnailSlug;

		$data[self::THUMBNAIL_FIELD]['FilesPlugin']['plugin_key'] = $pluginKey;	// plugin_keyは、元動画のをセット
		$data[self::THUMBNAIL_FIELD]['FilesRoom']['room_id'] = $roomId;
		$data[self::THUMBNAIL_FIELD]['FilesUser']['user_id'] = AuthComponent::user('id');

		return $data;
	}
}
