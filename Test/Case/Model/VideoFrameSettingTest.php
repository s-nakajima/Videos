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

App::uses('VideoTestBase', 'Videos.Test/Case/Model');

/**
 * VideoFrameSettingTest Case
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Videos\Test\Case\Model
 */
class VideoFrameSettingTest extends VideoTestBase {

/**
 * VideoFrameSettingデータ取得 テスト
 *
 * @return void
 */
	public function testGetVideoFrameSetting() {
		//		$frameKey = '1';
		//		$roomId = 1;
		//		$videoFrameSetting = $this->VideoFrameSetting->getVideoFrameSetting($frameKey, $roomId);
		$videoFrameSetting = $this->VideoFrameSetting->getVideoFrameSetting(true);

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
		//		$frameKey = '1';
		//		$roomId = 1;
		//		$videoFrameSetting = $this->VideoFrameSetting->getVideoFrameSetting($frameKey, $roomId);
		$videoFrameSetting = $this->VideoFrameSetting->getVideoFrameSetting(true);

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
}
