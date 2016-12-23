<?php
/**
 * VideosEditController::add()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('WorkflowControllerAddTest', 'Workflow.TestSuite');
App::uses('Video', 'Videos.Model');
App::uses('VideoTestUtil', 'Videos.Test/Case');

/**
 * VideosEditController::add()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Videos\Test\Case\Controller\VideosEditController
 */
class VideosEditControllerAddTest extends WorkflowControllerAddTest {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.videos.video',
		'plugin.videos.video_setting',
		'plugin.videos.block_setting_for_video',
		'plugin.videos.video_frame_setting',
		'plugin.likes.like',
		'plugin.likes.likes_user',
		'plugin.tags.tag',
		'plugin.tags.tags_content',
		'plugin.workflow.workflow_comment',
		'plugin.files.upload_file',
		'plugin.files.upload_files_content',
		'plugin.content_comments.content_comment',
		'plugin.categories.category',
		'plugin.categories.category_order',
		'plugin.categories.categories_language',
	);

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'videos';

/**
 * Controller name
 *
 * @var string
 */
	protected $_controller = 'videos_edit';

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		// ファイルアップロードの実ファイルが配置されなかったので、強制的に実ファイルを配置
		// アップロードパスの変更
		$tmpFolder = new TemporaryFolder();
		$this->controller->UploadFile = ClassRegistry::init('Files.UploadFile', true);
		$this->controller->UploadFile->uploadBasePath = $tmpFolder->path . '/';
		// テスト実ファイル配置
		$testFilePath = $tmpFolder->path . '/files/upload_file/real_file_name/2/14';
		$tmpFolder->create($testFilePath);
		$videoFilePath = APP . 'Plugin' . DS . 'Videos' . DS . 'Test' . DS . 'Fixture' . DS .
			'video1.mp4';
		$tmpFilePath = $testFilePath . DS . 'ef4ac246226cf2f9896c0d978c71541f.mp4';
		copy($videoFilePath, $tmpFilePath);
	}

/**
 * テストDataの取得
 *
 * @return array
 */
	private function __data() {
		$frameId = '6';
		$blockId = '2';
		$blockKey = 'block_1';

		// アップロードした一時ファイル作成
		$fileName = 'video1.mp4';
		$testUtil = new VideoTestUtil();
		$videoFileData = $testUtil->getFileData('Videos', $fileName, 'video/mp4');

		$data = array(
			'save_' . WorkflowComponent::STATUS_IN_DRAFT => null,
			'Frame' => array(
				'id' => $frameId,
			),
			'Block' => array(
				'id' => $blockId,
				'key' => $blockKey,
				'language_id' => '2',
				'room_id' => '2',
				'plugin_key' => $this->plugin,
			),
			'Video' => array(
				'id' => null,
				'key' => null,
				'block_id' => $blockId,
				'language_id' => '2',
				'status' => null,
				'title' => 'タイトル',
				Video::VIDEO_FILE_FIELD => $videoFileData
			),
			'WorkflowComment' => array(
				'comment' => 'WorkflowComment save test',
			),
		);

		return $data;
	}

/**
 * addアクションのGETテスト(ログインなし)用DataProvider
 *
 * ### 戻り値
 *  - urlOptions: URLオプション
 *  - assert: テストの期待値
 *  - exception: Exception
 *  - return: testActionの実行後の結果
 *
 * @return array
 */
	public function dataProviderAddGet() {
		$data = $this->__data();

		//テストデータ
		$results = array();
		// * ログインなし
		$results[0] = array(
			'urlOptions' => array(
				'frame_id' => $data['Frame']['id'],
				'block_id' => $data['Block']['id']
			),
			'assert' => null, 'exception' => 'ForbiddenException',
		);

		return $results;
	}

/**
 * addアクションのGETテスト(作成権限あり)用DataProvider
 *
 * ### 戻り値
 *  - urlOptions: URLオプション
 *  - assert: テストの期待値
 *  - exception: Exception
 *  - return: testActionの実行後の結果
 *
 * @return array
 */
	public function dataProviderAddGetByCreatable() {
		$data = $this->__data();

		//テストデータ
		$results = array();
		$results[0] = array(
			'urlOptions' => array(
				'frame_id' => $data['Frame']['id'],
				'block_id' => $data['Block']['id'],
			),
			'assert' => array('method' => 'assertNotEmpty'),
		);

		// * フレームID指定なしテスト
		array_push($results, Hash::merge($results[0], array(
			'urlOptions' => array('frame_id' => null, 'block_id' => $data['Block']['id']),
			'assert' => array('method' => 'assertNotEmpty'),
		)));

		return $results;
	}

