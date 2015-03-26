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
		'Files.FileModel',		// FileUpload
		'Videos.Video',
		'Videos.VideoFrameSetting',
	);

/**
 * use components
 *
 * @var array
 */
	public $components = array(
		'Files.FileUpload',		// FileUpload
		'NetCommons.NetCommonsBlock',
		'NetCommons.NetCommonsFrame',
		'NetCommons.NetCommonsWorkflow',
		'NetCommons.NetCommonsRoomRole' => array(
			//コンテンツの権限設定
			'allowedActions' => array(
				'contentEditable' => array('edit', 'delete', 'add')
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
	}

/**
 * 詳細表示
 *
 * @return CakeResponse
 */
	public function view() {
	}

/**
 * 登録
 *
 * @return CakeResponse
 */
	public function add() {
		$this->__init();

		if ($this->request->isPost()) {
			if (!$status = $this->NetCommonsWorkflow->parseStatus()) {
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
				return;
			}

			//$this->__init();

			// 一覧へ
			$this->view = 'Videos/index';
		}
	}

/**
 * 編集
 *
 * @return CakeResponse
 */
	public function edit() {
		$this->__init();
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
 * @return array
 */
	private function __init() {
		$videoKey = isset($this->viewVars['videoKey']) ? $this->viewVars['videoKey'] : null;

		//取得
		if (!$video = $this->Video->getVideo(
			$videoKey,
			$this->viewVars['contentEditable']
		)) {
			// 登録データ作成
			$video = $this->Video->create(['key' => Security::hash('video' . mt_rand() . microtime(), 'md5')]);
		}

		$comments = $this->Comment->getComments(array(
			'plugin_key' => 'videos',
			//'content_key' => null,
			'content_key' => $video['Video']['key'],
		));

		$results['comments'] = $comments;
		$results['contentStatus'] = null;

		// ファイル取得 動画ファイル
		if (isset($video['Video']['mp4_id'])) {
			if ($file = $this->FileModel->find('first', array(
				'recursive' => -1,
				'conditions' => array(
					$this->FileModel->alias . '.id' => $video['Video']['mp4_id']
					//$this->FileModel->alias . '.id' => 21
				)
			))) {
				$results['videoFile'] = $file['File'];
			} else {
				$results['videoFile'] = null;
			}
		} else {
			$results['videoFile'] = null;
		}

		//ファイル取得 サムネイル
		if (isset($video['Video']['mp4_id'])) {
			if ($file = $this->FileModel->find('first', array(
				'recursive' => -1,
				'conditions' => array(
					$this->FileModel->alias . '.id' => $video['Video']['thumbnail_id']
					//$this->FileModel->alias . '.id' => 23
				)
			))) {
				$results['thumbnail'] = $file['File'];
			} else {
				$results['thumbnail'] = null;
			}
		} else {
			$results['thumbnail'] = null;
		}

		// キーをキャメル変換
		$results = $this->camelizeKeyRecursive($results);

		$this->set($results);
	}
}