<?php
/**
 * VideoBehaviorテスト用Model
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AppModel', 'Model');

/**
 * VideoBehaviorテスト用Model
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Videos\Test\test_app\Plugin\TestVideos\Model
 */
class TestVideoBehaviorModel extends AppModel {

/**
 * テーブル名
 *
 * @var mixed
 */
	public $useTable = 'videos';

/**
 * Alias name for model.
 *
 * @var string
 */
	public $alias = 'Video';

/**
 * 使用ビヘイビア
 *
 * @var array
 */
	public $actsAs = array(
		'NetCommons.OriginalKey',		// 自動でkeyセット
		'Videos.Video',
		'Files.Attachment' => [
			Video::VIDEO_FILE_FIELD,
			Video::THUMBNAIL_FIELD,
		],
	);

}
