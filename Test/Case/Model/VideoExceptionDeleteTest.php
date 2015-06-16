<?php
/**
 * VideoExceptionDeleteTest Case
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('VideoTestBase', 'Videos.Test/Case/Model');

/**
 * VideoExceptionDeleteTest Case
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\ContentComments\Test\Case\Model
 */
class VideoExceptionDeleteTest extends VideoTestBase {

/**
 * Videoデータ削除 例外テスト
 *
 * @return void
 */
	public function testDeleteVideoException() {
		$this->setExpectedException('InternalErrorException');

		$data = array($this->Video->alias => array(
			'id' => 1,
			'key' => 'video_1',
		));

		// modelモック
		$modelMock = $this->getMockForModel('Videos.Video', ['deleteAll']);
		$modelMock->expects($this->any())
			->method('deleteAll')
			->will($this->returnValue(false));

		$modelMock->deleteVideo($data);
	}

/**
 * Videoデータ削除 承認コメント例外テスト
 *
 * @return void
 */
	public function testDeleteVideoCommnetException() {
		$this->setExpectedException('InternalErrorException');

		$data = array($this->Video->alias => array(
			'id' => 1,
			'key' => 'video_1',
		));

		// commnet modelモック
		$commnetMock = $this->getMockForModel('Comments.Comment', ['deleteAll']);
		$commnetMock->expects($this->any())
			->method('deleteAll')
			->will($this->returnValue(false));
		$this->Video->Commnet = $commnetMock;

		$this->Video->deleteVideo($data);
	}

/**
 * Videoデータ削除 コンテンツコメント例外テスト
 *
 * @return void
 */
	public function testDeleteVideoContentCommnetException() {
		$this->setExpectedException('InternalErrorException');

		$data = array($this->Video->alias => array(
			'id' => 1,
			'key' => 'video_1',
		));

		// modelモック
		$modelMock = $this->getMockForModel('ContentComments.ContentComment', ['deleteAll']);
		$modelMock->expects($this->any())
			->method('deleteAll')
			->will($this->returnValue(false));
		$this->Video->ContentComment = $modelMock;

		$this->Video->deleteVideo($data);
	}

/**
 * Videoデータ削除 タグコンテンツ例外テスト
 *
 * @return void
 */
	public function testDeleteVideoTagsContentException() {
		$this->setExpectedException('InternalErrorException');

		$data = array($this->Video->alias => array(
			'id' => 1,
			'key' => 'video_1',
		));

		// modelモック
		$modelMock = $this->getMockForModel('TagsContents.TagsContent', ['deleteAll']);
		$modelMock->expects($this->any())
			->method('deleteAll')
			->will($this->returnValue(false));
		$this->Video->TagsContent = $modelMock;

		$this->Video->deleteVideo($data);
	}

/**
 * Videoデータ削除 いいね例外テスト
 *
 * @return void
 */
	public function testDeleteVideoLikeException() {
		$this->setExpectedException('InternalErrorException');

		$data = array($this->Video->alias => array(
			'id' => 1,
			'key' => 'video_1',
		));

		// modelモック
		$modelMock = $this->getMockForModel('Likes.Like', ['deleteAll']);
		$modelMock->expects($this->any())
			->method('deleteAll')
			->will($this->returnValue(false));
		$this->Video->Like = $modelMock;

		$this->Video->deleteVideo($data);
	}
}
