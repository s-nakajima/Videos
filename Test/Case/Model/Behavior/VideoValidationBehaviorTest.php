<?php
/**
 * VideoValidationBehavior Test Case
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('VideoAppTest', 'Videos.Test/Case/Model');

/**
 * Summary for VideoValidationBehavior Test Case
 */
class VideoValidationBehaviorTest extends VideoAppTest {

/**
 * ルール定義 Video::FFMPEG_ENABLE = true;　取得テスト
 *
 * @return void
 */
	public function testRules() {
		$rules = $this->Video->rules();

		$this->assertInternalType('array', $rules);
	}

/**
 * ルール定義 Video::FFMPEG_ENABLE = false;　登録時 取得テスト
 *
 * @return void
 */
	public function testrulesFfmpegOffAdd() {
		$options = array('add');
		$rules = $this->Video->rulesFfmpegOff($options);

		$this->assertInternalType('array', $rules);
	}

/**
 * ルール定義 Video::FFMPEG_ENABLE = false;　編集時 取得テスト
 *
 * @return void
 */
	public function testrulesFfmpegOffEdit() {
		$options = array('edit');
		$rules = $this->Video->rulesFfmpegOff($options);

		$this->assertInternalType('array', $rules);
	}
}
