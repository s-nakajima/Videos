<?php
/**
 * VideoTest Base
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('YACakeTestCase', 'NetCommons.TestSuite');

/**
 * VideoTest Base
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\ContentComments\Test\Case\Model
 * @property Block $Block
 * @property FileModel $FileModel
 * @property NetCommonsBlockComponent $NetCommonsBlock
 * @property Video $Video
 * @property VideoBlockSetting $VideoBlockSetting
 * @property VideoFrameSetting $VideoFrameSetting
 * @property VideoViewLog $VideoViewLog
 */
class VideoTestBase extends YACakeTestCase {

/**
 * use component
 *
 * @var array
 */
	public $components = array(
		'NetCommons.NetCommonsBlock',
	);

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.boxes.box',
		'plugin.blocks.block',
		'plugin.blocks.block_role_permission',
		'plugin.comments.comment',
		'plugin.content_comments.content_comment',
		'plugin.files.files_plugin',
		'plugin.files.files_room',
		'plugin.files.files_user',
		'plugin.frames.frame',
		'plugin.likes.like',
		'plugin.m17n.language',
		'plugin.plugin_manager.plugin',
		'plugin.roles.role',
		'plugin.roles.default_role_permission',
		'plugin.rooms.room',
		'plugin.rooms.room_role',
		'plugin.rooms.room_role_permission',
		'plugin.rooms.roles_room',
		'plugin.tags.tag',
		'plugin.tags.tags_content',
		'plugin.users.user',
		'plugin.users.user_attributes_user',
		'plugin.videos.file',
		'plugin.videos.video',
		'plugin.videos.video_block_setting',
		'plugin.videos.video_frame_setting',
		'plugin.videos.video_view_log',
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Block = ClassRegistry::init('Blocks.Block');							// VideoBlockSetting Test用
		$this->Video = ClassRegistry::init('Videos.Video');
		$this->Video->ContentComment = ClassRegistry::init('ContentComments.ContentComment');
		$this->Video->FileModel = ClassRegistry::init('Files.FileModel');
		$this->VideoBlockSetting = ClassRegistry::init('Videos.VideoBlockSetting');
		$this->VideoFrameSetting = ClassRegistry::init('Videos.VideoFrameSetting');
		$this->VideoViewLog = ClassRegistry::init('Videos.VideoViewLog');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Block);
		unset($this->Video);
		unset($this->VideoBlockSetting);
		unset($this->VideoFrameSetting);
		unset($this->VideoViewLog);
		CakeSession::write('Auth.User', null);
		parent::tearDown();
	}

/**
 * テストファイル準備
 *
 * @param int $contentsId コンテンツID
 * @param int $roomId ルームID
 * @param string $fileName ファイル名
 * @return void
 */
	protected function _readyTestFile($contentsId = 1, $roomId = 1, $fileName = 'video1.mp4') {
		// ファイル準備
		// 本来は      /{TMP}/file/{roomId}/{contentsId} だけど、
		// テストの為、/{TMP}/file/{roomId}/{fileId} で対応。　　そのため、{contentsId}、{fileId}は同じにしないと、削除で失敗する。
		$filePath = TMP . 'tests' . DS . 'file' . DS . $roomId . DS . $contentsId;
		$folder = new Folder();
		$folder->create($filePath);
		$file = new File(APP . 'Plugin' . DS . 'Videos' . DS . 'Test' . DS . 'Fixture' . DS . $fileName);
		$file->copy($filePath . DS . $fileName);
		$file->close();
	}

/**
 * テストファイル削除
 *
 * @return void
 */
	protected function _deleteTestFile() {
		// アップロードテストのためのディレクトリ削除
		$folder = new Folder();
		$folder->delete(TMP . 'tests' . DS . 'file');
	}

/**
 * saveVideoBlockSetting で保存する $data 取得
 *
 * @return array
 */
	protected function _getVideoBlockSettingTestData() {
		// videoBlockSetting 取得
		$blockKey = 'block_1';
		$roomId = 1;
		$videoBlockSetting = $this->VideoBlockSetting->getVideoBlockSetting($blockKey, $roomId);

		// ブロック 初期値 取得
		$block = $this->Block->create(array(
			'name' => __d('videos', 'New channel %s', date('YmdHis')),
		));

		$frameId = 1;
		$languageId = 2;
		$pluginKey = 'videos';
		$data = Hash::merge(
			$videoBlockSetting,
			$block,
			//$this->data,
			array('Frame' => array('id' => $frameId)),
			array('Block' => array(
				'room_id' => $roomId,
				'language_id' => $languageId,
				'plugin_key' => $pluginKey,
			))
		);

		return $data;
	}
}
