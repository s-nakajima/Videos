<?php
/**
 * VideoValidationAddNoConvertSaveVideoTest Case
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('VideoValidationTestBase', 'Videos.Test/Case/Model');

/**
 * VideoValidationAddNoConvertSaveVideoTest Case
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\ContentComments\Test\Case\Model
 */
class VideoValidationAddNoConvertSaveVideoTest extends VideoValidationTestBase {

/**
 * 登録Videoデータ保存 動画を自動変換しない title required エラー
 * $data['Video']['title'] keyなしのため、エラー
 *
 * @return void
 */
	public function testAddNoConvertSaveVideoTitleRequired() {
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
				//'title' => '動画タイトル名',
			)),
			array('Comment' => array(
				'block_key' => $blockKey,
				'comment' => '承認コメント',
			))
		);
		$modelMock = $this->getMockForModel('Videos.Video', ['isFfmpegEnable']);
		$modelMock->expects($this->any())
			->method('isFfmpegEnable')
			->will($this->returnValue(false));
		// 暫定対応(;'∀') SQLSTATE[42S22]: Column not found: 1054 Unknown column 'Block.language_id' in 'on clause'
		$modelMock->hasOne = array();

		$modelMock->addNoConvertSaveVideo($data);

		$this->assertArrayHasKey('title', $modelMock->validationErrors);
	}

/**
 * 登録Videoデータ保存 動画を自動変換しない title notEmpty エラー
 * $data['Video']['title'] = '' のため、エラー
 *
 * @return void
 */
	public function testAddNoConvertSaveVideoTitleNotEmpty() {
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
				'title' => '',
			)),
			array('Comment' => array(
				'block_key' => $blockKey,
				'comment' => '承認コメント',
			))
		);
		$modelMock = $this->getMockForModel('Videos.Video', ['isFfmpegEnable']);
		$modelMock->expects($this->any())
			->method('isFfmpegEnable')
			->will($this->returnValue(false));
		// 暫定対応(;'∀') SQLSTATE[42S22]: Column not found: 1054 Unknown column 'Block.language_id' in 'on clause'
		$modelMock->hasOne = array();

		$modelMock->addNoConvertSaveVideo($data);

		$this->assertArrayHasKey('title', $modelMock->validationErrors);
	}

/**
 * 登録Videoデータ保存 動画を自動変換しない block_id required エラー
 * $data['Video']['block_id'] keyなしのため、エラー
 *
 * @return void
 */
	public function testAddNoConvertSaveVideoBlockIdRequired() {
		$status = WorkflowComponent::STATUS_APPROVED;
		//$blockId = 2;
		$languageId = 2;
		$blockKey = 'block_2';
		// 登録データ作成
		$video = $this->Video->create();
		$data = Hash::merge(
			$video,
			array($this->Video->alias => array(
				'status' => $status,
				//'block_id' => $blockId,
				'language_id' => $languageId,
				'title' => '動画タイトル名',
			)),
			array('Comment' => array(
				'block_key' => $blockKey,
				'comment' => '承認コメント',
			))
		);
		$modelMock = $this->getMockForModel('Videos.Video', ['isFfmpegEnable']);
		$modelMock->expects($this->any())
			->method('isFfmpegEnable')
			->will($this->returnValue(false));
		// 暫定対応(;'∀') SQLSTATE[42S22]: Column not found: 1054 Unknown column 'Block.language_id' in 'on clause'
		$modelMock->hasOne = array();

		$modelMock->addNoConvertSaveVideo($data);

		$this->assertArrayHasKey('block_id', $modelMock->validationErrors);
	}

/**
 * 登録Videoデータ保存 動画を自動変換しない block_id numeric エラー
 * $data['Video']['block_id'] = 数値以外のため、エラー
 *
 * @return void
 */
	public function testAddNoConvertSaveVideoBlockIdNumeric() {
		$status = WorkflowComponent::STATUS_APPROVED;
		$blockId = 'hoge';
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
		$modelMock = $this->getMockForModel('Videos.Video', ['isFfmpegEnable']);
		$modelMock->expects($this->any())
			->method('isFfmpegEnable')
			->will($this->returnValue(false));
		// 暫定対応(;'∀') SQLSTATE[42S22]: Column not found: 1054 Unknown column 'Block.language_id' in 'on clause'
		$modelMock->hasOne = array();

		$modelMock->addNoConvertSaveVideo($data);

		$this->assertArrayHasKey('block_id', $modelMock->validationErrors);
	}

