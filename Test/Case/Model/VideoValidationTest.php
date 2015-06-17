<?php
/**
 * VideoValidationTest Case
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('VideoValidationTestBase', 'Videos.Test/Case/Model');

/**
 * VideoValidationTest Case
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\ContentComments\Test\Case\Model
 */
class VideoValidationTest extends VideoValidationTestBase {

/**
 * 登録Videoデータ保存 バリデーションエラー戻り値テスト
 * $data['Video']['title'] keyなしのため、エラー
 *
 * @return void
 */
	public function testAddSaveVideoValidationErrors() {
		// テスト準備
		$roomId = 1;
		$data = $this->_readyTestSaveVideoData($roomId);
		$modelMock = $this->_readyVideoMock();
		unset($data['Video']['title']);

		$video = $modelMock->addSaveVideo($data, $roomId);

		// テストファイル削除
		$this->_deleteTestFile();

		$this->assertFalse($video);
	}

/**
 * 登録Videoデータ保存 validateByStatus エラー戻り値テスト
 * $data['Comment']['comment'] = null のため、エラー
 *
 * @return void
 */
	public function testAddSaveVideoValidateByStatus() {
		// テスト準備
		$roomId = 1;
		$data = $this->_readyTestSaveVideoData($roomId);
		$modelMock = $this->_readyVideoMock();
		$data['Comment']['comment'] = null;

		$video = $modelMock->addSaveVideo($data, $roomId);

		// テストファイル削除
		$this->_deleteTestFile();

		$this->assertFalse($video);
	}

/**
 * 登録Videoデータ保存 saveConvertVideo エラー戻り値テスト
 *
 * @return void
 */
	public function testAddSaveVideoSaveConvertVideo() {
		// テスト準備
		$roomId = 1;
		$data = $this->_readyTestSaveVideoData($roomId);

		// modelモック
		$modelMock = $this->getMockForModel('Videos.Video', ['saveConvertVideo']);
		$modelMock->expects($this->any())
			->method('saveConvertVideo')
			->will($this->returnValue(false));
		// 暫定対応(;'∀') SQLSTATE[42S22]: Column not found: 1054 Unknown column 'Block.language_id' in 'on clause'
		$modelMock->hasOne = array();

		$video = $modelMock->addSaveVideo($data, $roomId);

		// テストファイル削除
		$this->_deleteTestFile();

		$this->assertFalse($video);
	}

/**
 * 登録Videoデータ保存 動画を自動変換しない バリデーションエラー戻り値テスト
 * $data['Video']['title'] keyなしのため、エラー
 *
 * @return void
 */
	public function testAddNoConvertSaveVideoValidationErrors() {
		$status = NetCommonsBlockComponent::STATUS_APPROVED;
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
				//'title' => '動画タイトル名',
			)),
			array('Comment' => array(
				'block_key' => $blockKey,
				'comment' => '承認コメント',
			))
		);
		// 暫定対応(;'∀') SQLSTATE[42S22]: Column not found: 1054 Unknown column 'Block.language_id' in 'on clause'
		$this->Video->hasOne = array();

		$video = $this->Video->addNoConvertSaveVideo($data);

		$this->assertFalse($video);
	}

/**
 * 登録Videoデータ保存 動画を自動変換しない validateVideoFile 動画ファイル エラー戻り値テスト
 * $data['videoFile']['File']['role_type'] keyなしのため、エラー
 *
 * @return void
 */
	public function testAddNoConvertSaveVideoValidateVideoFile() {
		// テスト準備
		$roomId = 1;
		$data = $this->_readyTestSaveVideoData($roomId);
		unset($data[Video::VIDEO_FILE_FIELD]['File']['role_type']);

		// 暫定対応(;'∀') SQLSTATE[42S22]: Column not found: 1054 Unknown column 'Block.language_id' in 'on clause'
		$this->Video->hasOne = array();

		$video = $this->Video->addNoConvertSaveVideo($data);

		// テストファイル削除
		$this->_deleteTestFile();

		$this->assertFalse($video);
	}

/**
 * 登録Videoデータ保存 動画を自動変換しない validateVideoFile サムネイル エラー戻り値テスト
 * $data['thumbnail']['File']['role_type'] = nullのため、エラー
 *
 * @return void
 */
	public function testAddNoConvertSaveVideoValidateVideoFileThumbnail() {
		// テスト準備
		$roomId = 1;
		$data = $this->_readyTestSaveVideoData($roomId);

		$fileName = 'thumbnail1.jpg';
		$data = Hash::merge(
			$data,
			array(Video::THUMBNAIL_FIELD => array(
				'FilesPlugin' => array(
					'plugin_key' => 'videos'
				),
				'File' => array(
					'name' => $fileName,
					'extension' => 'image/jpeg',
					'slug' => 'thumbnail1',
					//'role_type' => 'room_file_role',
					'role_type' => null,
				),
			))
		);
		// 暫定対応(;'∀') SQLSTATE[42S22]: Column not found: 1054 Unknown column 'Block.language_id' in 'on clause'
		$this->Video->hasOne = array();

		$video = $this->Video->addNoConvertSaveVideo($data);

		// テストファイル削除
		$this->_deleteTestFile();

		$this->assertFalse($video);
	}

