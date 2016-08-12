<?php
/**
 * Video::getTotalSize()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsGetTest', 'NetCommons.TestSuite');

/**
 * Video::getTotalSize()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Videos\Test\Case\Model\Video
 */
class VideoGetTotalSizeTest extends NetCommonsGetTest {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.videos.video',
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
	protected $_modelName = 'Video';

/**
 * Method name
 *
 * @var string
 */
	protected $_methodName = 'getTotalSize';

/**
 * getTotalSize()のテスト
 *
 * @return void
 * @see Video::getTotalSize()
 */
	public function testGetTotalSize() {
		$model = $this->_modelName;
		$methodName = $this->_methodName;

		//データ生成
		Current::$current['Block']['id'] = '2';

		//テスト実施
		$result = $this->$model->$methodName();

		//チェック
		//debug($result);
		$this->assertNotEquals(0, $result);
	}

/**
 * getTotalSize()のテスト - データなしの場合、0 取得テスト
 *
 * @return void
 * @see Video::getTotalSize()
 */
	public function testGetTotalSizeNull() {
		$model = $this->_modelName;
		$methodName = $this->_methodName;

		//データ生成
		Current::$current['Block']['id'] = '999';

		//テスト実施
		$result = $this->$model->$methodName();

		//チェック
		//debug($result);
		$this->assertEquals(0, $result);
	}
}
