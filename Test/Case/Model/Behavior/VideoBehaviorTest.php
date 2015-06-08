<?php
/**
 * VideoBehavior Test Case
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('VideoAppTest', 'Videos.Test/Case/Model');

/**
 * テスト用Fake
 */
class FakeModel extends CakeTestModel {

/**
 * @var array ビヘイビア
 */
	public $actsAs = array('Videos.Video');
}

/**
 * Summary for VideoBehavior Test Case
 */
class VideoBehaviorTest extends VideoAppTest {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->FakeModel = ClassRegistry::init('FakeModel');
	}

/**
 * 秒を時：分：秒に変更テスト 秒単位
 *
 * @return void
 */
	public function testConvSecToHourSec() {
		$totalSec = 9;

		$time = $this->FakeModel->convSecToHour($totalSec);

		$this->assertEquals('0:09', $time);
	}

/**
 * 秒を時：分：秒に変更テスト 分単位
 *
 * @return void
 */
	public function testConvSecToHourMin() {
		$totalSec = 60;

		$time = $this->FakeModel->convSecToHour($totalSec);

		$this->assertEquals('1:00', $time);
	}

/**
 * 秒を時：分：秒に変更テスト 時間単位
 *
 * @return void
 */
	public function testConvSecToHourHour() {
		$totalSec = 3600;

		$time = $this->FakeModel->convSecToHour($totalSec);

		$this->assertEquals('1:00:00', $time);
	}

}
