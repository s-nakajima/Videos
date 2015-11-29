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
		'NetCommons.Token',
	);

/**
 * use components
 *
 * @var array
 */
	public $components = array(
		'ContentComments.ContentComments',
		'Cookie',
		'Paginator',						// ページャ
		//'NetCommons.NetCommonsRoomRole',	// パーミッション取得
	);

/**
 * beforeFilter
 *
 * @return void
 */
	public function beforeFilter() {
		// ゲストアクセスOKのアクションを設定
		$this->Auth->allow('tag');
		parent::beforeFilter();
	}

/**
 * 一覧表示
 *
 * @return void
 */
	public function index() {
		//条件
//		$conditions = array(
//			'FaqQuestion.faq_id' => $this->viewVars['faq']['id'],
//		);
//		if (isset($this->params['named']['category_id'])) {
//			$conditions['FaqQuestion.category_id'] = $this->params['named']['category_id'];
//		}
//
//		//取得
//		$faqQuestions = $this->FaqQuestion->getWorkflowContents('all', array(
//			'recursive' => 0,
//			'conditions' => $conditions
//		));
//		$this->set('faqQuestions', $faqQuestions);

		//条件
		$conditions = array(
			'Video.block_id' => Current::read('Block.id'),
		);

		//取得
		$videos = $this->Video->getWorkflowContents('all', array(
			'recursive' => 0,
			'conditions' => $conditions
		));
		$this->set('videos', $videos);
//var_dump($videos);

		// フレーム取得
		$conditions = array(
			$this->Frame->alias . '.key' => Current::read('Frame.key'),
		);
		$frame = $this->Frame->find('first', array(
			'recursive' => 0,
			'conditions' => $conditions,
		));
		$results['frame'] = $frame['Frame'];

		// 表示系(並び順、表示件数)の設定取得
//		$videoFrameSetting = $this->VideoFrameSetting->getVideoFrameSetting(true);
//
//		$results['videoFrameSetting'] = $videoFrameSetting['VideoFrameSetting'];

//		//ソート
//		$videoFrameSetting = $this->VideoFrameSetting->getVideoFrameSetting(true);
//		$order = $this->__order($videoFrameSetting);
//		$results['displayOrderPaginator'] = key($order) . '.' . $order[key($order)];
//
//		//表示件数
//		$limit = $this->_getNamed('limit');
//		if (!isset($limit)) {
//			$limit = $videoFrameSetting['VideoFrameSetting']['display_number'];
//		}
//
//		// 利用系(コメント利用、高く評価を利用等)の設定取得
//		$videoBlockSetting = $this->VideoBlockSetting->getVideoBlockSetting();
//		$results['videoBlockSetting'] = $videoBlockSetting['VideoBlockSetting'];
//
//		// 暫定対応しない(;'∀')
//		// blockテーブルのpublic_typeによって 表示・非表示する処理は、6/15以降に対応する
//
//		if (!empty($this->viewVars['blockId'])) {
//
//
//
//			// ワークフロー表示条件 取得
//			$conditions = $this->_getWorkflowConditions();
//
//
//			if ($extraConditions) {
//				$conditions = Hash::merge($conditions, $extraConditions);
//			}
//
//			$this->Paginator->settings = array(
//				$this->Video->alias => array(
//					'order' => $order,
//					'fields' => array(
//						'*',
//						'ContentCommentCnt.cnt',	// Behaviorでコンテンツコメント数取得
//					),
//					'conditions' => $conditions,
//					'limit' => $limit
//				)
//			);
//			$results['videos'] = $this->Paginator->paginate($this->Video->alias);
//		}

		// キーをキャメル変換
		//$results = $this->camelizeKeyRecursive($results);


		// 一覧取得
//		$results = $this->__list();

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
		$this->set('listTitle', __d('tags', 'Tag') . ':' . $tag['Tag']['name']);

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
		// フレームKeyなしはアクセスさせない
		if (empty($videoKey)) {
			$this->throwBadRequest();
			return false;
		}

		// ワークフロー表示条件 取得
		$conditions = $this->_getWorkflowConditions($videoKey);

		$fields = array(
			'*',
			'ContentCommentCnt.cnt',	// Behaviorでコンテンツコメント数取得
		);
		//動画の取得
		$video = $this->Video->getVideo($conditions, $fields);
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
		$videoBlockSetting = $this->VideoBlockSetting->getVideoBlockSetting();
		$results['videoBlockSetting'] = $videoBlockSetting['VideoBlockSetting'];

		// コメントを利用する
		if ($videoBlockSetting['VideoBlockSetting']['use_comment']) {
			// コンテンツコメントの取得
			$contentComments = $this->ContentComment->getContentComments(array(
				'block_key' => $this->viewVars['blockKey'],
				'plugin_key' => $this->request->params['plugin'],
				'content_key' => $video['Video']['key'],
			));

			$results['contentComments'] = $contentComments;
		}

		// クッキー対応
		$cookie = $this->Cookie->read('video_history');
		$cookieArray = explode(':', $cookie);

		if (! in_array($video['Video']['id'], $cookieArray, true)) {
			//再生回数 + 1 で更新
			$playNumber = $this->Video->updateCountUp($video);
			$results['video']['Video']['play_number'] = $playNumber;

			// cookie value = コンテンツid & 区切り文字
			$cookie = $cookie . $video['Video']['id'] . ':';

			// CookieComponentは3501byte～3550byte位をwriteしても、cookieに書き込んでくれなくなる。
			// このcookieは、動画の視聴回数の抑制に使っていて、そこまで重要ではない＆1時間で消えるので、
			// 3500byteを超えたら、cookieの内容をクリアする。
			if (strlen($cookie) > 3500) {
				$cookie = $video['Video']['id'] . ':';
			}
			// アクセス情報を記録
			$this->Cookie->write('video_history', $cookie, false, '1 hour');
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
//		$videoFrameSetting = $this->VideoFrameSetting->getVideoFrameSetting(
//			$this->viewVars['frameKey'],
//			$this->viewVars['roomId']
//		);
		$videoFrameSetting = $this->VideoFrameSetting->getVideoFrameSetting(true);

		$results['videoFrameSetting'] = $videoFrameSetting['VideoFrameSetting'];

		// フレーム取得
		$conditions = array(
			$this->Frame->alias . '.key' => Current::read('Frame.key'),
		);
		$frame = $this->Frame->find('first', array(
			'recursive' => 0,
			'conditions' => $conditions,
		));
		$results['frame'] = $frame['Frame'];

		//ソート
		$order = $this->__order($videoFrameSetting);
		$results['displayOrderPaginator'] = key($order) . '.' . $order[key($order)];

		//表示件数
		$limit = $this->_getNamed('limit');
		if (!isset($limit)) {
			$limit = $videoFrameSetting['VideoFrameSetting']['display_number'];
		}

		// 利用系(コメント利用、高く評価を利用等)の設定取得
		$videoBlockSetting = $this->VideoBlockSetting->getVideoBlockSetting();
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
					'order' => $order,
					'fields' => array(
						'*',
						'ContentCommentCnt.cnt',	// Behaviorでコンテンツコメント数取得
					),
					'conditions' => $conditions,
					'limit' => $limit
				)
			);
			$results['videos'] = $this->Paginator->paginate($this->Video->alias);
		}

		// キーをキャメル変換
		//$results = $this->camelizeKeyRecursive($results);

		return $results;
	}

/**
 * ソート条件 取得
 *
 * @param array $videoFrameSetting videoFrameSetting
 * @return array ソート条件
 */
	private function __order($videoFrameSetting) {
		$sort = $this->_getNamed('sort');
		$direction = $this->_getNamed('direction');

		//ソート
		if (isset($sort) && isset($direction)) {
			$order = array($sort => $direction);
		} else {
			$displayOrder = $videoFrameSetting['VideoFrameSetting']['display_order'];
			if ($displayOrder == VideoFrameSetting::DISPLAY_ORDER_NEW) {
				$order = array('Video.created' => 'desc');
			} elseif ($displayOrder == VideoFrameSetting::DISPLAY_ORDER_TITLE) {
				$order = array('Video.title' => 'asc');
			} elseif ($displayOrder == VideoFrameSetting::DISPLAY_ORDER_PLAY) {
				$order = array('Video.play_number' => 'desc');
				// 暫定対応(;'∀') 評価順はLikesプラグインが対応していないので、対応を先送りする
				//} elseif ($displayOrder == VideoFrameSetting::DISPLAY_ORDER_LIKE) {
				//	$order = array('Video.like_counts' => 'desc');
			}
		}

		return $order;
	}
}