/**
 * 登録Videoデータ保存 動画を自動変換しない videoFile uploadError エラー
 * $data['Video']['videoFile']['error'] = 4のため、エラー
 *
 * @return void
 */
	public function testAddNoConvertSaveVideoVideoFileUploadError() {
		// テスト準備
		$roomId = 1;
		$data = $this->_readyTestSaveVideoData($roomId);
		$data['Video'][Video::VIDEO_FILE_FIELD]['error'] = 4;

		$modelMock = $this->getMockForModel('Videos.Video', ['isFfmpegEnable']);
		$modelMock->expects($this->any())
			->method('isFfmpegEnable')
			->will($this->returnValue(false));
		// 暫定対応(;'∀') SQLSTATE[42S22]: Column not found: 1054 Unknown column 'Block.language_id' in 'on clause'
		$modelMock->hasOne = array();

		$modelMock->addNoConvertSaveVideo($data);

		// テストファイル削除
		$this->_deleteTestFile();

		//var_dump($modelMock->validationErrors);
		$this->assertArrayHasKey('videoFile', $modelMock->validationErrors);
	}

/**
 * 登録Videoデータ保存 動画を自動変換しない videoFile Extension エラー
 * アップロードしたファイルが Video::VIDEO_EXTENSION 以外の拡張子のため、エラー
 *
 * @return void
 */
	public function testAddNoConvertSaveVideoVideoFileExtension() {
		// テスト準備
		$roomId = 1;
		$contentsId = 2;
		$fileName = 'thumbnail1.jpg';
		$data = $this->_readyTestSaveVideoData($roomId, $contentsId, $fileName);

		$modelMock = $this->getMockForModel('Videos.Video', ['isFfmpegEnable']);
		$modelMock->expects($this->any())
			->method('isFfmpegEnable')
			->will($this->returnValue(false));
		// 暫定対応(;'∀') SQLSTATE[42S22]: Column not found: 1054 Unknown column 'Block.language_id' in 'on clause'
		$modelMock->hasOne = array();

		$modelMock->addNoConvertSaveVideo($data);

		// テストファイル削除
		$this->_deleteTestFile();

		//var_dump($modelMock->validationErrors);
		$this->assertArrayHasKey('videoFile', $modelMock->validationErrors);
	}

/**
 * 登録Videoデータ保存 動画を自動変換しない videoFile mimeType エラー
 * アップロードしたファイルが Video::VIDEO_MIME_TYPE 以外の mimetype のため、エラー
 *
 * @return void
 */
	public function testAddNoConvertSaveVideoVideoFileMimeType() {
		// テスト準備
		$roomId = 1;
		$contentsId = 4;
		$fileName = 'thumbnail1.mp4';	// 拡張子偽装したjpg
		$data = $this->_readyTestSaveVideoData($roomId, $contentsId, $fileName);

		$modelMock = $this->getMockForModel('Videos.Video', ['isFfmpegEnable']);
		$modelMock->expects($this->any())
			->method('isFfmpegEnable')
			->will($this->returnValue(false));
		// 暫定対応(;'∀') SQLSTATE[42S22]: Column not found: 1054 Unknown column 'Block.language_id' in 'on clause'
		$modelMock->hasOne = array();

		$modelMock->addNoConvertSaveVideo($data);

		// テストファイル削除
		$this->_deleteTestFile();

		//var_dump($modelMock->validationErrors);
		$this->assertArrayHasKey('videoFile', $modelMock->validationErrors);
	}

/**
 * 登録Videoデータ保存 動画を自動変換しない thumbnail uploadError エラー
 * $data['Video']['thumbnail']['error'] = 4のため、エラー
 *
 * @return void
 */
	public function testAddNoConvertSaveVideoThumbnailUploadError() {
		// テスト準備
		$roomId = 1;
		$data = $this->_readyTestSaveVideoData($roomId);
		$data['Video'][Video::THUMBNAIL_FIELD]['error'] = 4;

		$modelMock = $this->getMockForModel('Videos.Video', ['isFfmpegEnable']);
		$modelMock->expects($this->any())
			->method('isFfmpegEnable')
			->will($this->returnValue(false));
		// 暫定対応(;'∀') SQLSTATE[42S22]: Column not found: 1054 Unknown column 'Block.language_id' in 'on clause'
		$modelMock->hasOne = array();

		$modelMock->addNoConvertSaveVideo($data);

		// テストファイル削除
		$this->_deleteTestFile();

		//var_dump($modelMock->validationErrors);
		$this->assertArrayHasKey('thumbnail', $modelMock->validationErrors);
	}

