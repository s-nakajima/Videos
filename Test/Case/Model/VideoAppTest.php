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
 * @property Video $Video
 */
class VideoAppTest extends YACakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.blocks.block',
		'plugin.files.files_plugin',
		'plugin.files.files_room',
		'plugin.files.files_user',
		'plugin.m17n.language',
		//'plugin.m17n.languages_page',
		'plugin.rooms.room',
		'plugin.tags.tag',
		'plugin.tags.tags_content',
		'plugin.users.user',
		'plugin.videos.file',
		'plugin.videos.video',
		'plugin.videos.video_view_log',	// VideoViewLog model用
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Video = ClassRegistry::init('Videos.Video');
		$this->Video->FileModel = ClassRegistry::init('Files.FileModel');	// Behavior Test用
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Video);
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

/**
 * テストファイル準備
 *
 * @param int $contentsId コンテンツID
 * @param int $roomId ルームID
 * @param array $fileName ファイル名
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
}
