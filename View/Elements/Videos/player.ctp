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

$fileMp4Url = isset($fileMp4Url) ? $this->NetCommonsHtml->url($fileMp4Url) : '';
$fileThumbnailUrl = isset($fileThumbnailUrl) ? $this->NetCommonsHtml->url($fileThumbnailUrl) : '';
$isAutoPlay = $isAutoPlay ? 'autoplay' : '';
?>

<?php /* 右クリック抑止 */ ?>
<script type="text/javascript">
	$(function(){
		$('#nc-video-player-<?php echo Current::read('Frame.id'); ?>').on('contextmenu',function(e){
			return false;
		});
	});
</script>

<video id="nc-video-player-<?php echo Current::read('Frame.id'); ?>"
		poster="<?php echo $fileThumbnailUrl; ?>"
		<?php echo $isAutoPlay; ?>
		width="100%"
		height="100%"
		controls="controls">
	<source src="<?php echo $fileMp4Url; ?>" type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"'>
	<p><?php echo __d('videos', 'To play the video requires a browser that supports the video tag.'); ?></p>
</video>
