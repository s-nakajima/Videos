<?php
/**
 * VideoApp Test Case
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('YACakeTestCase', 'NetCommons.TestSuite');

/**
 * VideoApp Test Case
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\ContentComments\Test\Case\Model
 * @property Block $Block
 * @property NetCommonsBlockComponent $NetCommonsBlock
 * @property Video $Video
 * @property VideoBlockSetting $VideoBlockSetting
 * @property VideoFrameSetting $VideoFrameSetting
 * @property VideoViewLog $VideoViewLog
 */
class VideoAppTest extends YACakeTestCase {

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
		parent::tearDown();
	}

/**
 * testIndex method
 *
 * @return void
 */
	public function testIndex() {
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
