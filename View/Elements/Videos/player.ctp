<?php
/**
 * 動画プレイヤー template
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

$fileMp4Url = isset($fileMp4Url) ? $this->Html->url(h($fileMp4Url)) : '';
$fileThumbnailUrl = isset($fileThumbnailUrl) ? $this->Html->url(h($fileThumbnailUrl)) : '';
$isAutoPlay = $isAutoPlay ? 'autoplay' : '';
?>

<video poster="<?php echo $fileThumbnailUrl; ?>"
	<?php echo $isAutoPlay; ?>
	   width="100%"
	   height="100%"
	   controls="controls">
	<source src="<?php echo $fileMp4Url; ?>" type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"'>
	<p><?php echo __d('videos', '動画を再生するにはvideoタグをサポートしたブラウザが必要です。'); ?></p>
</video>