/**
 * addアクションのPOSTテスト用DataProvider
 *
 * ### 戻り値
 *  - data: 登録データ
 *  - role: ロール
 *  - urlOptions: URLオプション
 *  - exception: Exception
 *  - return: testActionの実行後の結果
 *
 * @return array
 */
	public function dataProviderAddPost() {
		$data = $this->__data();

		//テストデータ
		$results = array();
		// * ログインなし
		$results[0] = array(
			'data' => $data, 'role' => null,
			'urlOptions' => array(
				'frame_id' => $data['Frame']['id'],
				'block_id' => $data['Block']['id']
			),
			'exception' => 'ForbiddenException'
		);

		// * 作成権限あり
		$results[1] = array(
			'data' => $data, 'role' => Role::ROOM_ROLE_KEY_GENERAL_USER,
			'urlOptions' => array(
				'frame_id' => $data['Frame']['id'],
				'block_id' => $data['Block']['id']
			),
		);

		// CakeException: Can not determine the mimetype. mimetype判定できなかった=>ファイルなし濃厚のエラー対応
		$fileName = 'video1.mp4';
		$testUtil = new VideoTestUtil();
		$tmpFilePath = $testUtil->readyTestFile('Videos', $fileName);
		$data['Video'][Video::VIDEO_FILE_FIELD]['name'] = $fileName;
		$data['Video'][Video::VIDEO_FILE_FIELD]['tmp_name'] = $tmpFilePath;

		// * フレームID指定なしテスト
		$results[2] = array(
			'data' => $data, 'role' => Role::ROOM_ROLE_KEY_ROOM_ADMINISTRATOR,
			'urlOptions' => array(
				'frame_id' => null,
				'block_id' => $data['Block']['id']),
		);

		return $results;
	}

/**
 * addアクションのValidationErrorテスト用DataProvider
 *
 * ### 戻り値
 *  - data: 登録データ
 *  - urlOptions: URLオプション
 *  - validationError: バリデーションエラー
 *
 * @return array
 */
	public function dataProviderAddValidationError() {
		$data = $this->__data();
		$data['Video']['title'] = null;
		$result = array(
			'data' => $data,
			'urlOptions' => array(
				'frame_id' => $data['Frame']['id'],
				'block_id' => $data['Block']['id']
			),
			'validationError' => array(),
		);

		//テストデータ
		$results = array();
		array_push($results, Hash::merge($result, array(
			'validationError' => array(
				'field' => 'Video.title',
				'value' => '',
				'message' => sprintf(__d('net_commons', 'Please input %s.'), __d('videos', 'title'))
			)
		)));

		return $results;
	}

/**
 * Viewのアサーション
 *
 * @param array $data テストデータ
 * @return void
 */
	private function __assertAddGet($data) {
		$this->assertInput(
			'input', 'data[Video][block_id]', $data['Block']['id'], $this->view
		);
		$this->assertInput(
			'input', 'data[Video][language_id]', $data['Video']['language_id'], $this->view
		);
	}

/**
 * view(ctp)ファイルのテスト(公開権限なし)
 *
 * @return void
 */
	public function testViewFileByCreatable() {
		TestAuthGeneral::login($this, Role::ROOM_ROLE_KEY_GENERAL_USER);

		//テスト実行
		$data = $this->__data();
		$this->_testGetAction(
			array(
				'action' => 'add',
				'frame_id' => $data['Frame']['id'],
				'block_id' => $data['Block']['id'],
			),
			array('method' => 'assertNotEmpty')
		);

		//チェック
		$this->__assertAddGet($data);
		$this->assertInput('button', 'save_' . WorkflowComponent::STATUS_IN_DRAFT, null, $this->view);
		$this->assertInput('button', 'save_' . WorkflowComponent::STATUS_APPROVED, null, $this->view);

		//debug($this->view);

		TestAuthGeneral::logout($this);
	}

/**
 * view(ctp)ファイルのテスト(公開権限なし) - FFMPEG=ON
 *
 * @return void
 */
	public function testViewFileByCreatableFfmpegOn() {
		$this->controller->Video->isFfmpegEnable = true;
		$this->testViewFileByCreatable();
	}

/**
 * view(ctp)ファイルのテスト(公開権限なし) - FFMPEG=OFF
 *
 * @return void
 */
	public function testViewFileByCreatableFfmpegOff() {
		$this->controller->Video->isFfmpegEnable = false;
		$this->testViewFileByCreatable();
	}

/**
 * view(ctp)ファイルのテスト(公開権限あり)
 *
 * @return void
 */
	public function testViewFileByPublishable() {
		//ログイン
		TestAuthGeneral::login($this);

		//テスト実行
		$data = $this->__data();
		$this->_testGetAction(
			array(
				'action' => 'add',
				'frame_id' => $data['Frame']['id'],
				'block_id' => $data['Block']['id'],
			),
			array('method' => 'assertNotEmpty')
		);

		//チェック
		$this->__assertAddGet($data);
		$this->assertInput('button', 'save_' . WorkflowComponent::STATUS_IN_DRAFT, null, $this->view);
		$this->assertInput('button', 'save_' . WorkflowComponent::STATUS_PUBLISHED, null, $this->view);

		//debug($this->view);

		//ログアウト
		TestAuthGeneral::logout($this);
	}
}
