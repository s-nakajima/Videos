<?php
/**
 * VideoFrameSetting::getVideoFrameSetting()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsGetTest', 'NetCommons.TestSuite');

/**
 * VideoFrameSetting::getVideoFrameSetting()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Videos\Test\Case\Model\VideoFrameSetting
 */
class VideoFrameSettingGetVideoFrameSettingTest extends NetCommonsGetTest {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.videos.video_frame_setting',
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
	protected $_modelName = 'VideoFrameSetting';

/**
 * Method name
 *
 * @var string
 */
	protected $_methodName = 'getVideoFrameSetting';

/**
 * getVideoFrameSetting()のテスト
 *
 * @return void
 */
	public function testGetVideoFrameSetting() {
		$model = $this->_modelName;
		$methodName = $this->_methodName;
		Current::$current['Frame']['key'] = 'Frame_1';

		//データ生成
		$created = null;

		//テスト実施
		$result = $this->$model->$methodName($created);

		//チェック
		//debug($result);
		$this->assertArrayHasKey('VideoFrameSetting', $result);
	}

}
