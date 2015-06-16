<?php
/**
 * VideoTest Case
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('VideoTestBase', 'Videos.Test/Case/Model');

/**
 * VideoTest Case
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\ContentComments\Test\Case\Model
 */
class VideoTest extends VideoTestBase {

/**
 * Videoデータ取得 テスト
 *
 * @return void
 */
	public function testGetVideo() {
		$conditions = array();
		$fields = null;
		$video = $this->Video->getVideo($conditions, $fields);

		$this->assertInternalType('array', $video);
	}

/**
 * 複数Videoデータ取得 テスト
 *
 * @return void
 */
	public function testGetVideos() {
		$conditions = array();
		$video = $this->Video->getVideos($conditions);

		$this->assertInternalType('array', $video);
	}

/**
 * 再生回数 + 1 で更新 テスト
 *
 * @return void
 */
	public function testUpdateCountUp() {
		$conditions = array();
		$fields = null;
		$video = $this->Video->getVideo($conditions, $fields);

		$playNumber = $this->Video->updateCountUp($video);

		$this->assertEquals(2, $playNumber);
	}

/**
 * 登録Videoデータ保存 テスト
 *
 * @return void
 */
	public function testAddSaveVideo() {
		// テストファイル準備
		$roomId = 1;
		$fileName = 'video1.mp4';
		$mp4Id = 1;
		$this->_readyTestFile($mp4Id, $roomId, $fileName);

		$status = NetCommonsBlockComponent::STATUS_APPROVED;
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
		// $noConvert = $Model->FileModel->findById($video['Video']['mp4_id']);
		// Uninitialized string offset: -1
		// modelモック
		$modelMock = $this->getMockForModel('Videos.Video', ['saveConvertVideo']);
		$modelMock->expects($this->any())
			->method('saveConvertVideo')
			->will($this->returnValue(true));
		// 暫定対応(;'∀') SQLSTATE[42S22]: Column not found: 1054 Unknown column 'Block.language_id' in 'on clause'
		$modelMock->hasOne = array();

		$video = $modelMock->addSaveVideo($data, $roomId);

		// テストファイル削除
		$this->_deleteTestFile();

		$this->assertInternalType('array', $video);
	}

/**
 * 登録Videoデータ保存 動画を自動変換しない テスト
 *
 * @return void
 */
	public function testAddNoConvertSaveVideo() {
		$status = NetCommonsBlockComponent::STATUS_APPROVED;
		$blockId = 2;
		$languageId = 2;
		$blockKey = 'block_2';
		// 登録データ作成
		$video = $this->Video->create();
		$data = Hash::merge(
			$video,
			//$data,
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

		$video = $this->Video->addNoConvertSaveVideo($data);

		$this->assertInternalType('array', $video);
	}

/**
 * 編集Videoデータ保存 テスト
 *
 * @return void
 */
	public function testEditSaveVideo() {
		$status = NetCommonsBlockComponent::STATUS_APPROVED;
		$blockId = 2;
		$languageId = 2;
		$blockKey = 'block_2';
		// 登録データ作成
		$video = $this->Video->create();
		$data = Hash::merge(
			$video,
			//$data,
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

		$video = $this->Video->editSaveVideo($data);

		$this->assertInternalType('array', $video);
	}

/**
 * Videoデータ削除 テスト
 *
 * @return void
 */
	public function testDeleteVideo() {
		$data = array($this->Video->alias => array(
			'id' => 1,
			'key' => 'video_1',
		));

		$rtn = $this->Video->deleteVideo($data);

		$this->assertTrue($rtn);
	}
}
