<?php
/**
 * VideosTestBase
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AuthGeneralControllerTest', 'AuthGeneral.Test/Case/Controller');
App::uses('RolesControllerTest', 'Roles.Test/Case/Controller');
App::uses('YAControllerTestCase', 'NetCommons.TestSuite');

/**
 * VideosTestBase
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Videos\Test\Case\Controller
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class VideosTestBase extends YAControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.comments.comment',
		'plugin.content_comments.content_comment',
		//'plugin.files.file',
		//'plugin.files.files_plugin',
		//'plugin.files.files_room',
		//'plugin.files.files_user',
		'plugin.likes.like',
		'plugin.pages.page',
		'plugin.tags.tag',
		'plugin.tags.tags_content',
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
		Configure::write('Config.language', 'ja');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->controller);
		Configure::write('Config.language', null);
		CakeSession::write('Auth.User', null);
		parent::tearDown();
	}
}
