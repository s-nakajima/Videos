<?php
/**
 * 動画リスト Element
 *   - $video: 動画1件
 *   - $videoBlockSetting: 動画セッティング
 *   - $isFfmpegEnable: Ffmpegを使うか
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

echo $this->NetCommonsHtml->css(array(
	'/likes/css/style.css',
	'/videos/css/style.css',
));
echo $this->NetCommonsHtml->script('/videos/js/videos.js');
?>

<div class="col-xs-12">
	<div class="panel panel-default video-thumbnail-row">
		<div class="media">
			<div class="media-left">
				<div>
					<?php /* サムネイル */ ?>
					<div>
						<a href="<?php echo $this->NetCommonsHtml->url(array('action' => 'view', 'key' => $video['Video']['key'])); ?>">
							<?php echo $this->NetCommonsHtml->image(
								$this->NetCommonsHtml->url(
									[
										'action' => 'file',
										'key' => $video['Video']['key'],
										Video::THUMBNAIL_FIELD,
									]
								),
								[
									'alt' => $video['Video']['title'],
									'class' => 'img-rounded video-thumbnail-image',
								]
							); ?>
						</a>
					</div>

					<?php /* 再生時間 */ ?>
					<?php echo $this->Video->playTime($video['Video']['video_time'], $isFfmpegEnable); ?>
				</div>
			</div>
			<div class="media-body">
				<small>
					<?php /* タイトル */ ?>
					<div>
						<a href="<?php echo $this->NetCommonsHtml->url('/videos/videos/view/' . Current::read('Block.id') . '/' . $video['Video']['key']); ?>">
							<h2>
								<?php echo $this->TitleIcon->titleIcon($video['Video']['title_icon']); ?>
								<?php echo $video['Video']['title']; ?>
							</h2>
						</a>
					</div>

					<?php /* 投稿者 */ ?>
					<?php echo $this->DisplayUser->handleLink($video); ?><br />

					<?php /* 再生回数 */ ?>
					<span class="video-count-icons">
						<span class="glyphicon glyphicon-play" aria-hidden="true"></span> <?php echo $video['Video']['play_number'] ?>
					</span>

					<?php /* コメント数 */ ?>
					<?php echo $this->ContentComment->count($video, array('class' => 'video-count-icons')); ?>

					<?php /* いいね数 */ ?>
					<?php echo $this->Like->display($videoBlockSetting, $video); ?>
				</small>
				<div>
					<?php /* ステータス */ ?>
					<?php echo $this->Workflow->label($video['Video']['status']); ?>
				</div>
			</div>
		</div>
	</div>
</div>
