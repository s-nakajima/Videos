<?php
/**
 * VideoFrameSettingExceptionTest Case
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('VideoAppTest', 'Videos.Test/Case/Model');

/**
 * VideoFrameSettingExceptionTest Case
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Videos\Test\Case\Model
 */
class VideoFrameSettingExceptionTest extends VideoAppTest {

/**
 * VideoFrameSettingデータ保存 登録 例外テスト
 *
 * @return void
 * @throws Exception
 */
	public function testSaveVideoFrameSettingException() {
		$this->setExpectedException('InternalErrorException');

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

		// modelモック
		$videoFrameSettingMock = $this->getMockForModel('Video.VideoFrameSetting', ['save']);
		$videoFrameSettingMock->expects($this->any())
			->method('save')
			->will($this->returnValue(false));

		$videoFrameSetting = $videoFrameSettingMock->saveVideoFrameSetting($data);
	}
}
