<?php
/**
 * Video::deleteVideo()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('WorkflowDeleteTest', 'Workflow.TestSuite');
App::uses('VideoFixture', 'Videos.Test/Fixture');

/**
 * Video::deleteVideo()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Videos\Test\Case\Model\Video
 */
class VideoDeleteVideoTest extends WorkflowDeleteTest {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.videos.video',
		'plugin.videos.video_block_setting',
		'plugin.videos.video_frame_setting',
		'plugin.likes.like',
		'plugin.likes.likes_user',
		'plugin.tags.tag',
		'plugin.tags.tags_content',
		'plugin.workflow.workflow_comment',
		'plugin.files.upload_file',
		'plugin.files.upload_files_content',
		'plugin.content_comments.content_comment',
	);

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'videos';

/**
 * Model name
 *
 * @var string
 */
	protected $_modelName = 'Video';

/**
 * Method name
 *
 * @var string
 */
	protected $_methodName = 'deleteVideo';

/**
 * Delete用DataProvider
 *
 * ### 戻り値
 *  - data: 削除データ
 *  - associationModels: 削除確認の関連モデル array(model => conditions)
 *
 * @return array テストデータ
 */
	public function dataProviderDelete() {
		$data['Video'] = (new VideoFixture())->records[0];
		$association = array();

		$results = array();
		$results[0] = array($data, $association);

		return $results;
	}

/**
 * ExceptionError用DataProvider
 *
 * ### 戻り値
 *  - data 登録データ
 *  - mockModel Mockのモデル
 *  - mockMethod Mockのメソッド
 *
 * @return array テストデータ
 */
	public function dataProviderDeleteOnExceptionError() {
		$data = $this->dataProviderDelete()[0][0];

		return array(
			array($data, 'Videos.Video', 'deleteAll'),
			array($data, 'Likes.Like', 'deleteAll'),
			array($data, 'Tags.TagsContent', 'deleteAll'),
			array($data, 'Files.UploadFile', 'deleteAll'),
			array($data, 'Files.UploadFilesContent', 'deleteAll'),
		);
	}

}
