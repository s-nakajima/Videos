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

<?php /* 上部ボタン */ ?>
<header>
	<div class="text-right">
		<?php
		if ($this->Workflow->canEdit("Videos.Video", $video)) {
			$editUrl = $this->NetCommonsHtml->url(array(
				'controller' => 'videos_edit',
				'action' => 'edit',
				'key' => $video['Video']['key']
			));
			echo $this->Button->editLink(__d('net_commons', 'Edit'),
				$editUrl,
				array('tooltip' => __d('net_commons', 'Edit'))
			);
		}
		?>
	</div>
</header>

<?php /* 動画 */ ?>
<div class="video-margin-row">
	<?php /* 動画プレイヤー */ ?>
	<?php echo $this->element('Videos/player', array(
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

<div class="video-margin-row" ng-controller="VideoView">
	<div class="panel panel-default video-detail">
		<div>
			<?php /* ステータス */ ?>
			<?php echo $this->Workflow->label($video['Video']['status']); ?>
		</div>
		<div class="nc-content-list">
			<?php /* タイトル */ ?>
			<h1>
				<?php echo $this->TitleIcon->titleIcon($video['Video']['title_icon']); ?>
				<?php echo h($video['Video']['title']); ?>
			</h1>
		</div>
		<div>
			<?php /* 登録日 */ ?>
			<?php echo __d('videos', 'Registration Date') . '：' . $this->Date->dateFormat($video['Video']['created']); ?>
			&nbsp;
			<?php /* 投稿者 */ ?>
			<?php echo $this->DisplayUser->handleLink($video, ['avatar' => true]); ?>
			&nbsp;
			<?php /* カテゴリ */ ?>
			<?php if ($video['Video']['category_id']) : ?>
				<?php echo __d('categories', 'Category') ?>:<?php echo $this->Html->link(
					$video['Category']['name'],
					$this->NetCommonsHtml->url(
						array(
							'controller' => 'videos',
							'action' => 'index',
							'frame_id' => Current::read('Frame.id'),
							'category_id' => $video['Video']['category_id']
						)
					)
				); ?>
			<?php endif; ?>
		</div>
		<div class="video-description">
			<?php /* 説明 */ ?>
			<?php echo nl2br(h($video['Video']['description'])); ?>
		</div>
		<div class="video-detail-links-row">
			<?php /* ブロック編集許可OK（編集長以上）ならダウンロードできる */ ?>
			<?php if (Current::permission('block_editable')): ?>
				<span class="video-detail-links">
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

			<span class="video-detail-links">
				<?php /* 埋め込みコード */ ?>
				<a href="" ng-click="embed();"><?php echo __d('videos', 'Embed'); ?></a>
			</span>

			<?php /* 再生回数 */ ?>
			<span class="video-count-icons">
				<span class="glyphicon glyphicon-play" aria-hidden="true"></span> <?php echo $video['Video']['play_number'] ?>
			</span>

			<?php /* いいね */ ?>
			<?php echo $this->Like->buttons('Video', $videoBlockSetting, $video); ?>
		</div>
		<div class="form-group video-embed">
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
			<?php /* Tags */ ?>
			<?php if (isset($video['Tag'])) : ?>
				<?php echo __d('tags', 'tag'); ?>:
				<?php foreach ($video['Tag'] as $tag): ?>
					<?php echo $this->NetCommonsHtml->link($tag['name'], array(
						'controller' => 'videos',
						'action' => 'tag',
						'id' => $tag['id'],
					)); ?>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
	</div>
</div>

<?php /* 関連動画 */ ?>
<div class="video-margin-row">
	<div id="nc-related-videos-<?php echo Current::read('Frame.id'); ?>" ng-controller="RelatedVideos">
		<?php $i = 0; ?>
		<?php foreach ($relatedVideos as $relatedVideo) : ?>
			<?php /* related-videoはJSで必要 */ ?>
			<article class="related-video <?php echo $i >= VideosController::START_LIMIT_RELATED_VIDEO ? 'hidden' : '' ?>">
				<?php echo $this->element('Videos.Videos/list', array(
					"video" => $relatedVideo,
					"style" => 'panel panel-default',
					"videoBlockSetting" => $videoBlockSetting,
					"isFfmpegEnable" => $isFfmpegEnable,
				)); ?>
			</article>
			<?php $i++; ?>
		<?php endforeach; ?>

		<?php /* もっと見る */ ?>
		<?php
		$hidden = '';
		if ($i <= VideosController::START_LIMIT_RELATED_VIDEO) {
			$hidden = 'hidden';
		}
		echo $this->NetCommonsForm->button(__d('net_commons', 'More'),
			[
				// related-video-moreはJSで必要
				'class' => 'btn btn-info btn-block related-video-more ' . $hidden,
				'ng-click' => 'more();',
			]
		); ?>
	</div>
</div>

<?php /* コンテンツコメント */ ?>
<?php echo $this->ContentComment->index($video);