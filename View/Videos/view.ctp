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
);
?>

<div class="nc-content-list">
<article>

<?php /* 上部ボタン */ ?>
<?php if (Current::permission('content_editable')) : ?>
	<header>
		<div class="row">
			<div class="col-xs-12 text-right" style="padding-bottom: 10px;">
				<span class="nc-tooltip" tooltip="<?php echo __d('net_commons', 'Edit'); ?>">
					<a href="<?php echo $this->NetCommonsHtml->url(array(
						'controller' => 'videos_edit',
						'action' => 'edit',
						'key' => $video['Video']['key']
					)); ?>" class="btn btn-primary">
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
//				'fileMp4Url' => $video['FileMp4']['url'],
//				'fileThumbnailUrl' => $video['FileThumbnail']['url_big'],
				'fileMp4Url' => $this->NetCommonsHtml->url(
					[
						'action' => 'download',
						'key' => $video['Video']['key'],
						Video::VIDEO_FILE_FIELD,
					]
				),
				'fileThumbnailUrl' => $this->NetCommonsHtml->url(
					[
						'action' => 'download',
						'key' => $video['Video']['key'],
						Video::THUMBNAIL_FIELD,
						'big',
					]
				),
				'isAutoPlay' => $videoBlockSetting['auto_play'],
			)); ?>
		</div>

		<div class="panel panel-default video-description">
			<div>
				<?php /* タイトル */ ?>
				<h1><?php echo $video['Video']['title']; ?></h1>
			</div>
			<p>
			<div>
				<?php /* ステータス */ ?>
				<?php echo $this->Workflow->label($video['Video']['status']); ?>
			</div>
			</p>
			<div class="media">
				<div class="pull-left">
					<?php /* アバター */ ?>
					<?php echo $this->DisplayUser->avatarLink($video, array(
						'class' => '',
					)); ?>
				</div>
				<div class="media-body">
					<div class="row">
						<div class="col-xs-6">
							<?php /* 投稿者 */ ?>
							<?php echo $this->DisplayUser->handleLink($video); ?>
						</div>
						<div class="col-xs-6 text-right" style="font-size: 18px;">
							<?php /* 再生回数 */ ?>
							<?php echo sprintf(__d('videos', 'Views %s times'), $video['Video']['play_number']); ?>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 text-right">
