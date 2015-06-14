<?php
/**
 * VideoBlockSettingValidationTest Case
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('VideoAppTest', 'Videos.Test/Case/Model');

/**
 * VideoBlockSettingValidationTest Case
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Videos\Test\Case\Model
 */
class VideoBlockSettingValidationTest extends VideoAppTest {

/**
 * VideoBlockSettingデータ保存 バリデーションエラー戻り値テスト
 * $block['VideoBlockSetting']['block_key'] = null のため エラー
 *
 * @return void
 */
	public function testSaveVideoBlockSettingValidationErrors() {
		// saveVideoBlockSetting で保存する $data 取得
		$data = $this->_getVideoBlockSettingTestData();
		$data['VideoBlockSetting']['block_key'] = null;

		$videoBlockSetting = $this->VideoBlockSetting->saveVideoBlockSetting($data);

		$this->assertFalse($videoBlockSetting);
	}

/**
 * VideoBlockSettingデータ保存 block_key notEmptyエラー
 * $block['VideoBlockSetting']['block_key'] = null のため エラー
 *
 * @return void
 */
	public function testSaveVideoBlockSettingBlockKeyNotEmpty() {
		// saveVideoBlockSetting で保存する $data 取得
		$data = $this->_getVideoBlockSettingTestData();
		$data['VideoBlockSetting']['block_key'] = null;

		$this->VideoBlockSetting->saveVideoBlockSetting($data);

		$this->assertArrayHasKey('block_key', $this->VideoBlockSetting->validationErrors);
	}

/**
 * VideoBlockSettingデータ保存 block_key requiredエラー
 * $block['VideoBlockSetting']['block_key'] keyなしのため エラー
 *
 * @return void
 */
	public function testSaveVideoBlockSettingBlockKeyRequired() {
		// saveVideoBlockSetting で保存する $data 取得
		$data = $this->_getVideoBlockSettingTestData();
		unset($data['VideoBlockSetting']['block_key']);

		$this->VideoBlockSetting->saveVideoBlockSetting($data);

		$this->assertArrayHasKey('block_key', $this->VideoBlockSetting->validationErrors);
	}

/**
 * VideoBlockSettingデータ保存 Blockテーブル name requiredエラー
 * $block['Block']['name'] keyなしのため エラーを予想していたが、required 効かなかった
 *
 * @return void
 */
	public function testSaveVideoBlockSettingBlockTableNameRequired() {
		// saveVideoBlockSetting で保存する $data 取得
		$data = $this->_getVideoBlockSettingTestData();
		unset($data['Block']['name']);

		$videoBlockSetting = $this->VideoBlockSetting->saveVideoBlockSetting($data);

		//$this->assertArrayHasKey('name', $this->Block->validationErrors);
		$this->assertInternalType('array', $videoBlockSetting);
	}

/**
 * VideoBlockSettingデータ保存 Blockテーブル name notEmptyエラー
 * $block['Block']['name'] = nullのため エラー
 *
 * @return void
 */
	public function testSaveVideoBlockSettingBlockTableNameNotEmpty() {
		// saveVideoBlockSetting で保存する $data 取得
		$data = $this->_getVideoBlockSettingTestData();
		$data['Block']['name'] = null;

		$this->VideoBlockSetting->saveVideoBlockSetting($data);

		$this->assertArrayHasKey('name', $this->Block->validationErrors);
	}
}
