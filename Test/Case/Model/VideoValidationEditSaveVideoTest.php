<?php
/**
 * VideoValidationEditSaveVideoTest Case
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('VideoValidationTestBase', 'Videos.Test/Case/Model');

/**
 * VideoValidationEditSaveVideoTest Case
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\ContentComments\Test\Case\Model
 */
class VideoValidationEditSaveVideoTest extends VideoValidationTestBase {

/**
 * 編集Videoデータ保存 title required エラー
 * $data['Video']['title'] keyなしのため、エラー
 *
 * @return void
 */
	public function testEditSaveVideoTitleRequired() {
		// テスト準備
		$roomId = 1;
		$data = $this->_readyTestSaveVideoData($roomId);
		// 検索
		$videoId = 1;
		$video = $this->Video->findById($videoId);
		$data = Hash::merge(
			$data,
			$video
		);
		unset($data['Video']['title']);

		$modelMock = $this->_readyVideoMock();
		$modelMock->editSaveVideo($data, $roomId);

		// テストファイル削除
		$this->_deleteTestFile();

		$this->assertArrayHasKey('title', $modelMock->validationErrors);
	}

/**
 * 編集Videoデータ保存 title notEmpty エラー
 * $data['Video']['title'] = nullのため、エラー
 *
 * @return void
 */
	public function testEditSaveVideoTitleNotEmpty() {
		// テスト準備
		$roomId = 1;
		$data = $this->_readyTestSaveVideoData($roomId);
		// 検索
		$videoId = 1;
		$video = $this->Video->findById($videoId);
		$data = Hash::merge(
			$data,
			$video
		);
		$data['Video']['title'] = null;

		$modelMock = $this->_readyVideoMock();
		$modelMock->editSaveVideo($data, $roomId);

		// テストファイル削除
		$this->_deleteTestFile();

		$this->assertArrayHasKey('title', $modelMock->validationErrors);
	}

/**
 * 編集Videoデータ保存 block_id required エラー
 * $data['Video']['block_id'] keyなしのため、エラー
 *
 * @return void
 */
	public function testEditSaveVideoBlockIdRequired() {
		// テスト準備
		$roomId = 1;
		$data = $this->_readyTestSaveVideoData($roomId);
		// 検索
		$videoId = 1;
		$video = $this->Video->findById($videoId);
		$data = Hash::merge(
			$data,
			$video
		);
		unset($data['Video']['block_id']);

		$modelMock = $this->_readyVideoMock();
		$modelMock->editSaveVideo($data, $roomId);

		// テストファイル削除
		$this->_deleteTestFile();

		$this->assertArrayHasKey('block_id', $modelMock->validationErrors);
	}

/**
 * 編集Videoデータ保存 block_id numeric エラー
 * $data['Video']['block_id'] = 数値以外のため、エラー
 *
 * @return void
 */
	public function testEditSaveVideoBlockIdNumeric() {
		// テスト準備
		$roomId = 1;
		$data = $this->_readyTestSaveVideoData($roomId);
		// 検索
		$videoId = 1;
		$video = $this->Video->findById($videoId);
		$data = Hash::merge(
			$data,
			$video
		);
		$data['Video']['block_id'] = 'hoge';

		$modelMock = $this->_readyVideoMock();
		$modelMock->editSaveVideo($data, $roomId);

		// テストファイル削除
		$this->_deleteTestFile();

		$this->assertArrayHasKey('block_id', $modelMock->validationErrors);
	}

/**
 * 編集Videoデータ保存 thumbnail extension エラー
 * アップロードしたファイルが Video::THUMBNAIL_EXTENSION 以外の拡張子のため、エラー
 *
 * @return void
 */
	public function testEditSaveVideoThumbnailExtension() {
		// テスト準備
		$roomId = 1;
		$data = $this->_readyTestSaveVideoData($roomId);

		$fileName = 'video1.mp4';
		$contentsId = 1;
		// テストファイル準備
		$tmpFullPath = $this->_readyTestFile($contentsId, $roomId, $fileName);

		// 検索
		$videoId = 1;
		$video = $this->Video->findById($videoId);

		$status = NetCommonsBlockComponent::STATUS_APPROVED;
		$data = Hash::merge(
			$data,
			$video,
			array($this->Video->alias => array(
				'status' => $status,
				Video::THUMBNAIL_FIELD => array(
					'name' => $fileName,
					//'type' => 'image/jpeg',
					'type' => 'video/mp4',
					'tmp_name' => $tmpFullPath,
					'size' => 9999,
					'error' => 0,
				),
			))
		);

		$modelMock = $this->_readyVideoMock();
		$modelMock->editSaveVideo($data, $roomId);

		// テストファイル削除
		$this->_deleteTestFile();

		//var_dump($modelMock->validationErrors);
		$this->assertArrayHasKey('thumbnail', $modelMock->validationErrors);
	}

/**
 * 編集Videoデータ保存 thumbnail mimeType エラー
 * アップロードしたファイルが Video::THUMBNAIL_MIME_TYPE 以外の mimetype のため、エラー
 *
 * @return void
 */
	public function testEditSaveVideoThumbnailMimeType() {
		// テスト準備
		$roomId = 1;
		$data = $this->_readyTestSaveVideoData($roomId);

		$fileName = 'video1.jpg';	// 拡張子偽装したmp4
		$contentsId = 4;
		// テストファイル準備
		$tmpFullPath = $this->_readyTestFile($contentsId, $roomId, $fileName);

		// 検索
		$videoId = 1;
		$video = $this->Video->findById($videoId);

		$status = NetCommonsBlockComponent::STATUS_APPROVED;
		$data = Hash::merge(
			$data,
			$video,
			array($this->Video->alias => array(
				'status' => $status,
				Video::THUMBNAIL_FIELD => array(
					'name' => $fileName,
					'type' => 'image/jpeg',
					'tmp_name' => $tmpFullPath,
					'size' => 9999,
					'error' => 0,
				),
			))
		);

		$modelMock = $this->_readyVideoMock();
		$modelMock->editSaveVideo($data, $roomId);

		// テストファイル削除
		$this->_deleteTestFile();

		//var_dump($modelMock->validationErrors);
		$this->assertArrayHasKey('thumbnail', $modelMock->validationErrors);
	}
}
