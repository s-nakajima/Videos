<?php
/**
 * メール Component
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('MailSend', 'Mails.Utility');
App::uses('MailQueuesComponent', 'Mails.Controller/Component');

/**
 * メール Component
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Mails\Controller\Component
 * @property MailQueuesComponent $MailQueues
 */
class VideoMailComponent extends MailQueuesComponent {

/**
 * メール送信
 *
 * @param array $data コンテンツ
 * @return bool 成功 or 失敗
 */
	public function mail($data) {
		if (! $this->isMailSend()) {
			return true;
		}

		// fullpassのURL
		$url = NetCommonsUrl::actionUrl(array(
			'controller' => 'videos',
			'action' => 'view',
			'block_id' => Current::read('Block.id'),
			'frame_id' => Current::read('Frame.id'),
			'key' => $data['Video']['key']
		));
		$url = NetCommonsUrl::url($url, true);

		// 定型文の変換タグをセット
		//		$this->assignTag('X-SUBJECT', $data['Video']['title']);
		//		$this->assignTag('X-BODY', $data['Video']['description']);
		//		$this->assignTag('X-URL', $url);
		//
		//		$this->setTags($data);
		$this->tags = array(
			'X-SUBJECT' => $data['Video']['title'],
			'X-BODY' => $data['Video']['description'],
			'X-URL' => $url,
		);
		$this->setWorkflowCommentTag($data);

		// --- どのパターンでメールを送りたいかによって実装を変更する
		// キューに保存（ルーム単位でメール配信）
//		if (!$this->saveQueueRoomId($contentKey)) {
//			return false;
//		}

		// キューに保存（user単位でメール配信）
		//		$userId = Current::read('User.id');		//仮
		//		if (!$this->saveQueueUserId($contentKey, $userId)) {
		//			return false;
		//		}

		// キューに保存（メールアドレス単位でメール配信）
		$toAddress = 'mutaguchi@opensource-workshop.jp';	// 仮
		if (!$this->saveQueueToAddress($data['Video']['key'], $toAddress)) {
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
