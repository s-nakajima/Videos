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

App::uses('NetCommonsControllerTestCase', 'NetCommons.TestSuite');

/**
 * VideosTestBase
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Videos\Test\Case\Controller
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class VideosTestBase extends NetCommonsControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		//'plugin.comments.comment',
		'plugin.content_comments.content_comment',
		'plugin.files.upload_file',
		'plugin.files.upload_files_content',
		'plugin.likes.like',
		'plugin.pages.page',
		'plugin.tags.tag',
		'plugin.tags.tags_content',
		'plugin.videos.video',
		'plugin.videos.video_block_setting',
		'plugin.videos.video_frame_setting',
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
