<?php
/**
 * VideoValidationTest Base
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('VideoTestBase', 'Videos.Test/Case/Model');

/**
 * VideoValidationTest Base
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\ContentComments\Test\Case\Model
 */
class VideoValidationTestBase extends VideoTestBase {

/**
 * テスト saveVideo data準備
 *
 * @param int $roomId ルームID
 * @param int $contentsId コンテンツID
 * @param string $fileName ファイル名
 * @return array
 */
	protected function _readyTestSaveVideoData($roomId, $contentsId = 1, $fileName = 'video1.mp4') {
		// テストファイル準備
		$tmpFullPath = $this->_readyTestFile($contentsId, $roomId, $fileName);

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
				'mp4_id' => $contentsId,
				Video::VIDEO_FILE_FIELD => array(
					'name' => $fileName,
					'type' => 'video/mp4',
					// tmp_nameに指定するファイルは、一時的なアップロードファイルの扱いなので、ここに指定したファイルは自動削除される
					'tmp_name' => $tmpFullPath,
					'size' => 9999,
					'error' => 0,
				),
			)),
			array('Comment' => array(
				'block_key' => $blockKey,
				'comment' => '承認コメント',
			)),
			array(Video::VIDEO_FILE_FIELD => array(
				'FilesPlugin' => array(
					'plugin_key' => 'videos'
				),
				'File' => array(
					'name' => $fileName,
					'extension' => 'mp4',
					'slug' => 'video1',
					'role_type' => 'room_file_role',
				),
			))
		);

		return $data;
	}

/**
 * Video modelモック準備
 *
 * @return Model
 */
	protected function _readyVideoMock() {
		// 暫定対応(;'∀') 登録後、$this->saveConvertVideo($data, $video, $roomId)の下記で落ちるので、モックで対応
		//   $noConvert = $Model->FileModel->findById($video['Video']['mp4_id']);
		//   Uninitialized string offset: -1
		// modelモック
		$modelMock = $this->getMockForModel('Videos.Video', ['saveConvertVideo']);
		$modelMock->expects($this->any())
			->method('saveConvertVideo')
			->will($this->returnValue(true));
		// 暫定対応(;'∀') SQLSTATE[42S22]: Column not found: 1054 Unknown column 'Block.language_id' in 'on clause'
		$modelMock->hasOne = array();

		return $modelMock;
	}
}
