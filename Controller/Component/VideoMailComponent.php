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

App::uses('Component', 'Controller');
App::uses('NetCommonsMail', 'Mails.Utility');
App::uses('MailSend', 'Mails.Utility');
App::uses('WorkflowComponent', 'Workflow.Controller.Component');

/**
 * メール Component
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Mails\Controller\Component
 * @property MailQueuesComponent $MailQueues
 */
class VideoMailComponent extends Component {

/**
 * @var Controller コントローラ
 */
	protected $_controller = null;

/**
 * Other components
 * コントローラから読み込んだコンポーネントと違い、コンポーネントからコンポーネントを読み込んだ場合は、 コールバックが呼ばれないことに注意して下さい。
 *
 * @var array
 * @link http://book.cakephp.org/2.0/ja/controllers/components.html#id8
 */
	public $components = array(
		'Mails.MailQueues',
	);

/**
 * Called before the Controller::beforeFilter().
 *
 * @param Controller $controller Instantiating controller
 * @return void
 * @link http://book.cakephp.org/2.0/ja/controllers/components.html#Component::initialize
 */
	public function initialize(Controller $controller) {
		// どのファンクションでも $controller にアクセスできるようにクラス内変数に保持する
		$this->_controller = $controller;

		$this->MailQueues->initialize($controller);
		$this->MailQueues->startup($controller);
	}

/**
 * メール送信
 *
 * @param array $data コンテンツ
 * @return bool 成功 or 失敗
 */
	public function mail($data) {
		$status = $this->_controller->Workflow->parseStatus();
		// 一時保存はメール送らない
		if ($status == WorkflowComponent::STATUS_IN_DRAFT) {
			return true;
		}

		$mail = new NetCommonsMail();
		$mail->initPlugin($data);

		// 通知しない
		if (! $mail->isMailSend) {
			return true;
		}

		$mail = $this->__setTags($mail, $data);

		// --- どのパターンでメールを送りたいかによって実装を変更する
		// キューに保存（ルーム単位でメール配信）
//		if (!$this->MailQueues->saveQueueRoomId($mail, $contentKey)) {
//			return false;
//		}

		// キューに保存（user単位でメール配信）
		//		$userId = Current::read('User.id');		//仮
		//		if (!$this->MailQueues->saveQueueUserId($mail, $contentKey, $userId)) {
		//			return false;
		//		}

		// キューに保存（メールアドレス単位でメール配信）
		$toAddress = 'mutaguchi@opensource-workshop.jp';	// 仮
		if (!$this->MailQueues->saveQueueToAddress($mail, $data['Video']['key'], $toAddress)) {
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

/**
 * タグ セット
 *
 * @param NetCommonsMail $mail NetCommonsメール
 * @param array $data コンテンツデータ
 * @return NetCommonsMail
 */
	private function __setTags(NetCommonsMail $mail, $data) {
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
		$mail->assignTag('X-SUBJECT', $data['Video']['title']);
		$mail->assignTag('X-BODY', $data['Video']['description']);
		$mail->assignTag('X-URL', $url);

		return $mail;
	}
}
