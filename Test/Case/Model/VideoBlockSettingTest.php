<?php
/**
 * VideoBlockSettingTest Case
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('VideoAppTest', 'Videos.Test/Case/Model');

/**
 * VideoBlockSettingTest Case
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Videos\Test\Case\Model
 */
class VideoBlockSettingTest extends VideoAppTest {

/**
 * VideoBlockSettingデータ取得 テスト
 *
 * @return void
 */
	public function testGetVideoBlockSetting() {
		$blockKey = 'block_1';
		$roomId = 1;
		$videoBlockSetting = $this->VideoBlockSetting->getVideoBlockSetting($blockKey, $roomId);

		$this->assertInternalType('array', $videoBlockSetting);
	}

/**
 * VideoBlockSettingデータ取得 新規データ取得 テスト
 * $blockKey = nullで検索によって、取得データなし時の動作テスト
 *
 * @return void
 */
	public function testGetVideoBlockSettingCreate() {
		$blockKey = null;
		$roomId = 1;
		$videoBlockSetting = $this->VideoBlockSetting->getVideoBlockSetting($blockKey, $roomId);

		$this->assertInternalType('array', $videoBlockSetting);
	}

/**
 * VideoBlockSettingデータ保存 テスト
 *
 * @return void
 */
	public function testSaveVideoBlockSetting() {
		// saveVideoBlockSetting で保存する $data 取得
		$data = $this->_getVideoBlockSettingTestData();

		$videoBlockSetting = $this->VideoBlockSetting->saveVideoBlockSetting($data);

		$this->assertInternalType('array', $videoBlockSetting);
	}
}
