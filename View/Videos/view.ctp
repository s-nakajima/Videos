<?php
/**
 * 動画詳細 template
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<?php echo $this->Html->css('/videos/css/style.css', false); ?>
<?php echo $this->Html->script('/videos/js/videos.js', false); ?>

<?php /* 上部ボタン */ ?>
<p>
<div class="row">
	<div class="col-xs-12 text-right">
		<?php if ($contentEditable): ?>
			<span class="nc-tooltip" tooltip="<?php echo __d('net_commons', 'Edit'); ?>">
				<a href="<?php echo $this->Html->url('/videos/videos_edit/edit/' . $frameId . '/' . $video['video']['key']); ?>" class="btn btn-primary">
					<span class="glyphicon glyphicon-edit"> </span>
				</a>
			</span>
		<?php endif; ?>
	</div>
</div>
</p>

<?php /* 動画 */ ?>
<div class="row">
	<div class="col-xs-12">
		<div style="padding-bottom: 20px;">
			<?php /* 動画プレイヤー */ ?>
			<?php echo $this->element('Videos/player', array(
				'fileMp4Url' => isset($video['fileMp4']['url']) ? $this->Html->url($video['fileMp4']['url']) : '',
				'fileThumbnailUrl' => isset($video['fileThumbnail']['urlMedium']) ? $this->Html->url($video['fileThumbnail']['urlMedium']) : '',
				'isAutoPlay' => $videoBlockSetting['autoPlay'],
			)); ?>
		</div>

		<div class="panel panel-default video-description">
			<div>
				<?php /* タイトル */ ?>
				<h4><?php echo $video['video']['title']; ?></h4>
			</div>
			<p>
			<div>
				<?php /* ステータス */ ?>
				<?php echo $this->element('NetCommons.status_label', array(
					'status' => $video['video']['status']
				)); ?>
			</div>
			</p>
			<div class="media">
				<div class="pull-left">
					<?php /* アバター 暫定対応(;'∀') */ ?>
					<a href="#">
						<?php echo $this->Html->image('/videos/img/avatar.png', array(
							'class' => 'media-object',
							'alt' => $video['userAttributesUser']['value'],
							'width' => '60',
							'height' => '60',
						)); ?>
					</a>
				</div>
				<div class="media-body">
					<div class="row">
						<div class="col-xs-6">
							<?php /* 投稿者 */ ?>
							<span style="padding-left: 5px; padding-right: 15px;"><a href="#"><?php echo $video['userAttributesUser']['value']; ?></a><br />
						</div>
						<div class="col-xs-6 text-right" style="font-size: 18px;">
							<?php /* 再生回数 */ ?>
							<?php echo sprintf(__d('videos', '再生回数 %s回'), $video['video']['playNumber']); ?>
						</div>
					</div>

					<div class="row">
						<div class="col-xs-12 text-right">
							<span style="padding-right: 15px;">
								<?php /* 埋め込みコード */ ?>
								<a href="#"><?php echo __d('videos', '埋め込みコード'); ?></a>
							</span>

							<?php /* いいね */ ?>
							<?php if ($videoBlockSetting['useLike']) : ?>
								<span class="text-left">
									<?php /* 高く評価、暫定対応(;'∀') */ ?>
									<?php /* コンテンツが読めたらいいね、よくないね出来る */ ?>
									<?php if ($contentReadable): ?>
										<a href="#"><span class="glyphicon glyphicon-thumbs-up" style="padding-right: 3px;"></span><?php //echo $video['video']['likesNumber']; ?>0</a>
									<?php else : ?>
										<span class="glyphicon glyphicon-thumbs-up" style="padding-right: 3px;"><?php //echo $video['video']['likesNumber']; ?>0</span>
									<?php endif; ?>
								</span>

								<?php /* よくないね */ ?>
								<?php if ($videoBlockSetting['useUnlike']) : ?>
									&nbsp;
									<span class="text-left">
										<?php /* コンテンツが読めたらいいね、よくないね出来る */ ?>
										<?php if ($contentReadable): ?>
											<a href="#"><span class="glyphicon glyphicon-thumbs-down" style="padding-right: 3px;"></span><?php //echo $video['video']['unlikesNumber']; ?>0</a>
										<?php else : ?>
											<span class="glyphicon glyphicon-thumbs-down" style="padding-right: 3px;"></span><?php //echo $video['video']['unlikesNumber']; ?>0
										<?php endif; ?>
									</span>
								  <?php endif; ?>
							 <?php endif; ?>
							&nbsp;
						</div>
					</div>
				</div>
				<div class="clearfix"></div>
			</div>
			<div>
				<?php /* 登録日 */ ?>
				<strong><?php echo __d('videos', '登録日') . '：' . $this->Date->dateFormat($video['video']['created']); ?></strong>
			</div>
			<div>
				<?php /* 本文 */ ?>
				<?php echo $video['video']['description']; ?>
			</div>
			<div>
				<?php /* Tags */ ?>
				<?php if (isset($video['tag'])) : ?>
					<?php foreach ($video['tag'] as $tag): ?>
						<?php echo $this->Html->link($tag['name'], array(
							'controller' => 'videos',
							'action' => 'tag',
							$frameId,
							'id' => $tag['id'],
						),
						array('class' => 'label label-default')); ?>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>

