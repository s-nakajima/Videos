<?php
/**
 * VideoBlockSettingExceptionTest Case
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('VideoAppTest', 'Videos.Test/Case/Model');

/**
 * VideoBlockSettingExceptionTest Case
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Videos\Test\Case\Model
 */
class VideoBlockSettingExceptionTest extends VideoAppTest {

/**
 * VideoBlockSettingデータ保存 例外テスト
 *
 * @return void
 */
	public function testSaveVideoBlockSettingException() {
		$this->setExpectedException('InternalErrorException');

		// saveVideoBlockSetting で保存する $data 取得
		$data = $this->_getVideoBlockSettingTestData();

		// modelモック
		$modelMock = $this->getMockForModel('Video.VideoBlockSetting', ['save']);
		$modelMock->expects($this->any())
			->method('save')
			->will($this->returnValue(false));

		$modelMock->saveVideoBlockSetting($data);
	}
}
