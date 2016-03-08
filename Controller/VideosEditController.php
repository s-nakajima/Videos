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
		if ($this->request->is('post')) {

			//登録処理
			$data = $this->data;
			$data['Video']['status'] = $this->Workflow->parseStatus();
			unset($data['Video']['id']);

			// 登録
			if ($video = $this->Video->addSaveVideo($data)) {
				// メール送信
				$this->__mail($video);

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
		$mail = new NetCommonsMail();
		$mail->initPlugin($data);

		// 通知しない
		if (! $mail->isMailSend) {
			return true;
		}

		$contentKey = $data['Video']['key'];

		// fullpassのURL
		$url = NetCommonsUrl::actionUrl(array(
			'controller' => 'videos',
			'action' => 'view',
			'block_id' => Current::read('Block.id'),
			'frame_id' => Current::read('Frame.id'),
			'key' => $contentKey
		));
		$url = NetCommonsUrl::url($url, true);

		// 定型文の変換タグをセット
		$mail->assignTag("X-SUBJECT", $data['Video']['title']);
		$mail->assignTag("X-BODY", $data['Video']['description']);
		$mail->assignTag("X-URL", $url);

		// キューに保存（ルーム単位でメール配信）
//		/** @see MailQueuesComponent::saveQueueRoomId() */
//		if (!$this->MailQueues->saveQueueRoomId($mail, $contentKey)) {
//			return false;
//		}

		// キューに保存（user単位でメール配信）
		//		$userId = Current::read('User.id');		//仮
		//		/** @see MailQueuesComponent::saveQueueUserId() */
		//		if (!$this->MailQueues->saveQueueUserId($mail, $contentKey, $userId)) {
		//			return false;
		//		}

		// キューに保存（メールアドレス単位でメール配信）
		$toAddress = 'mutaguchi@opensource-workshop.jp';	// 仮
		/** @see MailQueuesComponent::saveQueueToAddress() */
		if (!$this->MailQueues->saveQueueToAddress($mail, $contentKey, $toAddress)) {
			return false;
		}

		// キューに保存しないで直送信
		//		$toAddress = 'mutaguchi@opensource-workshop.jp';	// 仮
		//		$mail->to($toAddress);
		//		$mail->sendMail();

		// キューからメール送信
		MailSend::send();

		return true;
	}

}