/**
 * 登録Videoデータ保存 動画を自動変換しない thumbnail extension エラー
 * アップロードしたファイルが Video::THUMBNAIL_EXTENSION 以外の拡張子のため、エラー
 *
 * @return void
 */
	public function testAddNoConvertSaveVideoThumbnailExtension() {
		// テスト準備
		$roomId = 1;
		$data = $this->_readyTestSaveVideoData($roomId);

		$fileName = 'video1.mp4';
		$contentsId = 1;
		// テストファイル準備
		$tmpFullPath = $this->_readyTestFile($contentsId, $roomId, $fileName);

		$data = Hash::merge(
			$data,
			array($this->Video->alias => array(
				Video::THUMBNAIL_FIELD => array(
					'name' => $fileName,
					'type' => 'image/jpeg',
					'tmp_name' => $tmpFullPath,
					'size' => 9999,
					'error' => 0,
				),
			)),
			array(Video::THUMBNAIL_FIELD => array(
				'FilesPlugin' => array(
					'plugin_key' => 'videos'
				),
				'File' => array(
					'name' => $fileName,
					'extension' => 'jpg',
					'slug' => 'thumbnail1',
					'role_type' => 'room_file_role',
				),
			))
		);
		$modelMock = $this->getMockForModel('Videos.Video', ['isFfmpegEnable']);
		$modelMock->expects($this->any())
			->method('isFfmpegEnable')
			->will($this->returnValue(false));
		// 暫定対応(;'∀') SQLSTATE[42S22]: Column not found: 1054 Unknown column 'Block.language_id' in 'on clause'
		$modelMock->hasOne = array();

		$modelMock->addNoConvertSaveVideo($data);

		// テストファイル削除
		$this->_deleteTestFile();

		//var_dump($modelMock->validationErrors);
		$this->assertArrayHasKey('thumbnail', $modelMock->validationErrors);
	}

/**
 * 登録Videoデータ保存 動画を自動変換しない thumbnail mimeType エラー
 * アップロードしたファイルが Video::THUMBNAIL_MIME_TYPE 以外の mimetype のため、エラー
 *
 * @return void
 */
	public function testAddNoConvertSaveVideoThumbnailMimeType() {
		// テスト準備
		$roomId = 1;
		$data = $this->_readyTestSaveVideoData($roomId);

		$contentsId = 4;
		$fileName = 'video1.jpg';	// 拡張子偽装したmp4
		// テストファイル準備
		$tmpFullPath = $this->_readyTestFile($contentsId, $roomId, $fileName);

		$data = Hash::merge(
			$data,
			array($this->Video->alias => array(
				Video::THUMBNAIL_FIELD => array(
					'name' => $fileName,
					'type' => 'image/jpeg',
					'tmp_name' => $tmpFullPath,
					'size' => 9999,
					'error' => 0,
				),
			)),
			array(Video::THUMBNAIL_FIELD => array(
				'FilesPlugin' => array(
					'plugin_key' => 'videos'
				),
				'File' => array(
					'name' => $fileName,
					'extension' => 'jpg',
					'slug' => 'video1',
					'role_type' => 'room_file_role',
				),
			))
		);
		$modelMock = $this->getMockForModel('Videos.Video', ['isFfmpegEnable']);
		$modelMock->expects($this->any())
			->method('isFfmpegEnable')
			->will($this->returnValue(false));
		// 暫定対応(;'∀') SQLSTATE[42S22]: Column not found: 1054 Unknown column 'Block.language_id' in 'on clause'
		$modelMock->hasOne = array();

		$modelMock->addNoConvertSaveVideo($data);

		// テストファイル削除
		$this->_deleteTestFile();

		//var_dump($modelMock->validationErrors);
		$this->assertArrayHasKey('thumbnail', $modelMock->validationErrors);
	}
}
