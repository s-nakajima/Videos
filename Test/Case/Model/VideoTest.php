<?php
/**
 * ContentComment Test Case
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('Video', 'Videos.Model');
App::uses('VideoAppTest', 'Videos.Test/Case/Model');

/**
 * ContentComment Test Case
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\ContentComments\Test\Case\Model
 */
class VideoTest extends VideoAppTest {

/**
 * 秒を時：分：秒に変更テスト 秒単位
 *
 * @return void
 */
	public function testConvSecToHourSec() {
		$totalSec = 9;

		// privateメソッド呼び出し
		$privateMethod = new ReflectionMethod($this->Video, '__convSecToHour');
		$privateMethod->setAccessible(true);
		$time = $privateMethod->invoke($this->Video, $totalSec);

		$this->assertEquals('0:09', $time);
	}

/**
 * 秒を時：分：秒に変更テスト 分単位
 *
 * @return void
 */
	public function testConvSecToHourMin() {
		$totalSec = 60;

		// privateメソッド呼び出し
		$privateMethod = new ReflectionMethod($this->Video, '__convSecToHour');
		$privateMethod->setAccessible(true);
		$time = $privateMethod->invoke($this->Video, $totalSec);

		$this->assertEquals('1:00', $time);
	}

/**
 * 秒を時：分：秒に変更テスト 時間単位
 *
 * @return void
 */
	public function testConvSecToHourHour() {
		$totalSec = 3600;

		// privateメソッド呼び出し
		$privateMethod = new ReflectionMethod($this->Video, '__convSecToHour');
		$privateMethod->setAccessible(true);
		$time = $privateMethod->invoke($this->Video, $totalSec);

		$this->assertEquals('1:00:00', $time);
	}
}