<?php /* 関連動画 */ ?>
<div class="row">
	<div class="col-xs-12">
		<p>
		<div id="nc-related-videos-<?php echo (int)$frameId; ?>" ng-controller="RelatedVideos">
			<?php $i = 0; ?>
			<?php foreach ($relatedVideos as $relatedVideo) : ?>
			<div class="row related-video <?php echo $i >= VideosController::START_LIMIT_RELATED_VIDEO ? 'hidden' : '' ?>">
				<div class="col-xs-12">
					<div style="border: 1px solid #ddd; padding: 5px; margin-bottom: 5px;">
						<?php /* サムネイル */ ?>
						<div class="media">
							<div class="pull-left">
								<div>
									<div>
										<a href="<?php echo $this->Html->url('/videos/videos/view/' . $frameId . '/' . $relatedVideo['video']['key']); ?>">
											<?php if (isset($relatedVideo['fileThumbnail']['urlThumbnail'])) : ?>
												<?php echo $this->Html->image($relatedVideo['fileThumbnail']['urlThumbnail'], array(
													'alt' => $relatedVideo['video']['title'],
												)); ?>
											<?php else : ?>
												<?php /* サムネイルなし */ ?>
												<?php echo $this->Html->image('/videos/img/noImage.png', array(
													'alt' => $relatedVideo['video']['title'],
												)); ?>
											<?php endif; ?>
										</a>
									</div>
									<?php /* 動画時間 */ ?>
									<div style="margin-top: -18px; margin-left: 65px;">
										<?php
											$videoTime = $relatedVideo['video']['videoTime'];
											$videoTime = floor($videoTime / 60) . ":" . str_pad(floor($videoTime - 60 * floor($videoTime / 60)), 2, '0');
										?>
										<span style="background-color: #000; color: #FFF; font-weight: bold; font-size: 11px; opacity: 0.75; padding: 0px 7px;">
									<?php echo $videoTime ?>
									</span>
									</div>
								</div>
							</div>
							<?php /* タイトル、投稿者、各種回数 */ ?>
							<div class="media-body">
								<small>
									<div>
										<a href="<?php echo $this->Html->url('/videos/videos/view/' . $frameId . '/' . $relatedVideo['video']['key']); ?>">
											<strong><?php echo $relatedVideo['video']['title']; ?></strong>
										</a>
									</div>
									<span style="padding-right: 15px;"><?php echo __d('videos', '投稿者'); ?></span><a href="#"><?php echo $relatedVideo['userAttributesUser']['value'] ?></a><br />
									<span style="padding-right: 15px;">
										<span class="glyphicon glyphicon-play" aria-hidden="true"></span> <?php echo $video['video']['playNumber'] ?>
									</span>
									<?php /* コメント数、暫定対応(;'∀') */ ?>
									<span class="glyphicon glyphicon-comment" aria-hidden="true"></span> <?php //echo $relatedVideo[0]['commentsNumber'] ?>0<br />

									<?php if ($videoBlockSetting['useLike']) : ?>
										<?php /* 高く評価、暫定対応(;'∀') */ ?>
										<span style="padding-right: 15px;">
											<span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span> <?php //echo $relatedVideo['video']['likesNumber'] ?>0
										</span>
										<?php if ($videoBlockSetting['useUnlike']) : ?>
											<?php /* 低く評価、暫定対応(;'∀') */ ?>
											<span class="glyphicon glyphicon-thumbs-down" aria-hidden="true"></span> <?php //echo $relatedVideo['video']['unlikesNumber'] ?>0
										<?php endif; ?>
									<?php endif; ?>
								</small>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php $i++; ?>
			<?php endforeach; ?>

			<?php /* もっと見る */ ?>
			<div>
				<button type="button" class="btn btn-info btn-block more related-video-more <?php echo $i <= VideosController::START_LIMIT_RELATED_VIDEO ? 'hidden' : '' ?>"
						ng-click="more();">
					<?php echo h(__d('net_commons', 'More')); ?>
				</button>
			</div>

		</div>
		</p>
	</div>
</div>

<?php /* コンテンツコメント */ ?>
<div class="row">
	<div class="col-xs-12">
		<?php /* コメントを利用しない or (コメント0件 and コメント投稿できない) */ ?>
		<?php if (!$videoBlockSetting['useComment'] || (!$contentComments && !$contentCommentCreatable)): ?>
			<?php /* 表示しない */ ?>
		<?php else : ?>
			<div class="panel panel-default">
				<?php echo $this->element('ContentComments.form', array(
					'formName' => 'Video',
				)); ?>
				<?php echo $this->element('ContentComments.index', array(
					'formName' => 'Video',
				)); ?>
			</div>
		<?php endif; ?>
	</div>
</div>

<?php /* 下部ボタン */ ?>
<div class="row">
	<div class="col-xs-12 text-center">
		<a href="<?php echo $this->Html->url('/videos/videos/index/' . $frameId) ?>" class="btn btn-default">
			<?php echo __d("videos", "一覧へ戻る") ?>
		</a>
	</div>
	<div class="col-xs-6 text-right">
	</div>
</div>
