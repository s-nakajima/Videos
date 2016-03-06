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
App::uses('NetCommonsMail', 'Mails.Utility');
App::uses('MailSend', 'Mails.Utility');

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
		'Videos.Video',
	);

/**
 * use components
 *
 * @var array
 */
	public $components = array(
		'Files.FileUpload',
		'Mails.MailQueues',
		'NetCommons.Permission' => array(
			//アクセスの権限
			'allow' => array(
				'add,edit,delete' => 'content_creatable',
			),
		),
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
//CakeLog::debug('add - 1');
		if ($this->request->is('post')) {

			//登録処理
			$data = $this->data;
			$data['Video']['status'] = $this->Workflow->parseStatus();
			unset($data['Video']['id']);

			// 登録
			if ($video = $this->Video->addSaveVideo($data)) {
//CakeLog::debug('add - __mail - 1');
				// メール送信
				$this->__mail($video);
//CakeLog::debug('add - __mail - 2');

				$this->redirect(NetCommonsUrl::backToPageUrl());
				return;
			}

			$this->NetCommons->handleValidationError($this->Video->validationErrors);

		} else {
			//表示処理
			$this->request->data = Hash::merge($this->request->data,
				$this->Video->create()
			);
			$this->request->data['Frame'] = Current::read('Frame');
			$this->request->data['Block'] = Current::read('Block');
		}

		$results['video'] = null;

		$this->set($results);
	}

/**
 * 編集
 *
 * @return CakeResponse
 */
	public function edit() {
		//動画の取得
		$videoKey = $this->params['pass'][1];
		$video = $this->Video->getWorkflowContents('first', array(
			'recursive' => 1,
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

		/* @see WorkflowCommentBehavior::getCommentsByContentKey() */
		$comments = $this->Video->getCommentsByContentKey($videoKey);
		$this->set('comments', $comments);

		if ($this->request->is('put')) {

			$data = $this->data;
			$data['Video']['status'] = $this->Workflow->parseStatus();
			unset($data['Video']['id']);

			// 登録（ワークフロー対応のため、編集でも常にinsert）
			if ($video = $this->Video->editSaveVideo($data)) {
				$url = NetCommonsUrl::actionUrl(array(
					'controller' => 'videos',
					'action' => 'view',
					'block_id' => Current::read('Block.id'),
					'frame_id' => Current::read('Frame.id'),
					'key' => $video['Video']['key']
				));

				// メール送信
//				if (!$this->__mail($video, $url)) {
//					return;
//				}
				$this->__mail($video);

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
	}

/**
 * 削除
 *
 * @return CakeResponse
 */
	public function delete() {
		if ($this->request->is('delete')) {
			// 削除
			if (!$this->Video->deleteVideo($this->data)) {
				$this->throwBadRequest();
				return;
			}

			if (! $this->request->is('ajax')) {
				// 一覧へ
				$url = NetCommonsUrl::actionUrl(array(
					'controller' => 'videos',
					'action' => 'index',
					'block_id' => $this->data['Block']['id'],
					'frame_id' => $this->data['Frame']['id'],
				));
				$this->redirect($url);
			}
			return;
		}
		$this->throwBadRequest();
	}

/**
 * メール送信
 *
 * @param array $data コンテンツ
 * @return bool 成功 or 失敗
 */
	private function __mail($data) {
		//private function __mail($data, $url) {
		$mail = new NetCommonsMail();
		//$mail->setSendMailSetting(Current::read('Block.key'));
		//$mail->setSendMailSetting();

		// 通知しない
		if (! $mail->isMailSend) {
			CakeLog::debug('__mail - 通知しない');
			return true;
		}

		$url = NetCommonsUrl::actionUrl(array(
			'controller' => 'videos',
			'action' => 'view',
			'block_id' => Current::read('Block.id'),
			'frame_id' => Current::read('Frame.id'),
			'key' => $data['Video']['key']
		));

		// ・定型文の変換タグの追加
		//		$this->assignTag("X-PLUGIN_NAME", '動画');
		//		$this->assignTag("X-BLOCK_NAME", '運動会');
		//		$this->assignTag("X-SUBJECT", 'タイトル');
		//		$this->assignTag("X-TO_DATE", '2099/01/01');
		//		$this->assignTag("X-BODY", '本文１\n本文２\n本文３');
		//		$this->assignTag("X-URL", 'http://localhost');
		//		$this->assignTag("X-APPROVAL_COMMENT", '承認コメント１\n承認コメント２\n承認コメント３');

		//$mail->assignTag("X-PLUGIN_NAME", '動画');
		//$mail->assignTag("X-BLOCK_NAME", '運動会');	// blockKey+langでDB参照[blocks]
		//$mail->assignTag("X-TO_DATE", date('Y/m/d H:i:s'));
		$mail->assignTag("X-SUBJECT", $data['Video']['title']);
		$mail->assignTag("X-BODY", $data['Video']['description']);
		$mail->assignTag("X-URL", $url);
		$mail->assignTag("X-APPROVAL_COMMENT", $data['WorkflowComment']['comment']);

		// 複数人の送信先ユーザ取得　※まだ決められない実装
		// blockeyをセットしたら、複数人を取得して、セットするまでやる。
		//$users = $this->getSendMailUsers($wwww, $zzzz);
		//$mail->setSendMailUsers($blockKey);
		// 複数人の送信先ユーザ追加
		//$mail->addMailToUsers($users);

		// 送信先メールアドレス 直指定
		$mail->to('mutaguchi@opensource-workshop.jp');

		//$languageId = Current::read('Language.id');		//仮
		//$roomId = Current::read('Room.id');
		$roomId = Current::read('Room.id');

//CakeLog::debug('__mail - 1');
		// キューに保存する
		//$mail->saveQueue($data['Video']['key'], $languageId);
		//$mail->saveQueue($mail, $data['Video']['key'], $roomId);
		/** @see MailQueuesComponent::saveQueueRoomId() */
		if (!$this->MailQueues->saveQueueRoomId($mail, $data['Video']['key'], $roomId)) {
//CakeLog::debug('__mail - false');
			return false;
		}
//CakeLog::debug('__mail - 2');

		// エラー
		//$this->NetCommons->handleValidationError($MailQueue->validationErrors);

		// メール送信
		//$mail->sendMail();
		//MailSend::send();

		return true;
	}

}