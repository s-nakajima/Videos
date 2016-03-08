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
 * @see MailQueueBehavior::beforeDelete()
 */
	public $actsAs = array(
		'ContentComments.ContentComment',
		'Likes.Like',					// いいね
		'NetCommons.OriginalKey',		// 自動でkeyセット
		'Mails.MailQueue',				// 自動でキューの削除
		'Tags.Tag',
		'Videos.Video',					// 動画変換
		'Videos.VideoValidation',		// Validation rules
		'Workflow.Workflow',			// 自動でis_active, is_latestセット
		'Workflow.WorkflowComment',
		'Files.Attachment' => [
			Video::VIDEO_FILE_FIELD,
			Video::THUMBNAIL_FIELD,
		],
	);

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
		'User' => array(
			'className' => 'Users.User',
			'foreignKey' => 'created_user',
			'conditions' => '',
			'fields' => 'handlename',
			'order' => ''
		),
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
 * FFMPEG有効フラグをセット
 *
 * @return bool
 */
	public static function isFfmpegEnable() {
		if (isset(Video::$__isFfmpegEnable)) {
			return Video::$__isFfmpegEnable;
		}

		$strCmd = 'which ' . Video::FFMPEG_PATH . ' 2>&1';
		exec($strCmd, $arr);

		// ffmpegコマンドがあるかどうかは環境に依存するため、true or false の両方を通すテストケースは書けない。
		// isFfmpegEnableをモックにして、強制的に true or false を返してテストするので、問題ないと思う。

		if (isset($arr[0]) && $arr[0] === Video::FFMPEG_PATH) {
			// コマンドあり
			Video::$__isFfmpegEnable = true;
		} else {
			// コマンドなし
			Video::$__isFfmpegEnable = false;
		}

		return Video::$__isFfmpegEnable;
	}

/**
 * UserIdと権限から参照可能なEntryを取得するCondition配列を返す
 *
 * @return array condition
 */
	public function getConditions() {
		// contentReadable falseなら何も見えない
		if (! Current::permission('content_readable')) {
			$conditions = array('Video.id' => 0); // ありえない条件でヒット0にしてる
			return $conditions;
		}

		// デフォルト絞り込み条件
		$conditions = array(
			'Video.block_id' => Current::read('Block.id')
		);

		$conditions = $this->getWorkflowConditions($conditions);

		return $conditions;
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
			// 動画データ登録
			if (! $video = $this->save(null, false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			if (self::isFfmpegEnable()) {
				// 動画変換とデータ保存
				/* @see VideoBehavior::saveConvertVideo() */
				if (!$this->saveConvertVideo($video)) {
					return false;
				}
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
 * 編集Videoデータ保存
 *
 * @param array $data received post data
 * @return mixed On success Model::$data if its not empty or true, false on failure
 * @throws InternalErrorException
 */
	public function editSaveVideo($data) {
		//トランザクションBegin
		$this->begin();

		//バリデーション
		$this->set($data);
		/* @see beforeValidate() */
		if (! $this->validates(array('edit'))) {
			$this->rollback();
			return false;
		}

		try {
			// 動画データ登録
			if (! $video = $this->save(null, false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
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
 * Videoデータ削除
 *
 * @param array $data received post data
 * @return mixed On success Model::$data if its not empty or true, false on failure
 * @throws InternalErrorException
 */
	public function deleteVideo($data) {
		$this->loadModels(array(
			'Like' => 'Likes.Like',
			'TagsContent' => 'Tags.TagsContent',
		));

		//トランザクションBegin
		$this->begin();

		try {
			// 動画削除($callbacks = true)
			if (! $this->deleteAll(array($this->alias . '.key' => $data['Video']['key']), false, true)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			// ファイル 削除 暫定として対応しない(;'∀')
			// 本来、データと物理ファイル削除。共通処理が完成したら、実装する

			// タグコンテンツ 削除
			if (! $this->TagsContent->deleteAll(array($this->TagsContent->alias . '.content_id' => $data['Video']['id']), false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			// いいね 削除
			if (! $this->Like->deleteAll(array($this->Like->alias . '.content_key' => $data['Video']['key']), false)) {
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
