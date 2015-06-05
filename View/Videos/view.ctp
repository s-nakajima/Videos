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

<?php
$this->Html->css(
	array(
		'/likes/css/style.css',
		'/videos/css/style.css',
	),
	array('plugin' => false, 'once' => true, 'inline' => false)
);
$this->Html->script(
	array(
		'/likes/js/likes.js',
		'/videos/js/videos.js',
	),
	array('plugin' => false, 'once' => true, 'inline' => false)
);?>

<div class="nc-content-list">
<article>

<?php /* 上部ボタン */ ?>
<?php if ($contentEditable): ?>
	<header>
		<div class="row">
			<div class="col-xs-12 text-right" style="padding-bottom: 10px;">
				<span class="nc-tooltip" tooltip="<?php echo __d('net_commons', 'Edit'); ?>">
					<a href="<?php echo $this->Html->url('/videos/videos_edit/edit/' . $frameId . '/' . $video['video']['key']); ?>" class="btn btn-primary">
						<span class="glyphicon glyphicon-edit"> </span>
					</a>
				</span>
			</div>
		</div>
	</header>
<?php endif; ?>

<?php /* 動画 */ ?>
<div class="row" ng-controller="VideoView">
	<div class="col-xs-12">
		<div style="padding-bottom: 20px;">
			<?php /* 動画プレイヤー */ ?>
			<?php echo $this->element('Videos/player', array(
				'fileMp4Url' => $video['fileMp4']['url'],
				'fileThumbnailUrl' => $video['fileThumbnail']['urlBig'],
				'isAutoPlay' => $videoBlockSetting['autoPlay'],
			)); ?>
		</div>

		<div class="panel panel-default video-description">
			<div>
				<?php /* タイトル */ ?>
				<h1><?php echo $video['video']['title']; ?></h1>
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
							<?php echo sprintf(__d('videos', 'Views %s times'), $video['video']['playNumber']); ?>
						</div>
					</div>

					<div class="row">
						<div class="col-xs-12 text-right" <?php echo $this->element('Likes.like_init_attributes', array(
							'contentKey' => $video['video']['key'],
							'disabled' => !(! isset($video['like']) && $video['video']['status'] === NetCommonsBlockComponent::STATUS_PUBLISHED),
							'likeCounts' => (int)$video['video']['likeCounts'],
							'unlikeCounts' => (int)$video['video']['unlikeCounts'],
						)); ?>>
							<?php if ($contentEditable): ?>
								<span style="padding-right: 15px;">
									<?php /* ダウンロード */ ?>
									<a href="<?php echo isset($video['fileMp4']['download']) ? $this->Html->url($video['fileMp4']['download']) : ''; ?>">
										<?php echo __d('videos', 'Downloads'); ?>
									</a>
								</span>
							<?php endif; ?>

							<span style="padding-right: 15px;">
								<?php /* 埋め込みコード */ ?>
								<a href="" ng-click="embed();"><?php echo __d('videos', 'Embed'); ?></a>
							</span>

							<?php /* いいね */ ?>
							<?php if ($videoBlockSetting['useLike']) : ?>
								<span class="text-left">
									<?php /* コンテンツが読めたらいいね、よくないね出来る */ ?>
									<?php if ($contentReadable): ?>
										<?php echo $this->element('Likes.like_button', array('isLiked' => Like::IS_LIKE)); ?>
									<?php else : ?>
										<span class="glyphicon glyphicon-thumbs-up" style="padding-right: 3px;"><?php echo $video['video']['likeCounts']; ?></span>
									<?php endif; ?>
								</span>

								<?php /* よくないね */ ?>
								<?php if ($videoBlockSetting['useUnlike']) : ?>
									&nbsp;
									<span class="text-left">
										<?php /* コンテンツが読めたらいいね、よくないね出来る */ ?>
										<?php if ($contentReadable): ?>
											<?php echo $this->element('Likes.like_button', array('isLiked' => Like::IS_UNLIKE)); ?>
										<?php else : ?>
											<span class="glyphicon glyphicon-thumbs-down" style="padding-right: 3px;"></span><?php echo $video['video']['unlikeCounts']; ?>
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
			<div class="form-group video-embed" style="display: none;">
				<?php /* 埋め込みコード(非表示) */ ?>
				<input type="text" class="form-control video-embed-text" value='<iframe width="400" height="300" src="<?php echo $this->Html->url($video['fileMp4']['url'], true); ?>" frameborder="0" allowfullscreen></iframe>'>
			</div>
			<div>
				<?php /* 登録日 */ ?>
				<strong><?php echo __d('videos', 'Registration Date') . '：' . $this->Date->dateFormat($video['video']['created']); ?></strong>
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
				<article>
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
															'style' => 'width: 140px; height: auto;'
														)); ?>
													<?php endif; ?>
												</a>
											</div>
											<?php /* 動画時間 */ ?>
											<div style="width: 140px;">
												<div class="text-right" style="margin-top: -20px; margin-right: 2px;">
													<span style="background-color: #000; color: #FFF; font-weight: bold; font-size: 11px; opacity: 0.75; padding: 0px 7px;">
														<?php echo $relatedVideo['video']['videoTimeView']; ?>
													</span>
												</div>
											</div>
										</div>
									</div>
									<?php /* タイトル、投稿者、各種回数 */ ?>
									<div class="media-body">
										<small>
											<div>
												<a href="<?php echo $this->Html->url('/videos/videos/view/' . $frameId . '/' . $relatedVideo['video']['key']); ?>">
													<h2><?php echo $relatedVideo['video']['title']; ?></h2>
												</a>
											</div>
											<a href="#"><?php echo $relatedVideo['userAttributesUser']['value'] ?></a><br />
											<span style="padding-right: 15px;">
												<span class="glyphicon glyphicon-play" aria-hidden="true"></span> <?php echo $relatedVideo['video']['playNumber'] ?>
											</span>
											<span style="padding-right: 15px;">
												<span class="glyphicon glyphicon-comment" aria-hidden="true"></span> <?php echo $relatedVideo['contentCommentCnt']['cnt']; ?>
											</span>

											<?php if ($videoBlockSetting['useLike']) : ?>
												<?php /* いいね */ ?>
												<span style="padding-right: 15px;">
													<span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span> <?php echo $relatedVideo['video']['likeCounts'] ?>
												</span>
												<?php if ($videoBlockSetting['useUnlike']) : ?>
													<?php /* よくないね */ ?>
													<span class="glyphicon glyphicon-thumbs-down" aria-hidden="true"></span> <?php echo $relatedVideo['video']['unlikeCounts'] ?>
												<?php endif; ?>
											<?php endif; ?>
										</small>
									</div>
								</div>
							</div>
						</div>
					</div>
				</article>
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
		<?php echo $this->element('ContentComments.index', array(
			'formName' => 'Video',
			'useComment' => $videoBlockSetting['useComment'],
		)); ?>
	</div>
</div>

<?php /* 下部ボタン */ ?>
<footer>
	<div class="row">
		<div class="col-xs-12 text-center">
			<a href="<?php echo $this->Html->url(isset($current['page']) ? '/' . $current['page']['permalink'] : null); ?>" class="btn btn-default">
				<?php echo __d("videos", "Back to list") ?>
			</a>
		</div>
	</div>
</footer>

</article>
</div>
