<?php
/**
 * 動画一覧 template
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<?php echo $this->Html->script('/videos/js/videos.js', array('plugin' => false, 'once' => true, 'inline' => false)); ?>

<div class="nc-content-list">
<article>

	<?php /* ブロック未選択 */ ?>
	<?php if (empty($frame['block_id'])) : ?>

		<div><?php echo __d('videos', 'There are no videos that are currently published.'); ?></div>

		<?php /* ブロック選択済み */ ?>
	<?php else : ?>
		<header>
			<?php /* タグ検索時、タイトル表示 */ ?>
			<?php if (!empty($listTitle)) : ?>
				<h1><?php echo $listTitle ?></h1>
			<?php endif; ?>

			<?php /* 上部ボタン */ ?>
			<?php if (Current::permission('content_editable')) : ?>
				<div class="row">
					<div class="col-xs-12 text-right">
						<?php
						$addUrl = $this->NetCommonsHtml->url(array(
							'controller' => 'videos_edit',
							'action' => 'add',
							'frame_id' => Current::read('Frame.id')
						));
						echo $this->Button->addLink('',
							$addUrl,
						array('tooltip' => __d('videos', '動画の追加')));
						?>
					</div>
				</div>
			<?php endif; ?>
		</header>

		<?php /* 検索 */ ?>
		<?php if ($this->Paginator->param('count') == 0) : ?>
			<div><?php echo __d('videos', 'There are no videos that are currently published.'); ?></div>
		<?php else : ?>
			<?php /* 件数、ソート順、表示件数 */ ?>
			<p>
			<div class="row">
				<div class="col-sm-3 col-xs-4">
					<div class="form-inline text-left text-nowrap">
						<strong><?php echo sprintf(__d('videos', '%s items'), $this->Paginator->param('count')); ?></strong>
					</div>
				</div>
				<div class="col-sm-9 col-xs-8">
					<div class="form-inline text-right">

						<?php /* ソート順 */ ?>
						<span class="btn-group text-left">
							<?php $displayOrderOptions = array(
								'Video.created.desc' => array(
									'label' => __d('videos', 'Newest'),
									'sort' => 'Video.created',
									'direction' => 'desc'
								),
								'Video.title.asc' => array(
									'label' => __d('videos', 'By title'),
									'sort' => 'Video.title',
									'direction' => 'asc'
								),
								'Video.play_number.desc' => array(
									'label' => __d('videos', 'Viewed'),
									'sort' => 'Video.play_number',
									'direction' => 'desc'
								),
								// 暫定対応(;'∀') 評価順はLikesプラグインが対応していないので、対応を先送りする
								/*'Video.like_counts.desc' => array(
									'label' => __d('videos', 'Reviews'),
									'sort' => 'Video.like_counts',
									'direction' => 'desc'
								),*/
							); ?>

							<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
								<?php echo $displayOrderOptions[$displayOrderPaginator]['label']; ?>
								<span class="caret"></span>
							</button>

							<ul class="dropdown-menu" role="menu">
								<?php foreach ($displayOrderOptions as $key => $sort) : ?>
									<li<?php echo $key == $displayOrderPaginator ? ' class="active"' : ''; ?>>
										<?php echo $this->Paginator->link($sort['label'], array('sort' => $sort['sort'], 'direction' => $sort['direction'])); ?>
									</li>
								<?php endforeach; ?>
							</ul>
						</span>

						<?php /* 表示件数 */ ?>
						<?php echo $this->DisplayNumber->dropDownToggle(); ?>

					</div>
				</div>
			</div>
			</p>

			<?php /* 動画一覧 */ ?>
			<div class="row">
<?php //var_dump($videos); ?>
				<?php foreach ($videos as $video) : ?>
					<article>
						<div class="col-xs-12">
							<?php /* サムネイル */ ?>
							<div class="row videos-row-height" style="border: 1px solid #ddd; padding: 5px; margin: 0px 0px 5px 0px;">
								<div class="media">

									<div class="pull-left">
										<div>
<!--											--><?php
//											$urlThumbnail = null;
//											if (isset($video['FileThumbnail']['url_thumbnail'])) {
//												$urlThumbnail = $this->Html->image($video['FileThumbnail']['url_thumbnail'], array(
//													'alt' => $video['Video']['title'],
//													'style' => 'width: 140px; height: auto;'
//												));
//											}
//											?>
<!--											--><?php //echo $this->NetCommonsHtml->link(
//												$urlThumbnail,
//												array('action' => 'view', 'key' => $video['Video']['key'])
//											); ?>

											<a href="<?php echo $this->NetCommonsHtml->url('/videos/videos/view/' . Current::read('Block.id') . '/' . $video['Video']['key']); ?>">
<!--												--><?php //if (isset($video['FileThumbnail']['url_thumbnail'])) : ?>
<!--													--><?php //echo $this->Html->image($video['FileThumbnail']['url_thumbnail'], array(
//														'alt' => $video['Video']['title'],
//														'style' => 'width: 140px; height: auto;'
//													)); ?>
													<?php echo $this->Html->image(
														$this->NetCommonsHtml->url(
																[
																	'action' => 'download',
																	'key' => $video['Video']['key'],
																	Video::THUMBNAIL_FIELD,
																]
														),
														[
															'alt' => $video['Video']['title'],
															'style' => 'width: 140px; height: auto;'
														]
													); ?>
<!--												--><?php //endif; ?>
											</a>
										</div>
										<?php /* 再生時間 */ ?>
										<?php /* ffmpeg=ON */ ?>
										<?php if (Video::isFfmpegEnable()) : ?>
											<div style="width: 140px;">
												<div class="text-right" style="margin-top: -20px; margin-right: 2px;">
													<span style="background-color: #000; color: #FFF; font-weight: bold; font-size: 11px; opacity: 0.75; padding: 0px 7px;">
														<?php echo $video['Video']['video_time_view']; ?>
													</span>
												</div>
											</div>
										<?php endif; ?>
									</div>

									<div class="media-body">
										<div class="row">
											<?php /* タイトル、投稿者、各種回数 */ ?>
											<div class="col-xs-12">
												<small>
													<div>
														<a href="<?php echo $this->NetCommonsHtml->url('/videos/videos/view/' . Current::read('Block.id') . '/' . $video['Video']['key']); ?>">
															<h2><?php echo $video['Video']['title']; ?></h2>
														</a>
													</div>
													<a href="#"><?php echo $video['User']['handlename'] ?></a><br />
													<span style="padding-right: 15px;">
														<span class="glyphicon glyphicon-play" aria-hidden="true"></span> <?php echo $video['Video']['play_number'] ?>
													</span>
													<span style="padding-right: 15px;">
														<span class="glyphicon glyphicon-comment" aria-hidden="true"></span> <?php echo $video['ContentCommentCnt']['cnt']; ?>
													</span>

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
							</div>
						</div>
					</article>
				<?php endforeach; ?>
			</div>

			<?php /* ページャ */ ?>
			<?php echo $this->element('NetCommons.paginator'); ?>
		<?php endif; ?>
	<?php endif; ?>

</article>
</div>