/**
 * 登録Videoデータ保存 動画を自動変換しない validateByStatus エラー戻り値テスト
 * $data['Comment']['comment'] = null のため、エラー
 *
 * @return void
 */
	public function testAddNoConvertSaveVideoValidateByStatus() {
		// テスト準備
		$roomId = 1;
		$data = $this->_readyTestSaveVideoData($roomId);
		$data['Comment']['comment'] = null;

		// 暫定対応(;'∀') SQLSTATE[42S22]: Column not found: 1054 Unknown column 'Block.language_id' in 'on clause'
		$this->Video->hasOne = array();

		$video = $this->Video->addNoConvertSaveVideo($data);

		// テストファイル削除
		$this->_deleteTestFile();

		$this->assertFalse($video);
	}

/**
 * 編集Videoデータ保存 リデーションエラー戻り値テスト
 * $data['Video']['title'] keyなしのため、エラー
 *
 * @return void
 */
	public function testEditSaveVideoValidationErrors() {
		$status = NetCommonsBlockComponent::STATUS_APPROVED;
		$blockKey = 'block_5';
		$videoId = 1;
		// 登録データ作成
		$video = $this->Video->findById($videoId);

		$data = Hash::merge(
			$video,
			array($this->Video->alias => array(
				'status' => $status,
				//'title' => '動画タイトル名',
				'title' => null,
			)),
			array('Comment' => array(
				'block_key' => $blockKey,
				'comment' => '承認コメント',
			))
		);
		// 暫定対応(;'∀') SQLSTATE[42S22]: Column not found: 1054 Unknown column 'Block.language_id' in 'on clause'
		$this->Video->hasOne = array();

		$video = $this->Video->editSaveVideo($data);

		$this->assertFalse($video);
	}

/**
 * 編集Videoデータ保存 validateVideoFile サムネイル エラー戻り値テスト
 * $data['thumbnail']['File']['role_type'] = null のため、エラー
 *
 * @return void
 */
	public function testEditSaveVideoValidateVideoFile() {
		$status = NetCommonsBlockComponent::STATUS_APPROVED;
		$blockKey = 'block_5';
		$videoId = 1;
		// 登録データ作成
		$video = $this->Video->findById($videoId);

		$fileName = 'thumbnail1.jpg';
		$data = Hash::merge(
			$video,
			array($this->Video->alias => array(
				'status' => $status,
				'title' => '動画タイトル名',
			)),
			array('Comment' => array(
				'block_key' => $blockKey,
				//'comment' => '承認コメント',
				'comment' => null,
			)),
			array(Video::THUMBNAIL_FIELD => array(
				'FilesPlugin' => array(
					'plugin_key' => 'videos'
				),
				'File' => array(
					'name' => $fileName,
					'extension' => 'image/jpeg',
					'slug' => 'thumbnail1',
					//'role_type' => 'room_file_role',
					'role_type' => null,
				),
			))
		);
		// 暫定対応(;'∀') SQLSTATE[42S22]: Column not found: 1054 Unknown column 'Block.language_id' in 'on clause'
		$this->Video->hasOne = array();

		$video = $this->Video->editSaveVideo($data);

		$this->assertFalse($video);
	}

/**
 * 編集Videoデータ保存 validateByStatus テスト
 * $data['Comment']['comment'] = null のため、エラー
 *
 * @return void
 */
	public function testEditSaveVideoValidateByStatus() {
		$status = NetCommonsBlockComponent::STATUS_APPROVED;
		$blockKey = 'block_5';
		$videoId = 1;
		// 登録データ作成
		$video = $this->Video->findById($videoId);

		$data = Hash::merge(
			$video,
			array($this->Video->alias => array(
				'status' => $status,
				'title' => '動画タイトル名',
			)),
			array('Comment' => array(
				'block_key' => $blockKey,
				//'comment' => '承認コメント',
				'comment' => null,
			))
		);
		// 暫定対応(;'∀') SQLSTATE[42S22]: Column not found: 1054 Unknown column 'Block.language_id' in 'on clause'
		$this->Video->hasOne = array();

		$video = $this->Video->editSaveVideo($data);

		$this->assertFalse($video);
	}
}
