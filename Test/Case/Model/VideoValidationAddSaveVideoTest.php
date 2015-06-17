<?php
/**
 * VideoValidationAddSaveVideoTest Case
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('VideoValidationTestBase', 'Videos.Test/Case/Model');

/**
 * VideoValidationAddSaveVideoTest Case
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\ContentComments\Test\Case\Model
 */
class VideoValidationAddSaveVideoTest extends VideoValidationTestBase {

/**
 * 登録Videoデータ保存 title required エラー
 * $data['Video']['title'] keyなしのため、エラー
 *
 * @return void
 */
	public function testAddSaveVideoTitleRequired() {
		// テスト準備
		$roomId = 1;
		$data = $this->_readyTestSaveVideoData($roomId);
		$modelMock = $this->_readyVideoMock();
		unset($data['Video']['title']);

		$modelMock->addSaveVideo($data, $roomId);

		// テストファイル削除
		$this->_deleteTestFile();

		$this->assertArrayHasKey('title', $modelMock->validationErrors);
	}

/**
 * 登録Videoデータ保存 title notEmpty エラー
 * $data['Video']['title'] = nullのため、エラー
 *
 * @return void
 */
	public function testAddSaveVideoTitleNotEmpty() {
		// テスト準備
		$roomId = 1;
		$data = $this->_readyTestSaveVideoData($roomId);
		$modelMock = $this->_readyVideoMock();
		$data['Video']['title'] = null;

		$modelMock->addSaveVideo($data, $roomId);

		// テストファイル削除
		$this->_deleteTestFile();

		$this->assertArrayHasKey('title', $modelMock->validationErrors);
	}

/**
 * 登録Videoデータ保存 block_id required エラー
 * $data['Video']['block_id'] keyなしのため、エラー
 *
 * @return void
 */
	public function testAddSaveVideoBlockIdRequired() {
		// テスト準備
		$roomId = 1;
		$data = $this->_readyTestSaveVideoData($roomId);
		$modelMock = $this->_readyVideoMock();
		unset($data['Video']['block_id']);

		$modelMock->addSaveVideo($data, $roomId);

		// テストファイル削除
		$this->_deleteTestFile();

		$this->assertArrayHasKey('block_id', $modelMock->validationErrors);
	}

/**
 * 登録Videoデータ保存 block_id numeric エラー
 * $data['Video']['block_id'] = 数値以外のため、エラー
 *
 * @return void
 */
	public function testAddSaveVideoBlockIdNumeric() {
		// テスト準備
		$roomId = 1;
		$data = $this->_readyTestSaveVideoData($roomId);
		$modelMock = $this->_readyVideoMock();
		$data['Video']['block_id'] = 'hoge';

		$modelMock->addSaveVideo($data, $roomId);

		// テストファイル削除
		$this->_deleteTestFile();

		$this->assertArrayHasKey('block_id', $modelMock->validationErrors);
	}

/**
 * 登録Videoデータ保存 videoFile uploadError エラー
 * $data['Video']['videoFile']['error'] = 4のため、エラー
 *
 * @return void
 */
	public function testAddSaveVideoVideoFileIsFileUpload() {
		// テスト準備
		$roomId = 1;
		$data = $this->_readyTestSaveVideoData($roomId);
		$modelMock = $this->_readyVideoMock();
		$data['Video'][Video::VIDEO_FILE_FIELD]['error'] = 4;

		$modelMock->addSaveVideo($data, $roomId);

		// テストファイル削除
		$this->_deleteTestFile();

		//var_dump($modelMock->validationErrors);
		$this->assertArrayHasKey('videoFile', $modelMock->validationErrors);
	}

/**
 * 登録Videoデータ保存 videoFile extension エラー
 * アップロードしたファイルが Video::VIDEO_EXTENSION 以外の拡張子のため、エラー
 *
 * @return void
 */
	public function testAddSaveVideoVideoFileExtension() {
		// テスト準備
		$roomId = 1;
		$contentsId = 2;
		$fileName = 'thumbnail1.jpg';
		$data = $this->_readyTestSaveVideoData($roomId, $contentsId, $fileName);
		$modelMock = $this->_readyVideoMock();

		$modelMock->addSaveVideo($data, $roomId);

		// テストファイル削除
		$this->_deleteTestFile();

		//var_dump($modelMock->validationErrors);
		$this->assertArrayHasKey('videoFile', $modelMock->validationErrors);
	}

/**
 * 登録Videoデータ保存 videoFile mimeType エラー
 * アップロードしたファイルが Video::VIDEO_MIME_TYPE 以外の mimetype のため、エラー
 *
 * @return void
 */
	public function testAddSaveVideoVideoFileMimeType() {
		// テスト準備
		$roomId = 1;
		$contentsId = 4;
		$fileName = 'thumbnail1.mp4';	// 拡張子偽装したjpg
		$data = $this->_readyTestSaveVideoData($roomId, $contentsId, $fileName);
		$modelMock = $this->_readyVideoMock();

		$modelMock->addSaveVideo($data, $roomId);

		// テストファイル削除
		$this->_deleteTestFile();

		//var_dump($modelMock->validationErrors);
		$this->assertArrayHasKey('videoFile', $modelMock->validationErrors);
	}
}
