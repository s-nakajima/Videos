<?php
/**
 * Video::validate()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsValidateTest', 'NetCommons.TestSuite');
App::uses('VideoFixture', 'Videos.Test/Fixture');
App::uses('Video', 'Videos.Model');

/**
 * Video::validate()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Videos\Test\Case\Model\Video
 */
class VideoValidateTest extends NetCommonsValidateTest {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.videos.video',
		'plugin.videos.video_block_setting',
		'plugin.videos.video_frame_setting',
		'plugin.workflow.workflow_comment',
	);

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'videos';

/**
 * Model name
 *
 * @var string
 */
	protected $_modelName = 'Video';

/**
 * Method name
 *
 * @var string
 */
	protected $_methodName = 'validates';

/**
 * ValidationErrorのDataProvider
 *
 * ### 戻り値
 *  - data 登録データ
 *  - field フィールド名
 *  - value セットする値
 *  - message エラーメッセージ
 *  - overwrite 上書きするデータ(省略可)
 *
 * @return array テストデータ
 */
	public function dataProviderValidationError() {
		$data['Video'] = (new VideoFixture())->records[0];
		$overwrite['Video'][Video::THUMBNAIL_FIELD]['size'] = 0;

		//debug($data);
		return array(
			'登録:language_id' => array('data' => $data, 'field' => 'language_id', 'value' => null,
				'message' => __d('net_commons', 'Invalid request.'),
				'overwrite' => $overwrite, 'options' => array('add')),
			'登録:block_id' => array('data' => $data, 'field' => 'block_id', 'value' => null,
				'message' => __d('net_commons', 'Invalid request.'),
				'overwrite' => array(), 'options' => array('add')),
			'登録:title' => array('data' => $data, 'field' => 'title', 'value' => null,
				'message' => sprintf(__d('net_commons', 'Please input %s.'), __d('videos', 'title')),
				'overwrite' => array(), 'options' => array('add')),
			'編集:language_id' => array('data' => $data, 'field' => 'language_id', 'value' => null,
				'message' => __d('net_commons', 'Invalid request.'),
				'overwrite' => $overwrite, 'options' => array('edit')),
		);
	}

/**
 * Validatesのテスト
 *
 * @param array $data 登録データ
 * @param string $field フィールド名
 * @param string $value セットする値
 * @param string $message エラーメッセージ
 * @param array $overwrite 上書きするデータ
 * @param array $options validateのオプション
 * @dataProvider dataProviderValidationError
 * @return void
 */
	public function testValidationError($data, $field, $value, $message, $overwrite = array(), $options = array()) {
		$model = $this->_modelName;

		if (is_null($value)) {
			unset($data[$model][$field]);
		} else {
			$data[$model][$field] = $value;
		}
		$data = Hash::merge($data, $overwrite);

		//validate処理実行
		$this->$model->set($data);
		//$result = $this->$model->validates();
		$result = $this->$model->validates($options);
		$this->assertFalse($result);

		if ($message) {
			$this->assertEquals($this->$model->validationErrors[$field][0], $message);
		}
	}

}
