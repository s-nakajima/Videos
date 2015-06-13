<?php
/**
 * VideoFrameSettingTest Case
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('VideoAppTest', 'Videos.Test/Case/Model');

/**
 * VideoFrameSettingTest Case
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Videos\Test\Case\Model
 */
class VideoFrameSettingTest extends VideoAppTest {

/**
 * VideoFrameSettingデータ取得 テスト
 *
 * @return void
 */
	public function testGetVideoFrameSetting() {
		$frameKey = '1';
		$roomId = 1;
		$videoFrameSetting = $this->VideoFrameSetting->getVideoFrameSetting($frameKey, $roomId);

		$this->assertInternalType('array', $videoFrameSetting);
	}

/**
 * VideoFrameSettingデータ保存 登録テスト
 *
 * @return void
 */
	public function testSaveVideoFrameSetting() {
		//新規データ
		$videoFrameSetting = $this->VideoFrameSetting->create();

		$data = Hash::merge(
			$videoFrameSetting,
			array('VideoFrameSetting' => array(
				'frame_key' => 'frame_1',
				'display_order' => 'play',
				'display_number' => 10,
			)),
			array('Frame' => array('id' => 1))
		);

		$videoFrameSetting = $this->VideoFrameSetting->saveVideoFrameSetting($data);

		$this->assertInternalType('array', $videoFrameSetting);
	}

/**
 * VideoFrameSettingデータ保存 更新テスト
 *
 * @return void
 */
	public function testSaveVideoFrameSettingEdit() {
		$frameKey = '1';
		$roomId = 1;
		$videoFrameSetting = $this->VideoFrameSetting->getVideoFrameSetting($frameKey, $roomId);

		$data = Hash::merge(
			$videoFrameSetting,
			array('VideoFrameSetting' => array(
				'frame_key' => 'frame_1',
				'display_order' => 'play',
				'display_number' => 10,
			)),
			array('Frame' => array('id' => 1))
		);

		$videoFrameSetting = $this->VideoFrameSetting->saveVideoFrameSetting($data);

		$this->assertInternalType('array', $videoFrameSetting);
	}

/**
 * 表示順 オプション属性 取得 テスト
 *
 * @return void
 */
	public function testGetDisplayOrderOptions() {
		$rtn = VideoFrameSetting::getDisplayOrderOptions();

		$this->assertInternalType('array', $rtn);
	}

/**
 * 表示件数 オプション属性 取得 テスト
 *
 * @return void
 */
	public function testGetDisplayNumberOptions() {
		$rtn = VideoFrameSetting::getDisplayNumberOptions();

		$this->assertInternalType('array', $rtn);
	}
}
