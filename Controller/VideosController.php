<?php
/**
 * 動画表示系 Controller
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
		'ContentComments.ContentComment',	// コンテンツコメント
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
		'NetCommons.Date',					// 詳細日付表示
	);

/**
 * use components
 *
 * @var array
 */
	public $components = array(
		'ContentComments.ContentComments',
		'Paginator',						// ページャ
		//'NetCommons.NetCommonsBlock',
		'NetCommons.NetCommonsFrame',		// frameId, frameKey等を自動セット
		'NetCommons.NetCommonsRoomRole' => array(
			//コンテンツの権限設定
			'allowedActions' => array(
				'contentEditable' => array()
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
		$conditions = $this->_getWorkflowConditions($videoKey);

		//動画の取得
		$video = $this->Video->getVideo($conditions);
		$results['video'] = $video;

		// 一覧条件で再取得
		$workflowConditions = $this->_getWorkflowConditions();

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
			$conditions = $this->_getWorkflowConditions();

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

}