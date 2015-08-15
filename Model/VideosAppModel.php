<?php
/**
 * VideosApp Model
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AppModel', 'Model');

/**
 * VideosApp Model
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Videos\Model
 * @property Block $Block
 * @property BlockRolePermission $BlockRolePermission
 * @property Comment $Comment
 * @property ContentComment $ContentComment
 * @property TagsContent $TagsContent
 * @property Like $Like
 */
class VideosAppModel extends AppModel {

/**
 * FFMPEG有効フラグをセット
 * (;'∀')暫定対応。今後Video 分割する。
 *
 * @return bool
 */
	public static function isFfmpegEnable() {
		if (isset(Video::$__isFfmpegEnable)) {
			return Video::$__isFfmpegEnable;
		}

		$strCmd = 'which ' . Video::FFMPEG_PATH . ' 2>&1';
		exec($strCmd, $arr);

		// ffmpegコマンドがあるかどうかは環境に依存するため、true or false の両方を通すテストケースは書けない。
		// isFfmpegEnableをモックにして、強制的に true or false を返してテストするので、問題ないと思う。

		if (isset($arr[0]) && $arr[0] === Video::FFMPEG_PATH) {
			// コマンドあり
			Video::$__isFfmpegEnable = true;
		} else {
			// コマンドなし
			Video::$__isFfmpegEnable = false;
		}

		return Video::$__isFfmpegEnable;
	}
}
