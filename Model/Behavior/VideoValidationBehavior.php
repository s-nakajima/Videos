<?php
/**
 * Video Validation Behavior
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

/**
 * Summary for Video Validation Behavior
 */
class VideoValidationBehavior extends ModelBehavior {

/**
 * ルール定義 ffmpeg=ON
 *
 * @param Model $Model モデル
 * @return array
 */
	public function rules(Model $Model) {
		$rules = Hash::merge($Model->validate, array(
			'block_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
					'message' => __d('net_commons', 'Invalid request.'),
					'required' => true,
				),
			),
			'title' => array(
				'notEmpty' => array(
					'rule' => array('notEmpty'),
					'message' => sprintf(__d('net_commons', 'Please input %s.'), __d('videos', 'title')),
					'required' => true,
				),
			),
			// 登録時のみ使っている
			Video::VIDEO_FILE_FIELD => array(
				'upload-file' => array(
					'rule' => array('uploadError'),
					'message' => array(__d('files', 'ファイルを指定してください'))
				),
				'extension' => array(
					'rule' => array('extension', explode(',', Video::VIDEO_EXTENSION)),
					'message' => array(__d('files', 'アップロード不可のファイル形式です'))
				),
				'mimetype' => array(
					'rule' => array('mimeType', explode(',', Video::VIDEO_MIME_TYPE)),
					'message' => array(__d('files', 'アップロード不可のファイル形式です'))
				),
			),
			// 編集時のみ使っている
			Video::THUMBNAIL_FIELD => array(
				'extension' => array(
					'rule' => array('extension', explode(',', Video::THUMBNAIL_EXTENSION)),
					'message' => array(__d('files', 'アップロード不可のファイル形式です'))
				),
				'mimetype' => array(
					'rule' => array('mimeType', explode(',', Video::THUMBNAIL_MIME_TYPE)),
					'message' => array(__d('files', 'アップロード不可のファイル形式です'))
				),
			),
		));

		return $rules;
	}

/**
 * ルール定義 ffmpeg=OFF
 *
 * @param Model $Model モデル
 * @param array $options Options passed from Model::save().
 * @return array
 */
	public function rulesFfmpegOff(Model $Model, $options = array()) {
		$rules = Hash::merge($Model->validate, array(
			'block_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
					'message' => __d('net_commons', 'Invalid request.'),
					'required' => true,
				),
			),
			'title' => array(
				'notEmpty' => array(
					'rule' => array('notEmpty'),
					'message' => sprintf(__d('net_commons', 'Please input %s.'), __d('videos', 'title')),
					'required' => true,
				),
			),
			/* // 再生時間
			'video_time' => array(
				'notEmpty' => array(
					'rule' => array('notEmpty'),
					'message' => sprintf(__d('net_commons', 'Please input %s.'), __d('videos', 'play time')),
					'required' => true,
				),
				//フォーマット 00:00:00
				'format' => array(
					'rule' => '/^[0-9]{2}:[0-9]{2}:[0-9]{2}$/i',
					'message' => __d('videos', 'There is an error in the time of format'),	//time format is incorrect
				),
			), */
		));

		// 登録時
		if (in_array('add', $options)) {
			$rules = Hash::merge($rules, array(
				// 必須 mp4のみ
				Video::VIDEO_FILE_FIELD => array(
					'upload-file' => array(
						'rule' => array('uploadError'),
						'message' => array(__d('files', 'ファイルを指定してください'))
					),
					'extension' => array(
						'rule' => array('extension', array('mp4')),
						'message' => array(__d('files', 'アップロード不可のファイル形式です'))
					),
					'mimetype' => array(
						'rule' => array('mimeType', array('video/mp4')),
						'message' => array(__d('files', 'アップロード不可のファイル形式です'))
					),
				),
				// 必須
				Video::THUMBNAIL_FIELD => array(
					'upload-file' => array(
						'rule' => array('uploadError'),
						'message' => array(__d('files', 'ファイルを指定してください'))
					),
					'extension' => array(
						'rule' => array('extension', explode(',', Video::THUMBNAIL_EXTENSION)),
						'message' => array(__d('files', 'アップロード不可のファイル形式です'))
					),
					'mimetype' => array(
						'rule' => array('mimeType', explode(',', Video::THUMBNAIL_MIME_TYPE)),
						'message' => array(__d('files', 'アップロード不可のファイル形式です'))
					),
				),
			));

			// 編集時
		} elseif (in_array('edit', $options)) {
			$rules = Hash::merge($rules, array(
				// 任意
				Video::THUMBNAIL_FIELD => array(
					'extension' => array(
						'rule' => array('extension', explode(',', Video::THUMBNAIL_EXTENSION)),
						'message' => array(__d('files', 'アップロード不可のファイル形式です'))
					),
					'mimetype' => array(
						'rule' => array('mimeType', explode(',', Video::THUMBNAIL_MIME_TYPE)),
						'message' => array(__d('files', 'アップロード不可のファイル形式です'))
					),
				),
			));
		}

		return $rules;
	}

}