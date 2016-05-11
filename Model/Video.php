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
App::uses('VideoValidationBehavior', 'Videos.Model/Behavior');

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
	// @codingStandardsIgnoreStart
	// [.]よる文字連結で複数行にすると syntax error になるため、phpcs(1行100文字制限)を除外
	const VIDEO_MIME_TYPE = 'video/mpeg,video/mpg,video/avi,video/quicktime,video/x-ms-wmv,video/x-ms-asf,video/x-flv,video/mp4';
	// @codingStandardsIgnoreEnd

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
	public $isFfmpegEnable = null;

/**
 * use behaviors
 *
 * @var array
 * @see MailQueueBehavior
 */
	public $actsAs = array(
		'ContentComments.ContentComment',
		'Likes.Like',					// いいね
		'NetCommons.OriginalKey',		// 自動でkeyセット
		'Tags.Tag',
		'Videos.Video',					// 動画変換
		'Videos.VideoValidation',		// Validation rules
		'Workflow.Workflow',			// 自動でis_active, is_latestセット
		'Workflow.WorkflowComment',
		// 自動でメールキューの登録, 削除。ワークフロー利用時はWorkflow.Workflowより下に記述する
		'Mails.MailQueue' => array(
			'embedTags' => array(
				'X-SUBJECT' => 'Video.title',
				'X-BODY' => 'Video.description',
			),
			// 暫定対応：メールで承認するフラグ取得用（今後設定不要になる見込み）
			'useWorkflow' => 'VideoBlockSetting.use_workflow',
		),
		'Mails.MailQueueDelete',
		'Files.Attachment' => [
			Video::VIDEO_FILE_FIELD,
			Video::THUMBNAIL_FIELD,
		],
		'Topics.Topics' => array(
			'fields' => array(
				'title' => 'Video.title',
				'summary' => 'Video.description',
				'path' => '/:plugin_key/:plugin_key/view/:block_id/:content_key',
			),
		),
	);

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
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
		/** @see VideoValidationBehavior::setSettingVideo() */
		$this->setSettingVideo(VideoValidationBehavior::IS_FFMPEG_ENABLE, self::isFfmpegEnable());

		/** @see VideoValidationBehavior::rules() */
		$this->validate = $this->rules($options);

		return parent::beforeValidate($options);
	}

/**
 * FFMPEG有効フラグをセット
 *
 * @return bool
 */
	public function isFfmpegEnable() {
		if (isset($this->isFfmpegEnable)) {
			return $this->isFfmpegEnable;
		}

		$strCmd = 'which ' . self::FFMPEG_PATH . ' 2>&1';
		exec($strCmd, $arr);

		if (isset($arr[0]) && $arr[0] === self::FFMPEG_PATH) {
			// コマンドあり
			$this->isFfmpegEnable = true;
		} else {
			// コマンドなし
			$this->isFfmpegEnable = false;
		}

		return $this->isFfmpegEnable;
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
	public function countUp($data) {
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
 * Videoデータ保存
 *
 * @param array $data received post data
 * @param int $isEdit 編集か
 * @return mixed On success Model::$data if its not empty or true, false on failure
 * @throws InternalErrorException
 */
	public function saveVideo($data, $isEdit = 0) {
		//トランザクションBegin
		$this->begin();

		if ($isEdit) {
			$options = array('edit');
		} else {
			$options = array('add');
		}

		//バリデーション
		$this->set($data);
		/* @see beforeValidate() */
		if (! $this->validates($options)) {
			return false;
		}

		try {
			if (! $video = $this->save(null, false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			// 編集でない=登録 and Ffmpeg=ON
			if (!$isEdit && self::isFfmpegEnable()) {
				// 動画変換のため、計2回saveしているので、MailQueueビヘイビア外す
				$this->Behaviors->unload('Mails.MailQueue');

				// 動画変換とデータ保存
				/* @see VideoBehavior::saveConvertVideo() */
				$this->saveConvertVideo($video);
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
			$conditions = array($this->TagsContent->alias . '.content_id' => $data['Video']['id']);
			if (! $this->TagsContent->deleteAll($conditions, false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			// いいね 削除
			$conditions = array($this->Like->alias . '.content_key' => $data['Video']['key']);
			if (! $this->Like->deleteAll($conditions, false)) {
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
