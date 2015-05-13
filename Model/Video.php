<?php
/**
 * Video Model
 *
 * @property Video $Video
 * @property Language $Language
 * @property Block $Block
 * @property Part $Part
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('VideosAppModel', 'Videos.Model');
//FileUpload
App::uses('UploadBehavior', 'Upload.Model/Behavior');

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
 * file field name サムネイル
 *
 * @var string
 */
	const SHORT_TITLE_LENGTH = 25;

/**
 * use behaviors
 *
 * @var array
 */
	public $actsAs = array(
		//'NetCommons.Publishable',
		'Files.YAUpload' => array(			// FileUpload
			self::VIDEO_FILE_FIELD => array(
				//UploadBefavior settings
				'mimetypes' => array('video/mp4'),
				//'extensions' => array('mp4'),
			),
			self::THUMBNAIL_FIELD => array(
				//UploadBefavior settings
			),
		),
	);

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
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
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

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
		// Comment=承認コメント、コンテンツコメント=新たに作成するプラグインになった
		/*'Comment' => array(
			'className' => 'Comments.Comment',
			'foreignKey' => false,
			'conditions' => 'Comment.content_key = Video.key',
			//'fields' => array('count(Comment.id) as comments_number'),
			'order' => ''
		),*/
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
 * @param string $key videos.key
 * @param int $languageId languages.id
 * @param bool $contentEditable true can edit the content, false not can edit the content.
 * @return array
 */
	public function getVideo($key, $languageId, $contentEditable) {
		$conditions = array(
			$this->alias . '.key' => $key,
			'Block.language_id' => $languageId,
		);
		if (! $contentEditable) {
			$conditions[$this->alias . '.status'] = NetCommonsBlockComponent::STATUS_PUBLISHED;
		}

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
 * @param int $createdUser videos.createdUser
 * @param int $blockId blocks.id
 * @param bool $contentEditable true can edit the content, false not can edit the content.
 * @return array
 */
	public function getVideos($createdUser, $blockId, $contentEditable) {
		$conditions = array(
			$this->alias . '.block_id' => $blockId,
			$this->alias . '.created_user' => $createdUser,
		);
		if (! $contentEditable) {
			$conditions[$this->alias . '.status'] = NetCommonsBlockComponent::STATUS_PUBLISHED;
		}

		$videos = $this->find('all', array(
			'recursive' => 1,
			'conditions' => $conditions,
			'order' => $this->alias . '.id DESC'
		));

		return $videos;
	}

/**
 * Videoデータ保存
 *
 * @param array $data received post data
 * @return mixed On success Model::$data if its not empty or true, false on failure
 * @throws InternalErrorException
 */
	public function saveVideo($data) {
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

			// 動画変換するのまだ仮(;'∀')
			// ファイルチェック 動画ファイル
			if (! $data = $this->validateVideoFile($data, self::VIDEO_FILE_FIELD, $this->alias, 'mp4_id', 0)) {
				return false;
			}

			// ファイルチェック サムネイル
			if (! $data = $this->validateVideoFile($data, self::THUMBNAIL_FIELD, $this->alias, 'thumbnail_id', 1)) {
				return false;
			}

			// ステータスチェック
			if (!$this->Comment->validateByStatus($data, array('caller' => $this->name))) {
				$this->validationErrors = Hash::merge($this->validationErrors, $this->Comment->validationErrors);
				return false;
			}

			// ファイルの登録 動画ファイル
			$data = $this->saveVideoFile($data, self::VIDEO_FILE_FIELD, $this->alias, 'mp4_id', 0);

			// ファイルの登録 サムネイル
			$data = $this->saveVideoFile($data, self::THUMBNAIL_FIELD, $this->alias, 'thumbnail_id', 1);

			// 値をセット
			$this->set($data);

			//登録
			$video = $this->save(null, false);
			if (!$video) {
				// @codeCoverageIgnoreStart
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
				// @codeCoverageIgnoreEnd
			}
			//コメントの登録
			if ($this->Comment->data) {
				if (!$this->Comment->save(null, false)) {
					// @codeCoverageIgnoreStart
					throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
					// @codeCoverageIgnoreEnd
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
 * ファイルチェック
 * 共通化希望(*´ω｀)
 *
 * @param array $data received post data
 * @param string $field ファイルのフィールド名
 * @param string $modelAlias モデル名
 * @param string $colom セットするDBカラム名
 * @param int $index File inputのindex
 * @return mixed Array on success, false on error
 */
	public function validateVideoFile($data, $field, $modelAlias, $colom, $index = 0) {
		//ファイル更新の準備
		$data = $this->__readyUpdateFile($data, $field, $modelAlias, $colom, $index);

		//ファイル削除のvalidate
		if (isset($data['DeleteFile'][$index]['File']['id']) && $data['DeleteFile'][$index]['File']['id'] > 0) {
			if (! $deleteFile = $this->FileModel->validateDeletedFiles($data['DeleteFile'][$index]['File']['id'])) {
				$this->validationErrors = Hash::merge($this->validationErrors, $this->FileModel->validationErrors);
				return false;
			}
			$data['DeleteFile'][$index] = $deleteFile[0];
		}

		//ファイルのvalidate
		if (isset($data[$field])) {
			if (! $this->FileModel->validateFile($data[$field])) {
				$this->validationErrors = Hash::merge($this->validationErrors, $this->FileModel->validationErrors);
				return false;
			}
			if (! $this->FileModel->validateFileAssociated($data[$field])) {
				$this->validationErrors = Hash::merge($this->validationErrors, $this->FileModel->validationErrors);
				return false;
			}
		}

		return $data;
	}

/**
 * ファイル更新の準備
 * 共通化希望(*´ω｀)
 *
 * @param array $data received post data
 * @param string $field ファイルのフィールド名
 * @param string $modelAlias モデル名
 * @param string $colom セットするDBカラム名
 * @param int $index File inputのindex
 * @return mixed Array on success, false on error
 */
	private function __readyUpdateFile($data, $field, $modelAlias, $colom, $index = 0) {
		//ファイル更新は、今までのファイルを削除,新しいファイルを登録する
		//そのためここで、今までのファイルの削除準備をしている
		if (isset($data[$field]) && isset($data[$modelAlias][$colom]) && $data[$modelAlias][$colom] !== null) {
			$data['DeleteFile'][$index]['File'] = array(
				'id' => $data[$modelAlias][$colom]
			);
		}
		return $data;
	}

/**
 * ファイルの登録
 * 共通化希望(*´ω｀)
 *
 * @param array $data received post data
 * @param string $field ファイルのフィールド名
 * @param string $modelAlias 登録するモデル名
 * @param string $colom 登録するDBカラム名
 * @param int $index File inputのindex
 * @return mixed Array on success, false on error
 * @throws InternalErrorException
 */
	public function saveVideoFile($data, $field, $modelAlias, $colom, $index = 0) {
		//ファイル削除
		$data = $this->deleteFile($data, $modelAlias, $colom, $index);

		//ファイル登録
		if (isset($data[$field])) {
			// 新規作成
			$this->FileModel->create();

			// ファイル上書き = 上書きチェックON、今までのファイルあり
			if (isset($data['OverwriteFile'][$index]['File']['id']) && $data['OverwriteFile'][$index]['File']['id'] > 0 &&
				isset($data['DeleteFile'][$index]['File']['id']) && $data['DeleteFile'][$index]['File']['id'] > 0) {
				// 今までのファイルのslugを使う
				$data[$field]['File']['slug'] = $data['DeleteFile'][$index]['File']['slug'];
				// バグのため暫定対応(;'∀') https://github.com/NetCommons3/Files/issues/2
				$data[$field]['File']['original_name'] = $data['DeleteFile'][$index]['File']['slug'];
			}

			if (! $file = $this->FileModel->save(
				$data[$field],
				array('validate' => false, 'callbacks' => 'before')
			)) {
				// @codeCoverageIgnoreStart
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
				// @codeCoverageIgnoreEnd
			}
			if (! $this->FileModel->saveFileAssociated($file)) {
				// @codeCoverageIgnoreStart
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
				// @codeCoverageIgnoreEnd
			}
			$data[$field] = Hash::insert(
				$data[$field], '{s}.id', (int)$file[$this->FileModel->alias]['id']
			);
			$data[$modelAlias][$colom] = $data[$field][$this->FileModel->alias]['id'];
		}

		return $data;
	}

/**
 * ファイル削除
 * 共通化希望(*´ω｀)
 *
 * @param array $data received post data
 * @param string $modelAlias 登録するモデル名
 * @param string $colom 登録するDBカラム名
 * @param int $index File inputのindex
 * @return mixed Array on success, false on error
 * @throws InternalErrorException
 */
	public function deleteFile($data, $modelAlias, $colom, $index = 0) {
		if (isset($data['DeleteFile'][$index]['File']['id']) && $data['DeleteFile'][$index]['File']['id'] > 0) {

			//データ削除
			if (!$this->FileModel->deleteAll(['id' => $data['DeleteFile'][$index]['File']['id']], true, false)) {
				// @codeCoverageIgnoreStart
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
				// @codeCoverageIgnoreEnd
			}
			if (!$this->FileModel->deleteFileAssociated($data['DeleteFile'][$index]['File']['id'])) {
				// @codeCoverageIgnoreStart
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
				// @codeCoverageIgnoreEnd
			}

			// 暫定対応(;'∀') コメントアウトする。
			// 現在、path=フォルダなので、フォルダ削除になっている。2ファイルを1度にアップロードすると同じフォルダにアップロードされる。
			// 更新時に1ファイルだけアップロードすると、下記フォルダ削除によりもう一方のファイルが消える問題あり。
			//
			// 1ファイルのアップロード毎にフォルダが別になれば、下記フォルダ削除のままでも問題解消する。
			//ファイル削除
			//$folder = new Folder();
			//$folder->delete($data['DeleteFile'][$index]['File']['path']);

			$data[$modelAlias][$colom] = 0;
		}

		return $data;
	}
}
