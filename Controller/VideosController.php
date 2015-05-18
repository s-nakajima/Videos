<?php
/**
 * Videos Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('VideosAppController', 'Videos.Controller');

/**
 * Videos Controller
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Videos\Controller
 */
class VideosController extends VideosAppController {

/**
 * 関連動画 もっと見る start limit
 *
 * @var int
 */
	const START_LIMIT_RELATED_VIDEO = 5;

/**
 * 関連動画 もっと見る max limit
 *
 * @var int
 */
	const MAX_LIMIT_RELATED_VIDEO = 100;

/**
 * use model
 *
 * @var array
 */
	public $uses = array(
		'Blocks.Block',
		'Comments.Comment',
		'ContentComments.ContentComment',
		'Files.FileModel',		// FileUpload
		'Frames.Frame',
		'Videos.Video',
		'Videos.VideoBlockSetting',
		'Videos.VideoFrameSetting',
	);

/**
 * use helpers
 *
 * @var array
 */
	public $helpers = array(
		'NetCommons.Date',
	);

/**
 * use components
 *
 * @var array
 */
	public $components = array(
		'ContentComments.ContentComments',
		'Paginator',			// ページャ
		'Files.FileUpload',		// FileUpload
		'NetCommons.NetCommonsBlock',
		'NetCommons.NetCommonsFrame',
		'NetCommons.NetCommonsWorkflow',
		'NetCommons.NetCommonsRoomRole' => array(
			//コンテンツの権限設定
			'allowedActions' => array(
				'contentEditable' => array(
					'edit',
					'delete',
					'add',
				)
			),
		),
	);

/**
 * beforeFilter
 *
 * @return void
 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow();
	}

/**
 * 一覧表示
 *
 * @return void
 */
	public function index() {
		// 一覧取得
		$results = $this->__list();

		$this->set($results);
	}

/**
 * tag別一覧
 *
 * @return void
 */
	public function tag() {
		// indexとのちがいはtagIdでの絞り込みだけ
		$tagId = $this->_getNamed('id', 0);

		// カテゴリ名をタイトルに
		$tag = $this->Video->getTagByTagId($tagId);
		$this->set('listTitle', __d('blogs', 'Tag') . ':' . $tag['Tag']['name']);

		$conditions = array(
			'Tag.id' => $tagId // これを有効にするにはentry_tag_linkもJOINして検索か。
		);

		// 一覧取得
		$results = $this->__list($conditions);

		$this->set($results);

		// 一覧画面表示
		$this->render('index');
	}

/**
 * 詳細表示
 *
 * @param int $frameId frames.id
 * @param int $videoKey videos.key
 * @return CakeResponse
 */
	public function view($frameId, $videoKey = null) {
		// ワークフロー表示条件 取得
		$conditions = $this->__getWorkflowConditions($videoKey);

		//動画の取得
		$video = $this->Video->getVideo($conditions);
		$results['video'] = $video;

		// 一覧条件で再取得
		$workflowConditions = $this->__getWorkflowConditions();

		//関連動画の取得条件
		$conditions = array(
			$this->Video->alias . '.created_user' => $video['Video']['created_user'],
			'NOT' => array(
				$this->Video->alias . '.id' => $video['Video']['id'],
			),
		);
		$conditions = Hash::merge($workflowConditions, $conditions);

		//関連動画の取得
		$relatedVideos = $this->Video->getVideos($conditions);
		$results['relatedVideos'] = $relatedVideos;

		// 利用系(コメント利用、高く評価を利用等)の設定取得
		$videoBlockSetting = $this->VideoBlockSetting->getVideoBlockSetting(
			$this->viewVars['blockKey'],
			$this->viewVars['roomId']
		);
		$results['videoBlockSetting'] = $videoBlockSetting['VideoBlockSetting'];

		// コメントを利用する
		if ($videoBlockSetting['VideoBlockSetting']['use_comment']) {
			if ($this->request->isPost()) {
				// コメントする
				if (!$this->ContentComments->comment('videos', $video['Video']['key'], $videoBlockSetting['VideoBlockSetting']['comment_agree'])) {
					$this->throwBadRequest();
					return;
				}
			}

			// コンテンツコメントの取得
			$contentComments = $this->ContentComment->getContentComments(array(
				'block_key' => $this->viewVars['blockKey'],
				'plugin_key' => 'videos',
				'content_key' => $video['Video']['key'],
			));
			$results['contentComments'] = $contentComments;
		}

		// キーをキャメル変換
		$results = $this->camelizeKeyRecursive($results);

		$this->set($results);
	}

/**
 * 登録
 *
 * @return CakeResponse
 */
	public function add() {
		if ($this->request->isGet()) {
			$results = $this->__init();
			$this->set($results);
		}

		if ($this->request->isPost()) {
			if (!$status = $this->NetCommonsWorkflow->parseStatus()) {
				$this->throwBadRequest();
				return;
			}

			// 保存dataの準備
			$data = $this->__readySaveData($this->data);

			// 登録データ作成
			$video = $this->Video->create();
			$data = Hash::merge(
				$video,
				$data,
				array($this->Video->alias => array(
					'status' => $status,
					'block_id' => $this->viewVars['blockId'],
					'language_id' => $this->viewVars['languageId'],
				))
			);

			// 登録
			$this->Video->saveVideo($data, false);
			if (!$this->handleValidationError($this->Video->validationErrors)) {
				$this->log($this->validationErrors, 'debug');
			}

			if (! $this->request->is('ajax')) {
				// 一覧へ
				$this->redirect('/videos/videos/index/' . $this->viewVars['frameId']);
			}
		}
	}

/**
 * 編集
 *
 * @param int $frameId frames.id
 * @param int $videoKey videos.key
 * @return CakeResponse
 */
	public function edit($frameId, $videoKey = null) {
		$results = $this->__init($videoKey);
		$this->set($results);

		if ($this->request->isPost()) {
			if (!$status = $this->NetCommonsWorkflow->parseStatus()) {
				$this->throwBadRequest();
				return;
			}

			// 保存dataの準備
			$data = $this->__readySaveData($this->data);

			// ワークフロー表示条件 取得
			$conditions = $this->__getWorkflowConditions($videoKey);

			//動画の取得
			$video = $this->Video->getVideo($conditions);

			// ワークフロー対応 idを取り除く
			unset($video['Video']['id']);

			// 更新データ作成
			$data = Hash::merge(
				$video,
				$data,
				array($this->Video->alias => array(
					'status' => $status,
				))
			);

			// 登録（ワークフロー対応のため、編集でも常にinsert）
			$this->Video->saveVideo($data, false);
			if (!$this->handleValidationError($this->Video->validationErrors)) {
				$this->log($this->validationErrors, 'debug');
			}

			if (! $this->request->is('ajax')) {
				// 一覧へ
				$this->redirect('/videos/videos/index/' . $this->viewVars['frameId']);
			}
		}
	}

/**
 * 削除
 *
 * @return CakeResponse
 */
	public function delete() {
	}

/**
 * 初期値設定
 *
 * @param int $videoKey videos.key
 * @return array
 */
	private function __init($videoKey = null) {
		if (empty($videoKey)) {
			$results['video'] = null;

			// タグ対応
			$this->request->data['Tag'] = array();

			$comments = $this->Comment->getComments(array(
				'plugin_key' => 'videos',
				'content_key' => null,
			));
		} else {

			// ワークフロー表示条件 取得
			$conditions = $this->__getWorkflowConditions($videoKey);

			//取得
			$video = $this->Video->getVideo($conditions);

			$results['video'] = $video['Video'];

			// タグ対応
			$this->request->data['Tag'] = isset($video['Tag']) ? $video['Tag'] : array();

			$comments = $this->Comment->getComments(array(
				'plugin_key' => 'videos',
				'content_key' => $video['Video']['key'],
			));
		}

		$results['comments'] = $comments;
		$results['contentStatus'] = null;

		// ファイル取得 動画ファイル
		$results['videoFile'] = null;
		if (isset($video['Video']['mp4_id'])) {
			if ($file = $this->FileModel->find('first', array(
				'recursive' => -1,
				'conditions' => array(
					$this->FileModel->alias . '.id' => $video['Video']['mp4_id']
				)
			))) {
				$results['videoFile'] = $file['File'];
			}
		}

		//ファイル取得 サムネイル
		$results['thumbnail'] = null;
		if (isset($video['Video']['thumbnail_id'])) {
			if ($file = $this->FileModel->find('first', array(
				'recursive' => -1,
				'conditions' => array(
					$this->FileModel->alias . '.id' => $video['Video']['thumbnail_id']
				)
			))) {
				$results['thumbnail'] = $file['File'];
			}
		}

		// キーをキャメル変換
		$results = $this->camelizeKeyRecursive($results);

		return $results;
	}

/**
 * 保存dataの準備
 *
 * @param array $data data
 * @return array data
 */
	private function __readySaveData($data) {
		// 動画ファイル
		$data[Video::VIDEO_FILE_FIELD]['File'] = $this->FileUpload->upload($this->Video->alias, Video::VIDEO_FILE_FIELD);
		if (! $data[Video::VIDEO_FILE_FIELD]['File']) {
			unset($data[Video::VIDEO_FILE_FIELD]);
		}

		// サムネイル
		$data[Video::THUMBNAIL_FIELD]['File'] = $this->FileUpload->upload($this->Video->alias, Video::THUMBNAIL_FIELD);
		if (! $data[Video::THUMBNAIL_FIELD]['File']) {
			unset($data[Video::THUMBNAIL_FIELD]);
		}
		return $data;
	}

/**
 * 一覧取得
 *
 * @param array $extraConditions 追加conditions
 * @return array 動画一覧
 */
	private function __list($extraConditions = array()) {
		// 表示系(並び順、表示件数)の設定取得
		$videoFrameSetting = $this->VideoFrameSetting->getVideoFrameSetting(
			$this->viewVars['frameKey'],
			$this->viewVars['roomId']
		);
		$results['videoFrameSetting'] = $videoFrameSetting['VideoFrameSetting'];

		// フレーム取得
		$conditions = array(
			$this->Frame->alias . '.key' => $this->viewVars['frameKey'],
		);
		$frame = $this->Frame->find('first', array(
			'recursive' => 0,
			'conditions' => $conditions,
		));
		$results['frame'] = $frame['Frame'];

		$displayOrder = $this->_getNamed('display_order');
		$displayNumber = $this->_getNamed('display_number');

		// 並び順
		if (empty($displayOrder)) {
			$results['displayOrder'] = $videoFrameSetting['VideoFrameSetting']['display_order'];
		} else {
			$results['displayOrder'] = $displayOrder;
		}
		// 表示件数
		if (empty($displayNumber)) {
			$results['displayNumber'] = $videoFrameSetting['VideoFrameSetting']['display_number'];
		} else {
			$results['displayNumber'] = $displayNumber;
		}

		// 利用系(コメント利用、高く評価を利用等)の設定取得
		$videoBlockSetting = $this->VideoBlockSetting->getVideoBlockSetting(
			$this->viewVars['blockKey'],
			$this->viewVars['roomId']
		);
		$results['videoBlockSetting'] = $videoBlockSetting['VideoBlockSetting'];

		// 暫定対応しない(;'∀')
		// blockテーブルのpublic_typeによって 表示・非表示する処理は、6/15以降に対応する

		if (!empty($this->viewVars['blockId'])) {

			// ワークフロー表示条件 取得
			$conditions = $this->__getWorkflowConditions();

			if ($extraConditions) {
				$conditions = Hash::merge($conditions, $extraConditions);
			}

			$this->Paginator->settings = array(
				$this->Video->alias => array(
					'order' => $this->Video->alias . '.id DESC',
					'conditions' => $conditions,
					'limit' => $results['displayNumber']
				)
			);
			$results['videos'] = $this->Paginator->paginate($this->Video->alias);
		}

		// キーをキャメル変換
		$results = $this->camelizeKeyRecursive($results);

		return $results;
	}

/**
 * ワークフロー表示条件 取得
 *
 * @param string $videoKey Videos.key
 * @return array Conditions data
 */
	private function __getWorkflowConditions($videoKey = null) {
		//ゲスト
		$activeConditions = array(
			$this->Video->alias . '.is_active' => true,
		);
		$latestConditons = array();

		//コンテンツ編集 許可あり
		if ($this->viewVars['contentEditable']) {
			$activeConditions = array();
			$latestConditons = array(
				$this->Video->alias . '.is_latest' => true,
			);

			//コンテンツ作成 許可あり
		} elseif ($this->viewVars['contentCreatable']) {
			$activeConditions = array(
				$this->Video->alias . '.is_active' => true,
				$this->Video->alias . '.created_user !=' => (int)$this->viewVars['userId'],
			);
			$latestConditons = array(
				$this->Video->alias . '.is_latest' => true,
				$this->Video->alias . '.created_user' => (int)$this->viewVars['userId'],
			);
		}

		$conditions = array(
			'Block.id = ' . $this->viewVars['blockId'],
			'Block.language_id = ' . $this->viewVars['languageId'],
			'Block.room_id = ' . $this->viewVars['roomId'],
			'OR' => array($activeConditions, $latestConditons)
		);

		// 動画1件 取得条件
		if (!empty($videoKey)) {
			$conditions[$this->Video->alias . '.key'] = $videoKey;
		}

		return $conditions;
	}

}