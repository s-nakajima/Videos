<?php
/**
 * VideosAppController Test Case
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AuthGeneralControllerTest', 'AuthGeneral.Test/Case/Controller');
App::uses('NetCommonsFrameComponent', 'NetCommons.Controller/Component');
App::uses('NetCommonsBlockComponent', 'NetCommons.Controller/Component');
App::uses('NetCommonsRoomRoleComponent', 'NetCommons.Controller/Component');
App::uses('RolesControllerTest', 'Roles.Test/Case/Controller');
App::uses('YAControllerTestCase', 'NetCommons.TestSuite');
App::uses('ContentCommentsComponent', 'ContentComments.Controller/Component');

/**
 * VideosAppController Test Case
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Videos\Test\Case\Controller
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class VideosAppTest extends YAControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.blocks.block_role_permission',
		'plugin.boxes.boxes_page',
		'plugin.containers.container',
		'plugin.containers.containers_page',
		'plugin.content_comments.content_comment',
		'plugin.files.file',
		'plugin.files.files_plugin',
		'plugin.files.files_room',
		'plugin.files.files_user',
		'plugin.frames.box',
		'plugin.net_commons.site_setting',
		'plugin.m17n.language',
		'plugin.m17n.languages_page',
		'plugin.roles.default_role_permission',
		'plugin.rooms.roles_room',
		'plugin.rooms.roles_rooms_user',
		'plugin.rooms.room',
		'plugin.rooms.room_role_permission',
		'plugin.pages.page',
		'plugin.pages.space',
		'plugin.videos.block',
		'plugin.videos.comment',
		'plugin.videos.frame',
		'plugin.videos.plugin',
		'plugin.videos.user',
		'plugin.videos.user_attributes_user',
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
		Configure::write('Config.language', null);
		CakeSession::write('Auth.User', null);
		parent::tearDown();
	}

/**
 * testIndex method
 *
 * @return void
 */
	public function testIndex() {
	}
}
