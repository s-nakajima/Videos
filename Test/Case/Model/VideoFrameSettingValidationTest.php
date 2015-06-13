<?php
/**
 * VideoFrameSettingValidationTest Case
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
class VideoFrameSettingValidationTest extends VideoAppTest {

/**
 * VideoFrameSettingデータ保存 バリデートエラー
 * $data['VideoFrameSetting']['frame_key'] = nullでエラー
 * 戻り値をチェック
 *
 * @return void
 */
	public function testSaveVideoFrameSettingValidationErrors() {
		//新規データ
		$videoFrameSetting = $this->VideoFrameSetting->create();

		$data = Hash::merge(
			$videoFrameSetting,
			array('VideoFrameSetting' => array(
				'frame_key' => null,
				'display_order' => 'play',
				'display_number' => 10,
			)),
			array('Frame' => array('id' => 1))
		);

		$videoFrameSetting = $this->VideoFrameSetting->saveVideoFrameSetting($data);

		$this->assertFalse($videoFrameSetting);
	}

/**
 * VideoFrameSettingデータ保存 バリデート frame_key notEmptyテスト
 * $data['VideoFrameSetting']['frame_key'] = nullでエラー
 * validationErrorsをチェック
 *
 * @return void
 */
	public function testSaveVideoFrameSettingValidationFrameKeyNotEmpty() {
		//新規データ
		$videoFrameSetting = $this->VideoFrameSetting->create();

		$data = Hash::merge(
			$videoFrameSetting,
			array('VideoFrameSetting' => array(
				'frame_key' => null,
				'display_order' => 'play',
				'display_number' => 10,
			)),
			array('Frame' => array('id' => 1))
		);

		$videoFrameSetting = $this->VideoFrameSetting->saveVideoFrameSetting($data);

		$this->assertArrayHasKey('frame_key', $this->VideoFrameSetting->validationErrors);
	}

/**
 * VideoFrameSettingデータ保存 バリデート frame_key requiredテスト
 * $data['VideoFrameSetting']['frame_key'] keyなしでエラー
 * validationErrorsをチェック
 *
 * @return void
 */
	public function testSaveVideoFrameSettingValidationFrameKeyRequired() {
		//新規データ
		$videoFrameSetting = $this->VideoFrameSetting->create();

		$data = Hash::merge(
			$videoFrameSetting,
			array('VideoFrameSetting' => array(
				'display_order' => 'play',
				'display_number' => 10,
			)),
			array('Frame' => array('id' => 1))
		);

		$videoFrameSetting = $this->VideoFrameSetting->saveVideoFrameSetting($data);

		$this->assertArrayHasKey('frame_key', $this->VideoFrameSetting->validationErrors);
	}

/**
 * VideoFrameSettingデータ保存 バリデート display_order notEmptyテスト
 * $data['VideoFrameSetting']['display_order'] = nullでエラー
 * validationErrorsをチェック
 *
 * @return void
 */
	public function testSaveVideoFrameSettingValidationDisplayOrderNotEmpty() {
		//新規データ
		$videoFrameSetting = $this->VideoFrameSetting->create();

		$data = Hash::merge(
			$videoFrameSetting,
			array('VideoFrameSetting' => array(
				'frame_key' => 'frame_1',
				'display_order' => null,
				'display_number' => 10,
			)),
			array('Frame' => array('id' => 1))
		);

		$videoFrameSetting = $this->VideoFrameSetting->saveVideoFrameSetting($data);

		$this->assertArrayHasKey('display_order', $this->VideoFrameSetting->validationErrors);
	}

/**
 * VideoFrameSettingデータ保存 バリデート display_order requiredテスト
 * $data['VideoFrameSetting']['display_order'] keyなしでエラー
 * validationErrorsをチェック
 *
 * @return void
 */
	public function testSaveVideoFrameSettingValidationDisplayOrderRequired() {
		//新規データ
		$videoFrameSetting = $this->VideoFrameSetting->create();

		unset($videoFrameSetting['VideoFrameSetting']['display_order']);
		$data = Hash::merge(
			$videoFrameSetting,
			array('VideoFrameSetting' => array(
				'frame_key' => 'frame_1',
				'display_number' => 10,
			)),
			array('Frame' => array('id' => 1))
		);

		$videoFrameSetting = $this->VideoFrameSetting->saveVideoFrameSetting($data);

		//'required' => true,		// db項目にdefaultが定義されていると required 効かず、default値が設定された
		// $videoFrameSettingの戻り値で確認できる。
		//$this->assertArrayHasKey('display_order', $this->VideoFrameSetting->validationErrors);
		// 正常終了
		$this->assertInternalType('array', $videoFrameSetting);
	}

/**
 * VideoFrameSettingデータ保存 バリデート display_number Numericテスト
 * $data['VideoFrameSetting']['display_number'] = 文字 でエラー
 * validationErrorsをチェック
 *
 * @return void
 */
	public function testSaveVideoFrameSettingValidationDisplayNumberNumeric() {
		//新規データ
		$videoFrameSetting = $this->VideoFrameSetting->create();

		$data = Hash::merge(
			$videoFrameSetting,
			array('VideoFrameSetting' => array(
				'frame_key' => 'frame_1',
				'display_order' => 'play',
				'display_number' => 'hoge',
			)),
			array('Frame' => array('id' => 1))
		);

		$videoFrameSetting = $this->VideoFrameSetting->saveVideoFrameSetting($data);

		$this->assertArrayHasKey('display_number', $this->VideoFrameSetting->validationErrors);
	}

/**
 * VideoFrameSettingデータ保存 バリデート display_number notEmptyテスト
 * $data['VideoFrameSetting']['display_number'] keyなし でエラー
 * validationErrorsをチェック
 *
 * @return void
 */
	public function testSaveVideoFrameSettingValidationDisplayNumberRequired() {
		//新規データ
		$videoFrameSetting = $this->VideoFrameSetting->create();

		unset($videoFrameSetting['VideoFrameSetting']['display_number']);
		$data = Hash::merge(
			$videoFrameSetting,
			array('VideoFrameSetting' => array(
				'frame_key' => 'frame_1',
				'display_order' => 'play',
			)),
			array('Frame' => array('id' => 1))
		);

		$videoFrameSetting = $this->VideoFrameSetting->saveVideoFrameSetting($data);

		//'required' => true,		// db項目にdefaultが定義されていると required 効かず、default値が設定された
		// $videoFrameSettingの戻り値で確認できる。
		//$this->assertArrayHasKey('display_number', $this->VideoFrameSetting->validationErrors);
		// 正常終了
		$this->assertInternalType('array', $videoFrameSetting);
	}
}
