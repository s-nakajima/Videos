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
		'ContentComments.ContentComment',	// コンテンツコメント
		'Videos.Video',
		'Videos.VideoBlockSetting',
		'Videos.VideoFrameSetting',
	);

/**
 * use helpers
 *
 * @var array
 * @see NetCommonsAppController::$helpers
 */
	public $helpers = array(
		'ContentComments.ContentComment' => array(
			'viewVarsKey' => array(
				'contentKey' => 'video.Video.key',
				'useComment' => 'videoBlockSetting.use_comment',
				'useCommentApproval' => 'videoBlockSetting.use_comment_approval',
			),
		),
		'Likes.Like',
		'NetCommons.DisplayNumber',
		'NetCommons.Token',
		'Workflow.Workflow',
		'Users.DisplayUser',
	);

/**
 * use components
 *
 * @var array
 * @link http://book.cakephp.org/2.0/ja/controllers/components.html#configuring-components
 * @link http://book.cakephp.org/2.0/ja/core-libraries/collections.html#id6 デフォルトのプライオリティ 10
 * @see NetCommonsAppController::$components
 * @see ContentCommentsComponent::beforeRender()
 *
 */
	public $components = array(
		'ContentComments.ContentComments' => array(
			'viewVarsKey' => array(
				'contentKey' => 'video.Video.key',
				'useComment' => 'videoBlockSetting.use_comment',
			),
			'allow' => array('view'),
		),
		'Cookie',
		'Paginator',									// ページャ
		'Files.Download' => array('priority' => 99),
	);

/**
 * beforeFilter
 *
 * @return void
 * @see NetCommonsAppController::beforeFilter()
 */
	public function beforeFilter() {
		parent::beforeFilter();

		// ブロック未選択は、何も表示しない
		//if (! Current::read('Block.id')) {
		//	$this->setAction('emptyRender');
		//	return false;
		//}

		// ゲストアクセスOKのアクションを設定
		$this->Auth->allow('tag', 'file');
	}

/**
 * 一覧表示
 *
 * @return CakeResponse
 */
	public function index() {
		// 一覧取得
		$results = $this->__list();
		$this->set($results);
	}

/**
 * tag別一覧
 *
 * @return CakeResponse
 */
	public function tag() {
		$this->view = 'index';

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
		//$this->render('index');
	}

/**
 * 詳細表示
 *
 * @return CakeResponse
 */
	public function view() {
		//動画の取得
		$videoKey = $this->params['pass'][1];
		$video = $this->Video->getWorkflowContents('first', array(
			'recursive' => 1,
			'conditions' => array(
				$this->Video->alias . '.key' => $videoKey
			)
		));
		if (! $video) {
			$this->throwBadRequest();
			return false;
		}
		$this->set('video', $video);

		// モデルからビヘイビアをはずす
		//$this->Video->Behaviors->unload('Tags.Tag');

		//関連動画の取得
		$relatedVideos = $this->Video->getWorkflowContents('all', array(
			'recursive' => 1,
			'conditions' => array(
				$this->Video->alias . '.block_id' => Current::read('Frame.block_id'),
				$this->Video->alias . '.created_user' => $video['Video']['created_user'],
				'NOT' => array(
					$this->Video->alias . '.id' => $video['Video']['id'],
				),
			),
			'order' => $this->Video->alias . '.id DESC'
		));
		$this->set('relatedVideos', $relatedVideos);

		// 利用系(コメント利用、高く評価を利用等)の設定取得
		$videoBlockSetting = $this->VideoBlockSetting->getVideoBlockSetting(); //データあり
		$results['videoBlockSetting'] = $videoBlockSetting['VideoBlockSetting'];

		// クッキー対応
		$cookie = $this->Cookie->read('video_history');
		$cookieArray = explode(':', $cookie);

		if (! in_array($video['Video']['id'], $cookieArray, true)) {
			//再生回数 + 1 で更新
			$playNumber = $this->Video->updateCountUp($video);
			$this->viewVars['video']['Video']['play_number'] = $playNumber;

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

		$this->set($results);
	}

/**
 * サムネイル、動画の表示
 *
 * @return CakeResponse
 */
	public function file() {
		return $this->__download();
	}

/**
 * 動画をファイルとしてダウンロード
 *
 * @return CakeResponse
 */
	public function download() {
		$options = array(
			'download' => true
		);
		return $this->__download($options);
	}

/**
 * ダウンロード
 *
 * @param array $options オプション field : ダウンロードのフィールド名, size: nullならオリジナル thumb, small, medium, big
 * @return CakeResponse
 * @throws NotFoundException 表示できない記事へのアクセス
 * @see DownloadComponent::doDownload()
 */
	private function __download($options = array()) {
		// ここから元コンテンツを取得する処理
		//$this->_prepare();
		$key = $this->params['pass'][1];
		$conditions = $this->Video->getConditions();

		$conditions['Video.key'] = $key;
		$query = array(
			'conditions' => $conditions,
		);
		$video = $this->Video->find('first', $query);
		// ここまで元コンテンツを取得する処理

		// ダウンロード実行
		if ($video) {
			return $this->Download->doDownload($video['Video']['id'], $options);
		} else {
			// 表示できない記事へのアクセスなら404
			throw new NotFoundException(__('Invalid blog entry'));
		}
	}

/**
 * 一覧取得
 *
 * @param array $extraConditions 追加conditions
 * @return array 動画一覧及び、設定
 * @throws Exception Paginatorによる例外
 */
	private function __list($extraConditions = array()) {
		// ブロック未選択
		if (empty(Current::read('Frame.block_id'))) {
			return array();
		}

		/* @see WorkflowBehavior::getWorkflowConditions() */
		$query['conditions'] = $this->Video->getWorkflowConditions([
			$this->Video->alias . '.block_id' => Current::read('Frame.block_id'),
		]);

		$query['conditions'] = Hash::merge($query['conditions'], $extraConditions);

		//ソート
		if (isset($this->params['named']['sort']) && isset($this->params['named']['direction'])) {
			$query['order'] = array($this->params['named']['sort'] => $this->params['named']['direction']);
		} else {
			$query['order'] = array('Video.created' => 'desc');
		}

		// 表示系(並び順、表示件数)の設定取得
		$videoFrameSetting = $this->VideoFrameSetting->getVideoFrameSetting(true);

		//表示件数
		if (isset($this->params['named']['limit'])) {
			$query['limit'] = (int)$this->params['named']['limit'];
		} else {
			$query['limit'] = $videoFrameSetting['VideoFrameSetting']['display_number'];
		}

		$this->Paginator->settings = $query;
		try {
			$videos = $this->Paginator->paginate('Video');
		} catch (Exception $ex) {
			CakeLog::error($ex);
			throw $ex;
		}
		$results['videos'] = $videos;

		// 利用系(コメント利用、高く評価を利用等)の設定取得
		$videoBlockSetting = $this->VideoBlockSetting->getVideoBlockSetting(); //データあり
		$results['videoBlockSetting'] = $videoBlockSetting['VideoBlockSetting'];

		//ソート
		$order = $this->__order($videoFrameSetting);
		$results['displayOrderPaginator'] = key($order) . '.' . $order[key($order)];

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