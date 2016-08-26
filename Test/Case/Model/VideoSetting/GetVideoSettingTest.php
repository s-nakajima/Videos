<?php
/**
 * VideoSetting::getVideoSetting()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsGetTest', 'NetCommons.TestSuite');

/**
 * VideoSetting::getVideoSetting()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Videos\Test\Case\Model\VideoSetting
 */
class VideoSettingGetVideoSettingTest extends NetCommonsGetTest {

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
	protected $_methodName = 'getVideoSetting';

/**
 * getVideoSetting()のテスト
 *
 * @return void
 */
	public function testGetVideoSetting() {
		$model = $this->_modelName;
		$methodName = $this->_methodName;
		Current::$current['Block']['key'] = 'block_1';

		//データ生成

		//テスト実施
		$result = $this->$model->$methodName();

		//チェック
		//debug($result);
		$this->assertArrayHasKey('VideoSetting', $result);
		$this->assertArrayHasKey('Block', $result);
	}

}
