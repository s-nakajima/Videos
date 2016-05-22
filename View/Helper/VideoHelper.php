<?php
/**
 * Video Helper
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AppHelper', 'View/Helper');

/**
 * Video Helper
 *
 * @package NetCommons\ContentComments\View\Helper
 */
class VideoHelper extends AppHelper {

/**
 * Other helpers used by FormHelper
 *
 * @var array
 */
	public $helpers = array(
		'Html',
		'Users.DisplayUser',
	);

/**
 * 再生時間表示
 *
 * 動画の再生時間の表示HTMLを返します。<br>
 * 秒を指定してください。<br>
 * 時：分：秒の時間フォーマットに直して表示します。
 *
 * #### Sample code
 * ##### template file(ctp file)
 * ```
 * <?php echo $this->Video->playTime($video['Video']['video_time']); ?>
 * ```
 *
 * @param int $totalSec 秒
 * @param bool $isFfmpegEnable ffmpeg有効フラグ
 * @return string HTML tags
 */
	public function playTime($totalSec, $isFfmpegEnable) {
		// ffmpeg=OFF
		if (! $isFfmpegEnable) {
			return;
		}

		$videoTime = $this->__convSecToHour($totalSec);

		/* @link http://book.cakephp.org/2.0/ja/core-libraries/helpers/html.html#HtmlHelper::tag */
		$output = $this->Html->tag('span', $videoTime, array(
			'class' => 'video-time-color',
		));
		$output = $this->Html->tag('div', $output, array(
			'class' => 'text-right video-time-position',
		));
		$output = $this->Html->tag('div', $output, array(
			'class' => 'video-time-width',
		));

		return $output;
	}

/**
 * 秒を時：分：秒に変更 (表示用)
 *
 * @param int $totalSec 秒
 * @return string 時：分：秒
 */
	private function __convSecToHour($totalSec) {
		$sec = $totalSec % 60;
		$min = (int)($totalSec / 60) % 60;
		$hour = (int)($totalSec / (60 * 60));
		if ($hour > 0) {
			return sprintf("%d:%02d:%02d", $hour, $min, $sec);
		}
		return sprintf("%d:%02d", $min, $sec);
	}
}
