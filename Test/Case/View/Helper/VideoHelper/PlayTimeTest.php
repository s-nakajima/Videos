<?php
/**
 * VideoHelper::playTime()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsHelperTestCase', 'NetCommons.TestSuite');

/**
 * VideoHelper::playTime()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Videos\Test\Case\View\Helper\VideoHelper
 */
class VideoHelperPlayTimeTest extends NetCommonsHelperTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array();

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'videos';

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		//テストデータ生成
		$viewVars = array();
		$requestData = array();
		$params = array();

		//Helperロード
		$this->loadHelper('Videos.Video', $viewVars, $requestData, $params);
	}

/**
 * playTime()のテスト
 *
 * @return void
 */
	public function testPlayTime() {
		//データ生成
		$totalSec = 1;
		$isFfmpegEnable = true;

		//テスト実施
		$result = $this->Video->playTime($totalSec, $isFfmpegEnable);

		//チェック
		$this->assertTextContains('0:01', $result);
		//var_dump($result);
	}

/**
 * playTime()の1時間表示テスト
 *
 * @return void
 */
	public function testPlayTimeHour() {
		//データ生成
		$totalSec = 3600;
		$isFfmpegEnable = true;

		//テスト実施
		$result = $this->Video->playTime($totalSec, $isFfmpegEnable);

		//チェック
		$this->assertTextContains('1:00:00', $result);
		//var_dump($result);
	}

/**
 * playTime()のFfmpeg無効テスト
 *
 * @return void
 */
	public function testPlayTimeIsFfmpegEnableOff() {
		//データ生成
		$totalSec = 1;
		$isFfmpegEnable = false;

		//テスト実施
		$result = $this->Video->playTime($totalSec, $isFfmpegEnable);

		//チェック
		$this->assertEmpty($result);
		//var_dump($result);
	}
}
