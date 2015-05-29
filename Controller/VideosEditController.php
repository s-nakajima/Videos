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
		'Comments.Comment',					// 承認コメント
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
		'NetCommons.NetCommonsWorkflow',
		'NetCommons.NetCommonsRoomRole' => array(
			//コンテンツの権限設定
			'allowedActions' => array(
				'contentEditable' => array('add', 'edit', 'delete'),
				'contentCreatable' => array('add', 'edit', 'delete'),
			),
		),
	);

/**
 * beforeFilter
 *
 * @return void
 */
	public function beforeFilter() {
		// 権限判定が必要
		$this->Auth->deny('add', 'edit', 'delete');
		parent::beforeFilter();
	}

/**
 * 登録
 *
 * @return CakeResponse
 */
	public function add() {
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

			if (Video::FFMPEG_ENABLE) {
				// 登録
				$this->Video->addSaveVideo($data, $this->viewVars['roomId']);
			} else {
				// 登録 動画を自動変換しない
				$this->Video->addNoConvertSaveVideo($data);
			}
			if (!$this->handleValidationError($this->Video->validationErrors)) {
				// エラー時、なにもしない

				// 正常
			} else {
				if (! $this->request->is('ajax')) {
					// 一覧へ
					$this->redirect('/videos/videos/index/' . $this->viewVars['frameId']);
				}
			}
		}

		$results = $this->__init();
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
		// フレームKeyなしはアクセスさせない
		if (empty($videoKey)) {
			$this->throwBadRequest();
			return false;
		}

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
			$conditions = $this->_getWorkflowConditions($videoKey);

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
			$this->Video->editSaveVideo($data, $this->viewVars['roomId']);
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
 * 登録・編集 初期値設定
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
				'plugin_key' => $this->request->params['plugin'],
				'content_key' => null,
			));
		} else {

			// ワークフロー表示条件 取得
			$conditions = $this->_getWorkflowConditions($videoKey);

			//取得
			$video = $this->Video->getVideo($conditions);

			$results['video'] = $video['Video'];

			// タグ対応
			$this->request->data['Tag'] = isset($video['Tag']) ? $video['Tag'] : array();

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

}