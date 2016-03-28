<?php
/**
 * VideoExceptionTest Case
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('VideoTestBase', 'Videos.Test/Case/Model');

/**
 * VideoExceptionTest Case
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\ContentComments\Test\Case\Model
 */
class VideoExceptionTest extends VideoTestBase {

/**
 * 再生回数 + 1 で更新 例外テスト
 *
 * @return void
 */
	public function testUpdateCountUpException() {
		$this->setExpectedException('InternalErrorException');

		$conditions = array();
		$fields = null;
		$video = $this->Video->getVideo($conditions, $fields);

		// modelモック
		$modelMock = $this->getMockForModel('Videos.Video', ['save']);
		$modelMock->expects($this->any())
			->method('save')
			->will($this->returnValue(false));

		$modelMock->updateCountUp($video);
	}

/**
 * 登録Videoデータ保存 例外テスト
 *
 * @return void
 * @throws Exception
 */
	public function testAddSaveVideoException() {
		$this->setExpectedException('InternalErrorException');

		// テストファイル準備
		$roomId = 1;
		$fileName = 'video1.mp4';
		$mp4Id = 1;
		$this->_readyTestFile($mp4Id, $roomId, $fileName);

		$status = WorkflowComponent::STATUS_APPROVED;
		$blockId = 1;
		$languageId = 2;
		$blockKey = 'block_1';
		// 登録データ作成
		$video = $this->Video->create();
		$data = Hash::merge(
			$video,
			array($this->Video->alias => array(
				'status' => $status,
				'block_id' => $blockId,
				'language_id' => $languageId,
				'title' => '動画タイトル名',
				'mp4_id' => $mp4Id,
			)),
			array('Comment' => array(
				'block_key' => $blockKey,
				'comment' => '承認コメント',
			)),
			array(Video::VIDEO_FILE_FIELD => array(
				'FilesPlugin' => array(
					'plugin_key' => 'videos'
			)))
		);

		$data[Video::VIDEO_FILE_FIELD]['File']['name'] = $fileName;
		$data[Video::VIDEO_FILE_FIELD]['File']['extension'] = 'mp4';
		$data[Video::VIDEO_FILE_FIELD]['File']['slug'] = 'video1';
		$data[Video::VIDEO_FILE_FIELD]['File']['role_type'] = 'room_file_role';

		// 暫定対応(;'∀') 登録後、$this->saveConvertVideo($data, $video, $roomId)の下記で落ちるので、モックで対応
		//   $noConvert = $Model->FileModel->findById($video['Video']['mp4_id']);
		//   Uninitialized string offset: -1
		// modelモック
		$modelMock = $this->getMockForModel('Videos.Video', ['save', 'saveConvertVideo']);
		$modelMock->expects($this->any())
			->method('save')
			->will($this->returnValue(false));
		$modelMock->expects($this->any())
			->method('saveConvertVideo')
			->will($this->returnValue(true));
		// 暫定対応(;'∀') SQLSTATE[42S22]: Column not found: 1054 Unknown column 'Block.language_id' in 'on clause'
		$modelMock->hasOne = array();

		try {
			$modelMock->addSaveVideo($data, $roomId);
		} catch (Exception $e) {
			// テストファイル削除
			$this->_deleteTestFile();
			throw $e;
		}
	}

/**
 * 登録Videoデータ保存 承認コメント例外テスト
 *
 * @return void
 * @throws Exception
 */
	public function testAddSaveVideoCommentException() {
		$this->setExpectedException('InternalErrorException');

		// テストファイル準備
		$roomId = 1;
		$fileName = 'video1.mp4';
		$mp4Id = 1;
		$this->_readyTestFile($mp4Id, $roomId, $fileName);

		$status = WorkflowComponent::STATUS_APPROVED;
		$blockId = 1;
		$languageId = 2;
		$blockKey = 'block_1';
		// 登録データ作成
		$video = $this->Video->create();
		$data = Hash::merge(
			$video,
			array($this->Video->alias => array(
				'status' => $status,
				'block_id' => $blockId,
				'language_id' => $languageId,
				'title' => '動画タイトル名',
				'mp4_id' => $mp4Id,
			)),
			array('Comment' => array(
				'block_key' => $blockKey,
				'comment' => '承認コメント',
			)),
			array(Video::VIDEO_FILE_FIELD => array(
				'FilesPlugin' => array(
					'plugin_key' => 'videos'
				)))
		);

		$data[Video::VIDEO_FILE_FIELD]['File']['name'] = $fileName;
		$data[Video::VIDEO_FILE_FIELD]['File']['extension'] = 'mp4';
		$data[Video::VIDEO_FILE_FIELD]['File']['slug'] = 'video1';
		$data[Video::VIDEO_FILE_FIELD]['File']['role_type'] = 'room_file_role';

		// 暫定対応(;'∀') 登録後、$this->saveConvertVideo($data, $video, $roomId)の下記で落ちるので、モックで対応
		//   $noConvert = $Model->FileModel->findById($video['Video']['mp4_id']);
		//   Uninitialized string offset: -1
		// modelモック
		$modelMock = $this->getMockForModel('Videos.Video', ['saveConvertVideo']);
		$modelMock->expects($this->any())
			->method('save')
			->will($this->returnValue(false));
		// 暫定対応(;'∀') SQLSTATE[42S22]: Column not found: 1054 Unknown column 'Block.language_id' in 'on clause'
		$modelMock->hasOne = array();

		// commnet modelモック
		$commnetMock = $this->getMockForModel('Comments.Comment', ['save']);
		$commnetMock->expects($this->any())
			->method('save')
			->will($this->returnValue(false));
		$modelMock->Commnet = $commnetMock;

		try {
			$modelMock->addSaveVideo($data, $roomId);
		} catch (Exception $e) {
			// テストファイル削除
			$this->_deleteTestFile();
			throw $e;
		}
	}

/**
 * 登録Videoデータ保存 動画を自動変換しない 例外テスト
 *
 * @return void
 */
	public function testAddNoConvertSaveVideoException() {
		$this->setExpectedException('InternalErrorException');

		$status = WorkflowComponent::STATUS_APPROVED;
		$blockId = 2;
		$languageId = 2;
		$blockKey = 'block_2';
		// 登録データ作成
		$video = $this->Video->create();
		$data = Hash::merge(
			$video,
			array($this->Video->alias => array(
				'status' => $status,
				'block_id' => $blockId,
				'language_id' => $languageId,
				'title' => '動画タイトル名',
			)),
			array('Comment' => array(
				'block_key' => $blockKey,
				'comment' => '承認コメント',
			))
		);
		// 暫定対応(;'∀') SQLSTATE[42S22]: Column not found: 1054 Unknown column 'Block.language_id' in 'on clause'
		$this->Video->hasOne = array();

		// modelモック
		$modelMock = $this->getMockForModel('Videos.Video', ['save']);
		$modelMock->expects($this->any())
			->method('save')
			->will($this->returnValue(false));

		$modelMock->addNoConvertSaveVideo($data);
	}

/**
 * 登録Videoデータ保存 動画を自動変換しない 承認コメント例外テスト
 *
 * @return void
 */
	public function testAddNoConvertSaveVideoCommentException() {
		$this->setExpectedException('InternalErrorException');

		$status = WorkflowComponent::STATUS_APPROVED;
		$blockId = 2;
		$languageId = 2;
		$blockKey = 'block_2';
		// 登録データ作成
		$video = $this->Video->create();
		$data = Hash::merge(
			$video,
			array($this->Video->alias => array(
				'status' => $status,
				'block_id' => $blockId,
				'language_id' => $languageId,
				'title' => '動画タイトル名',
			)),
			array('Comment' => array(
				'block_key' => $blockKey,
				'comment' => '承認コメント',
			))
		);
		// 暫定対応(;'∀') SQLSTATE[42S22]: Column not found: 1054 Unknown column 'Block.language_id' in 'on clause'
		$this->Video->hasOne = array();

		// commnet modelモック
		$commnetMock = $this->getMockForModel('Comments.Comment', ['save']);
		$commnetMock->expects($this->any())
			->method('save')
			->will($this->returnValue(false));
		$this->Video->Commnet = $commnetMock;

		$this->Video->addNoConvertSaveVideo($data);
	}

/**
 * 編集Videoデータ保存 例外テスト
 *
 * @return void
 */
	public function testEditSaveVideoException() {
		$this->setExpectedException('InternalErrorException');

		$status = WorkflowComponent::STATUS_APPROVED;
		$blockId = 2;
		$languageId = 2;
		$blockKey = 'block_2';
		// 登録データ作成
		$video = $this->Video->create();
		$data = Hash::merge(
			$video,
			array($this->Video->alias => array(
				'status' => $status,
				'block_id' => $blockId,
				'language_id' => $languageId,
				'title' => '動画タイトル名',
			)),
			array('Comment' => array(
				'block_key' => $blockKey,
				'comment' => '承認コメント',
			))
		);

		// modelモック
		$modelMock = $this->getMockForModel('Videos.Video', ['save']);
		$modelMock->expects($this->any())
			->method('save')
			->will($this->returnValue(false));
		// 暫定対応(;'∀') SQLSTATE[42S22]: Column not found: 1054 Unknown column 'Block.language_id' in 'on clause'
		$modelMock->hasOne = array();

		$modelMock->editSaveVideo($data);
	}

/**
 * 編集Videoデータ保存 承認コメント例外テスト
 *
 * @return void
 */
	public function testEditSaveVideoCommentException() {
		$this->setExpectedException('InternalErrorException');

		$status = WorkflowComponent::STATUS_APPROVED;
		$blockId = 2;
		$languageId = 2;
		$blockKey = 'block_2';
		// 登録データ作成
		$video = $this->Video->create();
		$data = Hash::merge(
			$video,
			array($this->Video->alias => array(
				'status' => $status,
				'block_id' => $blockId,
				'language_id' => $languageId,
				'title' => '動画タイトル名',
			)),
			array('Comment' => array(
				'block_key' => $blockKey,
				'comment' => '承認コメント',
			))
		);
		// 暫定対応(;'∀') SQLSTATE[42S22]: Column not found: 1054 Unknown column 'Block.language_id' in 'on clause'
		$this->Video->hasOne = array();

		// commnet modelモック
		$commnetMock = $this->getMockForModel('Comments.Comment', ['save']);
		$commnetMock->expects($this->any())
			->method('save')
			->will($this->returnValue(false));
		$this->Video->Commnet = $commnetMock;

		$this->Video->editSaveVideo($data);
	}
}
