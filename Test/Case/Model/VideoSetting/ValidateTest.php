<?php
/**
 * VideoSetting::validate()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsValidateTest', 'NetCommons.TestSuite');
App::uses('VideoSettingFixture', 'Videos.Test/Fixture');

/**
 * VideoSetting::validate()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Videos\Test\Case\Model\VideoSetting
 */
class VideoSettingValidateTest extends NetCommonsValidateTest {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.videos.video_setting',
		'plugin.videos.block_setting_for_video',
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
	protected $_modelName = 'VideoSetting';

/**
 * Method name
 *
 * @var string
 */
	protected $_methodName = 'validates';

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		Current::write('Plugin.key', $this->plugin);
	}

/**
 * ValidationErrorのDataProvider
 *
 * ### 戻り値
 *  - data 登録データ
 *  - field フィールド名
 *  - value セットする値
 *  - message エラーメッセージ
 *  - overwrite 上書きするデータ(省略可)
 *
 * @return array テストデータ
 * @see VideoSetting::beforeValidate()
 * @see NetCommonsValidateTest::testValidationError()
 */
	public function dataProviderValidationError() {
		$data['VideoSetting'] = (new VideoSettingFixture())->records[0];
		//$data['BlocksLanguage']['name'] = 'ブロック名';

		//debug($data);
		return array(
			//			array('data' => $data, 'field' => 'name', 'value' => null,
			//				'message' => sprintf(
			//					__d('net_commons', 'Please input %s.'), __d('videos', 'Channel name')
			//				)
			//			),
			// BlockSettingのvalidateテスト
			array('data' => $data, 'field' => 'use_like', 'value' => 'dummy',
				'message' => __d('net_commons', 'Invalid request.')),
			array('data' => $data, 'field' => 'use_unlike', 'value' => 'dummy',
				'message' => __d('net_commons', 'Invalid request.')),
			array('data' => $data, 'field' => 'use_comment', 'value' => 'dummy',
				'message' => __d('net_commons', 'Invalid request.')),
			array('data' => $data, 'field' => 'use_workflow', 'value' => 'dummy',
				'message' => __d('net_commons', 'Invalid request.')),
			array('data' => $data, 'field' => 'auto_play', 'value' => 'dummy',
				'message' => __d('net_commons', 'Invalid request.')),
			array('data' => $data, 'field' => 'use_comment_approval', 'value' => 'dummy',
				'message' => __d('net_commons', 'Invalid request.')),
		);
	}

}