<!--						<div class="col-xs-12 text-right" --><?php //echo $this->element('Likes.like_init_attributes', array(
//							'contentKey' => $video['Video']['key'],
//							'disabled' => !(! isset($video['like']) && $video['Video']['status'] === WorkflowComponent::STATUS_PUBLISHED),
//							'likeCounts' => (int)$video['Video']['like_counts'],
//							'unlikeCounts' => (int)$video['Video']['unlike_counts'],
//						)); ?>

							<?php if (Current::permission('content_editable')): ?>
								<span style="padding-right: 15px;">
									<?php /* ダウンロード */ ?>
									<a href="<?php echo $this->NetCommonsHtml->url(
										[
											'action' => 'download',
											'key' => $video['Video']['key'],
											Video::VIDEO_FILE_FIELD,
										]
									); ?>">
										<?php echo __d('videos', 'Downloads'); ?>
									</a>
								</span>
							<?php endif; ?>

							<span style="padding-right: 15px;">
								<?php /* 埋め込みコード */ ?>
								<a href="" ng-click="embed();"><?php echo __d('videos', 'Embed'); ?></a>
							</span>

							<?php /* いいね */ ?>
							<?php echo $this->Like->buttons('Video', $videoBlockSetting, $video); ?>
							&nbsp;
						</div>
					</div>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="form-group video-embed" style="display: none;">
				<?php /* 埋め込みコード(非表示) */ ?>
				<input type="text" class="form-control video-embed-text" value='<iframe width="400" height="300" src="<?php echo $this->NetCommonsHtml->url($video['FileMp4']['url'], true); ?>" frameborder="0" allowfullscreen></iframe>'>
			</div>
			<div>
				<?php /* 登録日 */ ?>
				<strong><?php echo __d('videos', 'Registration Date') . '：' . $this->Date->dateFormat($video['Video']['created']); ?></strong>
			</div>
			<div>
				<?php /* 本文 */ ?>
				<?php echo $video['Video']['description']; ?>
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
		<div id="nc-related-videos-<?php echo Current::read('Frame.id'); ?>" ng-controller="RelatedVideos">
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
												<a href="<?php echo $this->NetCommonsHtml->url('/videos/videos/view/' . Current::read('Block.id') . '/' . $relatedVideo['Video']['key']); ?>">
													<?php if (isset($relatedVideo['FileThumbnail']['url_thumbnail'])) : ?>
														<?php echo $this->Html->image($relatedVideo['FileThumbnail']['url_thumbnail'], array(
															'alt' => $relatedVideo['Video']['title'],
															'style' => 'width: 140px; height: auto;'
														)); ?>
													<?php endif; ?>
												</a>
											</div>
											<?php /* 再生時間 */ ?>
											<?php /* ffmpeg=ON */ ?>
											<?php if (Video::isFfmpegEnable()) : ?>
												<div style="width: 140px;">
													<div class="text-right" style="margin-top: -20px; margin-right: 2px;">
														<span style="background-color: #000; color: #FFF; font-weight: bold; font-size: 11px; opacity: 0.75; padding: 0px 7px;">
															<?php echo $relatedVideo['Video']['video_time_view']; ?>
														</span>
													</div>
												</div>
											<?php endif; ?>
										</div>
									</div>
									<?php /* タイトル、投稿者、各種回数 */ ?>
									<div class="media-body">
										<small>
											<div>
												<a href="<?php echo $this->NetCommonsHtml->url('/videos/videos/view/' . Current::read('Block.id') . '/' . $relatedVideo['Video']['key']); ?>">
													<h2><?php echo $relatedVideo['Video']['title']; ?></h2>
												</a>
											</div>
											<a href="#"><?php echo $relatedVideo['User']['handlename'] ?></a><br />
											<span style="padding-right: 15px;">
												<span class="glyphicon glyphicon-play" aria-hidden="true"></span> <?php echo $relatedVideo['Video']['play_number'] ?>
											</span>
<!--											<span style="padding-right: 15px;">-->
<!--												<span class="glyphicon glyphicon-comment" aria-hidden="true"></span> --><?php //echo $relatedVideo['ContentCommentCnt']['cnt']; ?>
<!--											</span>-->

											<?php echo $this->ContentComment->count($videoBlockSetting, $relatedVideo); ?>
											<?php echo $this->Like->display($videoBlockSetting, $relatedVideo); ?>
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
<?php //echo $this->element('ContentComments.index', array(
//	'contentKey' => $video['Video']['key'], // helperで取得。項目名固定。model名＋key固定。できる。
//	'useCommentApproval' => $videoBlockSetting['comment_agree'], // helperで取得。項目名固定。Videoが合わせる。できる
//	'useComment' => $videoBlockSetting['use_comment'], // helperで取得。項目名固定。できる
//	'contentCommentCnt' => $video['ContentCommentCnt']['cnt'], // cntをコンポーネント
//)); ?>
<?php echo $this->ContentComment->index('Video', $videoBlockSetting, $video, array(
	'use_comment' => 'use_comment',
	'use_comment_approval' => 'comment_agree',
)); ?>

<?php /* 下部ボタン */ ?>
<footer>
	<div class="row">
		<div class="col-xs-12 text-center">
			<?php echo $this->NetCommonsHtml->link(
				__d("videos", "Back to list"),
				NetCommonsUrl::backToPageUrl(),
				array(
					'title' => __d("videos", "Back to list"),
					'class' => "btn btn-default",
				)
			); ?>
		</div>
	</div>
</footer>

</article>
</div>
