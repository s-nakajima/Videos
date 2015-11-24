<?php
/**
 * VideoFrameSetting Model
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('VideosAppModel', 'Videos.Model');

/**
 * VideoFrameSetting Model
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Videos\Model
 */
class VideoFrameSetting extends VideosAppModel {

/**
 * 表示順 新着順
 *
 * @var string
 */
	const DISPLAY_ORDER_NEW = 'new';

/**
 * 表示順 タイトル順
 *
 * @var string
 */
	const DISPLAY_ORDER_TITLE = 'title';

/**
 * 表示順 再生回数順
 *
 * @var string
 */
	const DISPLAY_ORDER_PLAY = 'play';

/**
 * 表示順 評価順
 *
 * @var string
 */
	const DISPLAY_ORDER_LIKE = 'like';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array();

/**
 * Called during validation operations, before validation. Please note that custom
 * validation rules can be defined in $validate.
 *
 * @param array $options Options passed from Model::save().
 * @return bool True if validate operation should continue, false to abort
 * @link http://book.cakephp.org/2.0/en/models/callback-methods.html#beforevalidate
 * @see Model::save()
 */
	public function beforeValidate($options = array()) {
		$this->validate = Hash::merge($this->validate, array(
			'frame_key' => array(
				'notEmpty' => array(
					'rule' => array('notEmpty'),
					'message' => __d('net_commons', 'Invalid request.'),
					'required' => true,
				),
			),
			'display_order' => array(
				'notEmpty' => array(
					'rule' => array('notEmpty'),
					'message' => __d('net_commons', 'Invalid request.'),
					'required' => true,		// required 効かず、default値が設定された
				),
			),
			'display_number' => array(
				'numeric' => array(
					'rule' => array('numeric'),
					'message' => __d('net_commons', 'Invalid request.'),
					'required' => true,		// required 効かず、default値が設定された
				),
			),
		));

		return parent::beforeValidate($options);
	}

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Frame' => array(
			'className' => 'Frames.Frame',
			'foreignKey' => false,
			'conditions' => array(
				'Frame.key = VideoFrameSetting.frame_key',
			),
			'fields' => 'block_id',
			'order' => ''
		),
	);

/**
 * VideoFrameSettingデータ取得
 *
 * @param bool $created If True, the results of the Model::find() to create it if it was null
 * @return array
 */
	public function getVideoFrameSetting($created) {
	//public function getVideoFrameSetting($frameKey, $roomId) {
		$conditions = array(
			'frame_key' => Current::read('Frame.key')
		);

		$videoFrameSetting = $this->find('first', array(
				'recursive' => -1,
				'conditions' => $conditions,
			)
		);

//		$conditions = array(
//			$this->alias . '.frame_key' => $frameKey,
//		);
//
//		$joins = array(
//			array(
//				'type' => 'inner',
//				'table' => 'frames',
//				'alias' => 'Frame2',
//				'conditions' => array(
//					$this->alias . '.frame_key = Frame2.key',
//					//'Frame2.room_id = ' . $roomId,
//				),
//			),
//		);
//
//		if (!$videoFrameSetting = $this->find('first', array(
//			'recursive' => 0,
//			'joins' => $joins,
//			'conditions' => $conditions,
//			'order' => $this->alias . '.id DESC'
//		))
//		) {
//			//初期値を設定
//			$videoFrameSetting = $this->create();
//		}

		if ($created && ! $videoFrameSetting) {
			$videoFrameSetting = $this->create(array(
				'frame_key' => Current::read('Frame.key'),
			));
		}

		return $videoFrameSetting;
	}

/**
 * VideoFrameSettingデータ保存
 *
 * @param array $data received post data
 * @return mixed On success Model::$data if its not empty or true, false on failure
 * @throws InternalErrorException
 */
	public function saveVideoFrameSetting($data) {
		$this->loadModels(array(
			'VideoFrameSetting' => 'Videos.VideoFrameSetting',
			'Block' => 'Blocks.Block',
		));

		//トランザクションBegin
		$dataSource = $this->getDataSource();
		$dataSource->begin();

		try {
			// 値をセット
			$this->set($data);

			// 入力チェック
			$this->validates();
			if ($this->validationErrors) {
				return false;
			}

			$videoFrameSetting = $this->save(null, false);
			if (!$videoFrameSetting) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			$dataSource->commit();

		} catch (InternalErrorException $ex) {
			$dataSource->rollback();
			CakeLog::write(LOG_ERR, $ex);
			throw $ex;
		}
		return $videoFrameSetting;
	}

/**
 * 表示順 オプション属性 取得
 *
 * @return array
 */
	public static function getDisplayOrderOptions() {
		return array(
			VideoFrameSetting::DISPLAY_ORDER_NEW => __d('videos', 'Newest'),
			VideoFrameSetting::DISPLAY_ORDER_TITLE => __d('videos', 'By title'),
			VideoFrameSetting::DISPLAY_ORDER_PLAY => __d('videos', 'Viewed'),
			//VideoFrameSetting::DISPLAY_ORDER_LIKE => __d('videos', 'Reviews'),
		);
	}

/**
 * 表示件数 オプション属性 取得
 *
 * @return array
 */
	public static function getDisplayNumberOptions() {
//		return array(
//			1 => __d('videos', '%s items', 1),
//			5 => __d('videos', '%s items', 5),
//			10 => __d('videos', '%s items', 10),
//			20 => __d('videos', '%s items', 20),
//			50 => __d('videos', '%s items', 50),
//			100 => __d('videos', '%s items', 100),
//		);
	}
}
