<?php
/**
 * VideoBlockSetting Model
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('VideosAppModel', 'Videos.Model');

/**
 * VideoBlockSetting Model
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Videos\Model
 */
class VideoBlockSetting extends VideosAppModel {

/**
 * 動画再生プレイヤー jPlayer
 *
 * @var int
 */
	const VIDEO_PLAYER_JPLAYER = '1';

/**
 * 動画再生プレイヤー HTML5
 *
 * @var int
 */
	const VIDEO_PLAYER_HTML5 = '2';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'block_key' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'use_like' => array(
			'boolean' => array(
				'rule' => array('boolean'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'use_unlike' => array(
			'boolean' => array(
				'rule' => array('boolean'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'use_comment' => array(
			'boolean' => array(
				'rule' => array('boolean'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'agree' => array(
			'boolean' => array(
				'rule' => array('boolean'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'mail_notice' => array(
			'boolean' => array(
				'rule' => array('boolean'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'auto_video_convert' => array(
			'boolean' => array(
				'rule' => array('boolean'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'video_player' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'auto_play' => array(
			'boolean' => array(
				'rule' => array('boolean'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'buffer_time' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'comment_agree' => array(
			'boolean' => array(
				'rule' => array('boolean'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'comment_agree_mail_notice' => array(
			'boolean' => array(
				'rule' => array('boolean'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Block' => array(
			'className' => 'Blocks.Block',
			'foreignKey' => false,
			'conditions' => array(
				'Block.key = VideoBlockSetting.block_key',
			),
			'fields' => '',
			'order' => ''
		),
	);

/**
 * VideoBlockSettingデータ取得
 *
 * @param string $blockKey blocks.key
 * @param int $roomId rooms.id
 * @return array
 */
	public function getVideoBlockSetting($blockKey, $roomId) {
		$conditions = array(
			$this->alias . '.block_key' => $blockKey,
		);

		$joins = array(
			array(
				'type' => 'inner',
				'table' => 'blocks',
				'alias' => 'Block',
				'conditions' => array(
					$this->alias . '.block_key = Block.key',
					'Block.room_id = ' . $roomId,
				),
			),
		);

		if (!$videoBlockSetting = $this->find('first', array(
			'recursive' => -1,
			'joins' => $joins,
			'conditions' => $conditions,
			'order' => $this->alias . '.id DESC'
		))
		) {
			//初期値を設定
			$videoBlockSetting = $this->create();

			// 暫定対応(;'∀')
			// $videoBlockSetting = $this->create(); の戻り値、boolean型が"1","0"のまま。
			// $videoBlockSetting = $this->find('first', array()); の戻り値は、boolean型だとtrue,false。
			$videoBlockSetting['VideoBlockSetting']['use_like'] = $videoBlockSetting['VideoBlockSetting']['use_like'] == '1';
			$videoBlockSetting['VideoBlockSetting']['use_unlike'] = $videoBlockSetting['VideoBlockSetting']['use_unlike'] == '1';
			$videoBlockSetting['VideoBlockSetting']['use_comment'] = $videoBlockSetting['VideoBlockSetting']['use_comment'] == '1';
			$videoBlockSetting['VideoBlockSetting']['agree'] = $videoBlockSetting['VideoBlockSetting']['agree'] == '1';
			$videoBlockSetting['VideoBlockSetting']['mail_notice'] = $videoBlockSetting['VideoBlockSetting']['mail_notice'] == '1';
			$videoBlockSetting['VideoBlockSetting']['auto_video_convert'] = $videoBlockSetting['VideoBlockSetting']['auto_video_convert'] == '1';
			$videoBlockSetting['VideoBlockSetting']['auto_play'] = $videoBlockSetting['VideoBlockSetting']['auto_play'] == '1';
			$videoBlockSetting['VideoBlockSetting']['comment_agree'] = $videoBlockSetting['VideoBlockSetting']['comment_agree'] == '1';
			$videoBlockSetting['VideoBlockSetting']['comment_agree_mail_notice'] = $videoBlockSetting['VideoBlockSetting']['comment_agree_mail_notice'] == '1';
		}

		return $videoBlockSetting;
	}

/**
 * VideoBlockSettingデータ保存
 *
 * @param array $data received post data
 * @return mixed On success Model::$data if its not empty or true, false on failure
 * @throws InternalErrorException
 */
	public function saveVideoBlockSetting($data) {
		$this->loadModels(array(
			'VideoBlockSetting' => 'Videos.VideoBlockSetting',
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

			//ブロックの保存
			$block = $this->Block->saveByFrameId($data['Frame']['id'], $data['Block']);

			// block.keyを含める
			$data = Hash::merge(
				$data,
				array('VideoBlockSetting' => array('block_key' => $block['Block']['key']))
			);

			// 値をセット
			$this->set($data);

			$videoBlockSetting = $this->save(null, false);
			if (!$videoBlockSetting) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			$dataSource->commit();

			$videoBlockSetting = Hash::merge(
				$videoBlockSetting,
				array('Block' => array('id' => $block['Block']['id']))
			);

		} catch (InternalErrorException $ex) {
			$dataSource->rollback();
			CakeLog::write(LOG_ERR, $ex);
			throw $ex;
		}
		return $videoBlockSetting;
	}

/**
 * VideoBlockSettingデータ削除
 *
 * @param array $data received post data
 * @return mixed On success Model::$data if its not empty or true, false on failure
 * @throws InternalErrorException
 */
	public function deleteVideoBlockSetting($data) {
		$this->loadModels(array(
			'Block' => 'Blocks.Block',
			'Comment' => 'Comments.Comment',
			'ContentComment' => 'ContentComments.ContentComment',
			'FileModel' => 'Files.FileModel',		// FileUpload
			'VideoBlockSetting' => 'Videos.VideoBlockSetting',
			'Video' => 'Videos.Video',
		));

		//トランザクションBegin
		$dataSource = $this->getDataSource();
		$dataSource->begin();

		try {
			// VideoBlockSetting削除
			if (! $this->deleteAll(array($this->alias . '.block_key' => $data['Block']['key']), false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			//Blockデータ削除
			$this->Block->deleteBlock($data['Block']['key']);

			// 動画削除
			if (! $this->Video->deleteAll(array($this->Video->alias . '.block_id' => $data['Block']['id']), false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			// ファイル 削除 暫定として対応しない(;'∀')
			// 本来、データと物理ファイル削除。共通処理が完成したら、実装する

			// 承認コメント 暫定として対応しない(;'∀')
			// 本来削除。Commentsテーブルにblock_keyが実装されたら、削除実装する

			// コンテンツコメント 削除
			if (! $this->ContentComment->deleteAll(array($this->ContentComment->alias . '.block_key' => $data['Block']['key']), false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			$dataSource->commit();

		} catch (InternalErrorException $ex) {
			$dataSource->rollback();
			CakeLog::write(LOG_ERR, $ex);
			throw $ex;
		}
		return true;
	}
}
