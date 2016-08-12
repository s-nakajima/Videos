<?php
/**
 * VideoBlockSetting::saveTotalSize()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsModelTestCase', 'NetCommons.TestSuite');

/**
 * VideoBlockSetting::saveTotalSize()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Videos\Test\Case\Model\Video
 */
class VideoBlockSettingSaveTotalSizeTest extends NetCommonsModelTestCase {

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
	protected $_modelName = 'VideoBlockSetting';

/**
 * Method name
 *
 * @var string
 */
	protected $_methodName = 'saveTotalSize';

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		Current::write('Block.key', 'block_1');
	}

/**
 * saveTotalSize()のテスト
 *
 * @return void
 * @see VideoBlockSetting::saveTotalSize()
 */
	public function testSaveTotalSize() {
		$model = $this->_modelName;
		$methodName = $this->_methodName;

		//データ生成
		$totalSize = 1111;

		//テスト実施
		$result = $this->$model->$methodName($totalSize);
		$this->assertTrue($result);

		//チェック
		$videoBlockSetting = $this->$model->getVideoBlockSetting();
		//debug($videoBlockSetting);
		$this->assertEquals($totalSize, $videoBlockSetting['VideoBlockSetting']['total_size']);
	}

/**
 * ExceptionErrorテスト
 *
 * @param array $data 登録データ
 * @param string $mockModel Mockのモデル
 * @param string $mockMethod Mockのメソッド
 * @dataProvider dataProviderSaveOnExceptionError
 * @return void
 */
	public function testSaveOnExceptionError($data, $mockModel, $mockMethod) {
		$model = $this->_modelName;
		$method = $this->_methodName;

		$this->_mockForReturnFalse($model, $mockModel, $mockMethod);

		$this->setExpectedException('InternalErrorException');
		$this->$model->$method($data);
	}

/**
 * ExceptionError用DataProvider
 *
 * ### 戻り値
 *  - data 登録データ
 *  - mockModel Mockのモデル
 *  - mockMethod Mockのメソッド
 *
 * @return array テストデータ
 */
	public function dataProviderSaveOnExceptionError() {
		$totalSize = 1111;

		return array(
			array($totalSize, 'Videos.VideoBlockSetting', 'saveField'),
		);
	}
}
