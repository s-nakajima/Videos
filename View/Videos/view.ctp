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

echo $this->NetCommonsHtml->css(array(
	'/likes/css/style.css',
	'/videos/css/style.css',
));

echo $this->NetCommonsHtml->script(array(
	'/likes/js/likes.js',
	'/videos/js/videos.js',
	'/authorization_keys/js/authorization_keys.js',
));
?>

<div class="nc-content-list">
<article>

<?php /* 上部ボタン */ ?>
<?php if ($this->Workflow->canEdit("Videos.Video", $video)) : ?>
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
				//'fileMp4Url' => $video['FileMp4']['url'],
				//'fileThumbnailUrl' => $video['FileThumbnail']['url_big'],
				'fileMp4Url' => $this->NetCommonsHtml->url(
					[
						'action' => 'file',
						'key' => $video['Video']['key'],
						Video::VIDEO_FILE_FIELD,
					]
				),
				'fileThumbnailUrl' => $this->NetCommonsHtml->url(
					[
						'action' => 'file',
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
				<div class="media-left">
					<?php /* アバター */ ?>
					<?php /** @see DisplayUserHelper::avatarLink() */ ?>
					<?php echo $this->DisplayUser->avatarLink($video, array(
						'class' => 'img-rounded',
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
							<?php /* ブロック編集許可OK（編集長以上）ならダウンロードできる */ ?>
							<?php if (Current::permission('block_editable')): ?>
								<span style="padding-right: 15px;">
									<?php /* ダウンロード */ ?>
									<a authorization-keys-popup-link frame-id="<?php echo Current::read('Frame.id'); ?>"
									   url="<?php echo NetCommonsUrl::actionUrl(array(
											'plugin' => 'videos',
											'controller' => 'videos',
											'action' => 'download',
											Current::read('Block.id'),
											$video['Video']['key'],
											'frame_id' => Current::read('Frame.id')
										)); ?>"
										popup-title="<?php echo __d('authorization_keys', 'Compression password'); ?>"
										popup-label="<?php echo __d('authorization_keys', 'Compression password'); ?>"
										popup-placeholder="<?php echo __d('authorization_keys', 'please input compression password'); ?>">
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
				<input type="text" class="form-control video-embed-text" value='<iframe width="400" height="300" src="<?php echo $this->NetCommonsHtml->url(
					[
						'action' => 'file',
						'key' => $video['Video']['key'],
						Video::VIDEO_FILE_FIELD,
					],
					true
				); ?>" frameborder="0" allowfullscreen></iframe>'>
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
				<?php if (isset($video['Tag'])) : ?>
					<?php foreach ($video['Tag'] as $tag): ?>
						<?php echo $this->NetCommonsHtml->link($tag['name'], array(
							'controller' => 'videos',
							'action' => 'tag',
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
						<div class="col-xs-12 col-lg-6">
							<div style="border: 1px solid #ddd; padding: 5px; margin-bottom: 5px;">
								<?php /* サムネイル */ ?>
								<div class="media">
									<div class="media-left">
										<div>
											<div>
												<a href="<?php echo $this->NetCommonsHtml->url(array('action' => 'view', 'key' => $relatedVideo['Video']['key'])); ?>">
													<?php echo $this->NetCommonsHtml->image(
														$this->NetCommonsHtml->url(
															[
																'action' => 'file',
																'key' => $relatedVideo['Video']['key'],
																Video::THUMBNAIL_FIELD,
															]
														),
														[
															'alt' => $relatedVideo['Video']['title'],
															'style' => 'width: 140px; height: auto;'
														]
													); ?>
												</a>
											</div>
											<?php /* 再生時間 */ ?>
											<?php echo $this->Video->playTime($relatedVideo['Video']['video_time'], $isFfmpegEnable); ?>
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

											<?php echo $this->ContentComment->count($relatedVideo); ?>
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
<?php echo $this->ContentComment->index($video); ?>

<?php /* 下部ボタン */ ?>
<footer>
	<div class="row">
		<div class="col-xs-12 text-center">
			<?php echo $this->BackTo->pageLinkButton(__d("videos", "Back to list"), array('icon' => '')); ?>
		</div>
	</div>
</footer>

</article>
</div>
