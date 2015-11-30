<?php
/**
 * 動画編集系 Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('VideosAppController', 'Videos.Controller');
App::uses('VideosAppModel', 'Videos.Model');
App::uses('Video', 'Videos.Model');

/**
 * 動画編集系 Controller
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Videos\Controller
 */
class VideosEditController extends VideosAppController {

/**
 * use model
 *
 * @var array
 */
	public $uses = array(
//		'Comments.Comment',					// 承認コメント
		'Files.FileModel',					// FileUpload
		'Videos.Video',
	);

/**
 * use components
 *
 * @var array
 */
	public $components = array(
		'Files.FileUpload',					// FileUpload
		'NetCommons.Permission' => array(
			//アクセスの権限
			'allow' => array(
				'add,edit,delete' => 'content_creatable',
			),
		),
//		'NetCommons.NetCommonsWorkflow',
	);

/**
 * use helpers
 *
 * @var array
 */
	public $helpers = array(
		'Workflow.Workflow',
	);

/**
 * 登録
 *
 * @return CakeResponse
 */
	public function add() {
		if ($this->request->isPost()) {

			//登録処理
			$data = $this->data;
			$data['Video']['status'] = $this->Workflow->parseStatus();
			unset($data['Video']['id']);

			// 保存dataの準備
//			$data = $this->__readySaveData($this->data);
			$data = $this->__readySaveData($data);

//			// 登録データ作成
//			$video = $this->Video->create();
//			$data = Hash::merge(
//				$video,
//				$data,
//				array($this->Video->alias => array(
//					'status' => $status,
//					'block_id' => $this->viewVars['blockId'],
//					'language_id' => $this->viewVars['languageId'],
//				)),
//				array($this->Comment->alias => array(
//					'block_key' => $this->viewVars['blockKey'],
//				))
//			);

			if (VideosAppModel::isFfmpegEnable()) {
				// 登録
				if ($this->Video->addSaveVideo($data)) {
					$this->redirect(NetCommonsUrl::backToPageUrl());
					return;
				}
			} else {
				// 登録 動画を自動変換しない
				if ($this->Video->addNoConvertSaveVideo($data)) {
					$this->redirect(NetCommonsUrl::backToPageUrl());
					return;
				}
			}

			$this->NetCommons->handleValidationError($this->Video->validationErrors);

//			// 正常時
//			if ($this->handleValidationError($this->Video->validationErrors)) {
//				if (! $this->request->is('ajax')) {
//					// 一覧へ
//					$this->redirect('/videos/videos/index/' . $this->viewVars['frameId']);
//				}
//			}

		} else {
			//表示処理
			$this->request->data = Hash::merge($this->request->data,
				$this->Video->create()
			);
			$this->request->data['Frame'] = Current::read('Frame');
			$this->request->data['Block'] = Current::read('Block');


		}


		$results['video'] = null;

//			$comments = $this->Comment->getComments(array(
//				'plugin_key' => $this->request->params['plugin'],
//				'content_key' => null,
//			));

//			$results['comments'] = $comments;
//			$results['content_status'] = null;

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
		//$results = $this->camelizeKeyRecursive($results);
		$this->set($results);
	}

/**
 * 編集
 *
 * @param int $frameId frames.id
 * @param int $videoKey videos.key
 * @return CakeResponse
 */
	public function edit($frameId, $videoKey = null) {
//		$this->view = 'edit';
//
//		$bbsArticleKey = $this->params['pass'][1];
//		if ($this->request->isPut()) {
//			$bbsArticleKey = $this->data['BbsArticle']['key'];
//		}
//
//		$bbsArticle = $this->BbsArticle->getWorkflowContents('first', array(
//			'recursive' => 0,
//			'conditions' => array(
//				$this->BbsArticle->alias . '.bbs_id' => $this->viewVars['bbs']['id'],
//				$this->BbsArticle->alias . '.key' => $bbsArticleKey
//			)
//		));
//
//		//掲示板の場合は、削除権限と同じ条件とする
//		if (! $this->BbsArticle->canDeleteWorkflowContent($bbsArticle)) {
//			$this->throwBadRequest();
//			return false;
//		}
//
//		if ($this->request->isPut()) {
//			$data = $this->data;
//			$data['BbsArticle']['status'] = $this->Workflow->parseStatus();
//			unset($data['BbsArticle']['id']);
//
//			if ($bbsArticle = $this->BbsArticle->saveBbsArticle($data)) {
//				$url = NetCommonsUrl::actionUrl(array(
//					'controller' => $this->params['controller'],
//					'action' => 'view',
//					'block_id' => $this->data['Block']['id'],
//					'frame_id' => $this->data['Frame']['id'],
//					'key' => $bbsArticle['BbsArticle']['key']
//				));
//				$this->redirect($url);
//				return;
//			}
//			$this->NetCommons->handleValidationError($this->BbsArticle->validationErrors);
//
//		} else {
//			$this->request->data = $bbsArticle;
//			if (! $this->request->data) {
//				$this->throwBadRequest();
//				return false;
//			}
//			$this->request->data['Frame'] = Current::read('Frame');
//			$this->request->data['Block'] = Current::read('Block');
//
//		}
//
//		$comments = $this->BbsArticle->getCommentsByContentKey($this->request->data['BbsArticle']['key']);
//		$this->set('comments', $comments);

		//動画の取得
		$video = $this->Video->getWorkflowContents('first', array(
			'recursive' => 1,
//			'fields' => array(
//				'*',
//				'ContentCommentCnt.cnt',	// Behaviorでコンテンツコメント数取得
//			),
			'conditions' => array(
				$this->Video->alias . '.key' => $videoKey
			)
		));
		$this->set('video', $video);


		//掲示板の場合は、削除権限と同じ条件とする(動画まねてみた。あってるのか？)
		if (! $this->Video->canDeleteWorkflowContent($video)) {
			$this->throwBadRequest();
			return false;
		}

		if ($this->request->isPut()) {

			$data = $this->data;
			$data['Video']['status'] = $this->Workflow->parseStatus();
			unset($data['Video']['id']);

			// 保存dataの準備
			$data = $this->__readySaveData($data);

			// 登録（ワークフロー対応のため、編集でも常にinsert）
			if ($video = $this->Video->editSaveVideo($data)) {
				$url = NetCommonsUrl::actionUrl(array(
					'controller' => $this->params['controller'],
					'action' => 'view',
					'block_id' => $this->data['Block']['id'],
					'frame_id' => $this->data['Frame']['id'],
					'key' => $video['Video']['key']
				));
				$this->redirect($url);
				return;
			}
			$this->NetCommons->handleValidationError($this->Video->validationErrors);

		} else {
			$this->request->data = $video;
			if (! $this->request->data) {
				$this->throwBadRequest();
				return false;
			}
			$this->request->data['Frame'] = Current::read('Frame');
			$this->request->data['Block'] = Current::read('Block');

		}

//		$comments = $this->BbsArticle->getCommentsByContentKey($this->request->data['BbsArticle']['key']);
//		$this->set('comments', $comments);

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
		$this->set($results);



		// フレームKeyなしはアクセスさせない
//		if (empty($videoKey)) {
//			$this->throwBadRequest();
//			return;
//		}

//		$results = $this->__init($videoKey);
//		$this->set($results);
//
//		if ($this->request->isPost()) {
//			if (!$status = $this->NetCommonsWorkflow->parseStatus()) {
//				$this->throwBadRequest();
//				return;
//			}

//			// 保存dataの準備
//			$data = $this->__readySaveData($this->data);

//			// ワークフロー表示条件 取得
//			$conditions = $this->_getWorkflowConditions($videoKey);
//
//			//動画の取得
//			$video = $this->Video->getVideo($conditions);

//			// ワークフロー対応 idを取り除く
//			unset($video['Video']['id']);

//			// 更新データ作成
//			$data = Hash::merge(
//				$video,
//				$data,
//				array($this->Video->alias => array(
//					'status' => $status,
//				)),
//				array($this->Comment->alias => array(
//					'block_key' => $this->viewVars['blockKey'],
//				))
//			);

//			// 登録（ワークフロー対応のため、編集でも常にinsert）
//			$this->Video->editSaveVideo($data);
//			// 正常時
//			if ($this->handleValidationError($this->Video->validationErrors)) {
//				if (! $this->request->is('ajax')) {
//					// 一覧へ
//					$this->redirect('/videos/videos/index/' . $this->viewVars['frameId']);
//				}
//			}
//		}
	}

/**
 * 削除
 *
 * @return CakeResponse
 */
	public function delete() {
		if ($this->request->isDelete()) {
			// 削除
			if (!$this->Video->deleteVideo($this->data)) {
				$this->throwBadRequest();
				return;
			}

			if (!$this->request->is('ajax')) {
				// 一覧へ
				$this->redirect('/videos/videos/index/' . $this->viewVars['frameId']);
			}
			return;
		}
		$this->throwBadRequest();
	}

/**
 * 登録・編集 初期値設定
 *
 * @param int $videoKey videos.key
 * @return array
 */
	private function __init($videoKey = null) {
		if (empty($videoKey)) {
			$results['video'] = null;

//			$comments = $this->Comment->getComments(array(
//				'plugin_key' => $this->request->params['plugin'],
//				'content_key' => null,
//			));
		} else {

			// ワークフロー表示条件 取得
			$conditions = $this->_getWorkflowConditions($videoKey);

			//取得
			$video = $this->Video->getVideo($conditions);

			$results['video'] = $video['Video'];

			if ($this->request->isGet()) {
				// タグ対応
				$this->request->data['Tag'] = isset($video['Tag']) ? $video['Tag'] : array();
			}

			$comments = $this->Comment->getComments(array(
				'plugin_key' => $this->request->params['plugin'],
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
		//$results = $this->camelizeKeyRecursive($results);

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

}