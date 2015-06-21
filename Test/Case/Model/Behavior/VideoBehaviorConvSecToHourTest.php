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

App::uses('VideoBehaviorTestBase', 'Videos.Test/Case/Model/Behavior');

/**
 * Summary for VideoBehavior Test Case
 */
class VideoBehaviorConvSecToHourTest extends VideoBehaviorTestBase {

/**
 * 秒を時：分：秒に変更 (表示用)テスト 秒単位
 *
 * @return void
 */
	public function testConvSecToHourSec() {
		$totalSec = 9;

		$time = $this->Video->convSecToHour($totalSec);

		$this->assertEquals('0:09', $time);
	}

/**
 * 秒を時：分：秒に変更 (表示用)テスト 分単位
 *
 * @return void
 */
	public function testConvSecToHourMin() {
		$totalSec = 60;

		$time = $this->Video->convSecToHour($totalSec);

		$this->assertEquals('1:00', $time);
	}

/**
 * 秒を時：分：秒に変更 (表示用)テスト 時間単位
 *
 * @return void
 */
	public function testConvSecToHourHour() {
		$totalSec = 3600;

		$time = $this->Video->convSecToHour($totalSec);

		$this->assertEquals('1:00:00', $time);
	}

/**
 * 秒を時：分：秒に変更 (編集用)テスト 秒単位
 *
 * @return void
 */
	//	public function testConvSecToHourEditSec() {
	//		$totalSec = 9;
	//
	//		$time = $this->Video->convSecToHourEdit($totalSec);
	//
	//		$this->assertEquals('00:00:09', $time);
	//	}

/**
 * 秒を時：分：秒に変更 (編集用)テスト 分単位
 *
 * @return void
 */
	//	public function testConvSecToHourEditMin() {
	//		$totalSec = 60;
	//
	//		$time = $this->Video->convSecToHourEdit($totalSec);
	//
	//		$this->assertEquals('00:01:00', $time);
	//	}

/**
 * 秒を時：分：秒に変更 (編集用)テスト 時間単位
 *
 * @return void
 */
	//	public function testConvSecToHourEditHour() {
	//		$totalSec = 3600;
	//
	//		$time = $this->Video->convSecToHourEdit($totalSec);
	//
	//		$this->assertEquals('01:00:00', $time);
	//	}
}
