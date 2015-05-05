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
 * use model
 *
 * @var array
 */
	public $uses = array(
		'Comments.Comment',
		'ContentComments.ContentComment',
		'Files.FileModel',		// FileUpload
		'Frames.Frame',
		'Videos.Video',
		'Videos.VideoFrameSetting',
		'Videos.VideoBlockSetting',
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
					'add'
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
 * @param int $frameId frames.id
 * @param int $displayOrder video_frame_setting.display_order
 * @param int $displayNumber video_frame_setting.display_number
 * @return void
 */
	public function index($frameId, $displayOrder = null, $displayNumber = null) {
		// 登録・更新後の戻りURL設定 暫定対応
		CakeSession::write('backUrl', Router::url('', true));

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

		// block.id
		if (empty($this->viewVars['blockId'])) {
			$blockId = 'null';
		} else {
			$blockId = $this->viewVars['blockId'];
		}
		// ページャーで複数動画取得
		$conditions = array(
			'Block.id = ' . $blockId,
			'Block.id = ' . $this->Video->alias . '.block_id',
			'Block.language_id = ' . $this->viewVars['languageId'],
			'Block.room_id = ' . $this->viewVars['roomId'],
		);
		if (! $this->viewVars['contentEditable']) {
			$conditions[] = $this->Video->alias . '.status = ' . NetCommonsBlockComponent::STATUS_PUBLISHED;
		}

		$this->Paginator->settings = array(
			$this->Video->alias => array(
				'order' => $this->Video->alias . '.id DESC',
				'conditions' => $conditions,
				'limit' => $results['displayNumber']
			)
		);
		$results['videos'] = $this->Paginator->paginate($this->Video->alias);

		// キーをキャメル変換
		$results = $this->camelizeKeyRecursive($results);

		$this->set($results);
	}

/**
 * 詳細表示
 *
 * @param int $frameId frames.id
 * @param int $videoKey videos.key
 * @return CakeResponse
 */
	public function view($frameId, $videoKey = null) {
		//動画の取得
		$video = $this->Video->getVideo(
			$videoKey,
			$this->viewVars['languageId'],
			$this->viewVars['contentEditable']
		);
		$results['video'] = $video;

		if ($this->request->isPost()) {
			// コメントする
			if (!$this->ContentComments->comment('videos', $video['Video']['key'])) {
				$this->throwBadRequest();
				return;
			}
		}

		//関連動画の取得
		$relatedVideos = $this->Video->getVideos(
			$video['Video']['created_user'],
			$this->viewVars['blockId'],
			$this->viewVars['contentEditable']
		);
		$results['relatedVideos'] = $relatedVideos;

		// 表示系(並び順、表示件数)の設定取得
		$videoFrameSetting = $this->VideoFrameSetting->getVideoFrameSetting(
			$this->viewVars['frameKey'],
			$this->viewVars['roomId']
		);
		$results['videoFrameSetting'] = $videoFrameSetting['VideoFrameSetting'];

		// 利用系(コメント利用、高く評価を利用等)の設定取得
		$videoBlockSetting = $this->VideoBlockSetting->getVideoBlockSetting(
			$this->viewVars['blockKey'],
			$this->viewVars['roomId']
		);
		$results['videoBlockSetting'] = $videoBlockSetting['VideoBlockSetting'];

		// コンテンツコメントの取得
		$contentComments = $this->ContentComment->getContentComments(array(
			'block_key' => $this->viewVars['blockKey'],
			'plugin_key' => 'videos',
			'content_key' => $video['Video']['key'],
		));
		$results['contentComments'] = $contentComments;

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

			$data = $this->data;

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

			// 登録データ作成
			$video = $this->Video->create(['key' => Security::hash('video' . mt_rand() . microtime(), 'md5')]);
			$data = Hash::merge(
				$video,
				$data,
				array($this->Video->alias => array(
					'status' => $status,
					'block_id' => $this->viewVars['blockId'],
				))
			);

			// 登録
			$video = $this->Video->saveVideo($data, false);
			if (!$this->handleValidationError($this->Video->validationErrors)) {
				$this->log($this->validationErrors, 'debug');
			}

			if (! $this->request->is('ajax')) {
				// 一覧へ
				$this->redirectBackUrl();
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

			$data = $this->data;

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

			//取得
			$video = $this->Video->getVideo(
				$videoKey,
				$this->viewVars['languageId'],
				$this->viewVars['contentEditable']
			);

			// 更新データ作成
			$data = Hash::merge(
				$video,
				$data,
				array($this->Video->alias => array(
					'id' => $results['video']['id'],
					'status' => $status,
				))
			);

			// 登録
			$this->Video->saveVideo($data, false);
			if (!$this->handleValidationError($this->Video->validationErrors)) {
				$this->log($this->validationErrors, 'debug');
			}

			if (! $this->request->is('ajax')) {
				// 一覧へ
				$this->redirectBackUrl();
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
			$comments = $this->Comment->getComments(array(
				'plugin_key' => 'videos',
				'content_key' => null,
			));
		} else {
			//取得
			$video = $this->Video->getVideo(
				$videoKey,
				$this->viewVars['languageId'],
				$this->viewVars['contentEditable']
			);
			$results['video'] = $video['Video'];

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
}