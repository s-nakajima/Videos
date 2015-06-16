<?php
/**
 * VideoBlockSettingValidationBooleanTest Case
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('VideoTestBase', 'Videos.Test/Case/Model');

/**
 * VideoBlockSettingValidationBooleanTest Case
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Videos\Test\Case\Model
 */
class VideoBlockSettingValidationBooleanTest extends VideoTestBase {

/**
 * VideoBlockSettingデータ保存 use_like booleanエラー
 * $block['VideoBlockSetting']['use_like'] bool型に変換できない値のため エラー
 *
 * @return void
 */
	public function testSaveVideoBlockSettingUseLikeBoolean() {
		// saveVideoBlockSetting で保存する $data 取得
		$data = $this->_getVideoBlockSettingTestData();
		$data['VideoBlockSetting']['use_like'] = 'hoge';

		$this->VideoBlockSetting->saveVideoBlockSetting($data);

		$this->assertArrayHasKey('use_like', $this->VideoBlockSetting->validationErrors);
	}

/**
 * VideoBlockSettingデータ保存 use_unlike booleanエラー
 * $block['VideoBlockSetting']['use_unlike'] bool型に変換できない値のため エラー
 *
 * @return void
 */
	public function testSaveVideoBlockSettingUseUnlikeBoolean() {
		// saveVideoBlockSetting で保存する $data 取得
		$data = $this->_getVideoBlockSettingTestData();
		$data['VideoBlockSetting']['use_unlike'] = 'hoge';

		$this->VideoBlockSetting->saveVideoBlockSetting($data);

		$this->assertArrayHasKey('use_unlike', $this->VideoBlockSetting->validationErrors);
	}

/**
 * VideoBlockSettingデータ保存 use_comment booleanエラー
 * $block['VideoBlockSetting']['use_comment'] bool型に変換できない値のため エラー
 *
 * @return void
 */
	public function testSaveVideoBlockSettingUseCommentBoolean() {
		// saveVideoBlockSetting で保存する $data 取得
		$data = $this->_getVideoBlockSettingTestData();
		$data['VideoBlockSetting']['use_comment'] = 'hoge';

		$this->VideoBlockSetting->saveVideoBlockSetting($data);

		$this->assertArrayHasKey('use_comment', $this->VideoBlockSetting->validationErrors);
	}

/**
 * VideoBlockSettingデータ保存 agree booleanエラー
 * $block['VideoBlockSetting']['agree'] bool型に変換できない値のため エラー
 *
 * @return void
 */
	public function testSaveVideoBlockSettingAgreeBoolean() {
		// saveVideoBlockSetting で保存する $data 取得
		$data = $this->_getVideoBlockSettingTestData();
		$data['VideoBlockSetting']['agree'] = 'hoge';

		$this->VideoBlockSetting->saveVideoBlockSetting($data);

		$this->assertArrayHasKey('agree', $this->VideoBlockSetting->validationErrors);
	}

/**
 * VideoBlockSettingデータ保存 mail_notice booleanエラー
 * $block['VideoBlockSetting']['mail_notice'] bool型に変換できない値のため エラー
 *
 * @return void
 */
	public function testSaveVideoBlockSettingMailNoticeBoolean() {
		// saveVideoBlockSetting で保存する $data 取得
		$data = $this->_getVideoBlockSettingTestData();
		$data['VideoBlockSetting']['mail_notice'] = 'hoge';

		$this->VideoBlockSetting->saveVideoBlockSetting($data);

		$this->assertArrayHasKey('mail_notice', $this->VideoBlockSetting->validationErrors);
	}

/**
 * VideoBlockSettingデータ保存 auto_play booleanエラー
 * $block['VideoBlockSetting']['auto_play'] bool型に変換できない値のため エラー
 *
 * @return void
 */
	public function testSaveVideoBlockSettingAutoPlayBoolean() {
		// saveVideoBlockSetting で保存する $data 取得
		$data = $this->_getVideoBlockSettingTestData();
		$data['VideoBlockSetting']['auto_play'] = 'hoge';

		$this->VideoBlockSetting->saveVideoBlockSetting($data);

		$this->assertArrayHasKey('auto_play', $this->VideoBlockSetting->validationErrors);
	}

/**
 * VideoBlockSettingデータ保存 comment_agree booleanエラー
 * $block['VideoBlockSetting']['comment_agree'] bool型に変換できない値のため エラー
 *
 * @return void
 */
	public function testSaveVideoBlockSettingCommentAgreeBoolean() {
		// saveVideoBlockSetting で保存する $data 取得
		$data = $this->_getVideoBlockSettingTestData();
		$data['VideoBlockSetting']['comment_agree'] = 'hoge';

		$this->VideoBlockSetting->saveVideoBlockSetting($data);

		$this->assertArrayHasKey('comment_agree', $this->VideoBlockSetting->validationErrors);
	}

/**
 * VideoBlockSettingデータ保存 comment_agree_mail_notice booleanエラー
 * $block['VideoBlockSetting']['comment_agree_mail_notice'] bool型に変換できない値のため エラー
 *
 * @return void
 */
	public function testSaveVideoBlockSettingCommentAgreeMailNoticeBoolean() {
		// saveVideoBlockSetting で保存する $data 取得
		$data = $this->_getVideoBlockSettingTestData();
		$data['VideoBlockSetting']['comment_agree_mail_notice'] = 'hoge';

		$this->VideoBlockSetting->saveVideoBlockSetting($data);

		$this->assertArrayHasKey('comment_agree_mail_notice', $this->VideoBlockSetting->validationErrors);
	}
}
