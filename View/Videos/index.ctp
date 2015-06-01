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

<?php /* 暫定対応(;'∀') <div class="frame"> 画面遷移してもブロック枠が残るようになったら不要 */ ?>
<div class="frame">
<div class="nc-content-list">
<article>

<?php /* ブロック未選択 */ ?>
<?php if (empty($frame['blockId'])) : ?>

	<div><?php echo __d('videos', '現在公開されている動画はありません。'); ?></div>

	<?php /* ブロック選択済み */ ?>
<?php else : ?>

    <header>
		<?php /* タグ検索時、タイトル表示 */ ?>
		<?php if (!empty($listTitle)) : ?>
			<h1><?php echo $listTitle ?></h1>
		<?php endif; ?>

		<?php /* 上部ボタン */ ?>
		<?php if ($contentEditable): ?>
			<div class="row">
				<div class="col-xs-12 text-right">
					<span class="nc-tooltip" tooltip="<?php echo __d('net_commons', 'Add'); ?>">
						<a href="<?php echo $this->Html->url('/videos/videos_edit/add/' . $frameId); ?>" class="btn btn-success">
							<span class="glyphicon glyphicon-plus"> </span>
						</a>
					</span>
				</div>
			</div>
		<?php endif; ?>
    </header>

	<?php /* 検索 */ ?>
	<?php if ($this->Paginator->param('count') == 0) : ?>
		<div><?php echo __d('videos', '現在公開されている動画はありません。'); ?></div>
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
					<span class="btn-group">
						<?php $displayOrderOptions = array(
							'Video.created.desc' => array(
								'label' => __d('videos', '新着順'),
								'sort' => 'Video.created',
								'direction' => 'desc'
							),
							'Video.title.asc' => array(
								'label' => __d('videos', 'タイトル順'),
								'sort' => 'Video.title',
								'direction' => 'asc'
							),
							'Video.play_number.desc' => array(
								'label' => __d('videos', '再生回数順'),
								'sort' => 'Video.play_number',
								'direction' => 'desc'
							),
							// 暫定対応(;'∀') 評価順はLikesプラグインが対応していないので、対応を先送りする
							/*'Video.like_counts.desc' => array(
								'label' => __d('videos', '評価順'),
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
					<span class="btn-group">
						<?php $displayNumberOptions = VideoFrameSetting::getDisplayNumberOptions(); ?>
						<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
							<?php echo $displayNumberOptions[$this->Paginator->param('limit')]; ?>
							<span class="caret"></span>
						</button>
						<ul class="dropdown-menu" role="menu">
							<?php foreach ($displayNumberOptions as $count => $label) : ?>
								<li<?php echo (int)$this->Paginator->param('limit') === $count ? ' class="active"' : ''; ?>>
									<?php echo $this->Paginator->link($label, array('limit' => $count, 'page' => '1')); ?>
								</li>
							<?php endforeach; ?>
						</ul>
					</span>

				</div>
			</div>
        </div>
        </p>

		<?php /* 動画一覧 */ ?>
        <div class="row">
			<?php foreach ($videos as $video) : ?>
				<article>
					<div class="col-xs-12">
						<?php /* サムネイル */ ?>
						<div class="row videos-row-height" style="border: 1px solid #ddd; padding: 5px; margin: 0px 0px 5px 0px;">
							<div class="media">

								<div class="pull-left">
									<div>
										<a href="<?php echo $this->Html->url('/videos/videos/view/' . $frameId . '/' . $video['video']['key']); ?>">
											<?php if (isset($video['fileThumbnail']['urlThumbnail'])) : ?>
												<?php echo $this->Html->image($video['fileThumbnail']['urlThumbnail'], array(
													'alt' => $video['video']['title'],
													'style' => 'width: 100%; height: auto;'
												)); ?>
											<?php else : ?>
												<?php /* サムネイルなし */ ?>
												<?php echo $this->Html->image('/videos/img/noImage.png', array(
													'alt' => $video['video']['title'],
													'style' => 'width: 100%; height: auto;'
												)); ?>
											<?php endif; ?>
										</a>
									</div>
									<?php /* 動画時間 */ ?>
									<div style="margin-top: -18px; margin-left: 65px;">
										<span style="background-color: #000; color: #FFF; font-weight: bold; font-size: 11px; opacity: 0.75; padding: 0px 7px;">
											<?php echo $this->Time->format($video['video']['videoTime'], '%M:%S'); ?>
										</span>
									</div>
								</div>

								<div class="media-body">
									<div class="row">
										<?php /* タイトル、投稿者、各種回数 */ ?>
										<div class="col-xs-12">
											<small>
												<div>
													<a href="<?php echo $this->Html->url('/videos/videos/view/' . $frameId . '/' . $video['video']['key']); ?>">
														<h2><?php echo $video['video']['title']; ?></h2>
													</a>
												</div>
												<a href="#"><?php echo $video['userAttributesUser']['value'] ?></a><br />
												<span style="padding-right: 15px;">
													<span class="glyphicon glyphicon-play" aria-hidden="true"></span> <?php echo $video['video']['playNumber'] ?>
												</span>
												<span style="padding-right: 15px;">
													<span class="glyphicon glyphicon-comment" aria-hidden="true"></span> <?php echo $video['contentCommentCnt']['cnt']; ?>
												</span>

												<?php if ($videoBlockSetting['useLike']) : ?>
													<?php /* いいね */ ?>
													<span style="padding-right: 15px;">
														<span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span> <?php echo $video['video']['likeCounts'] ?>
													</span>
													<?php if ($videoBlockSetting['useUnlike']) : ?>
														<?php /* よくないね */ ?>
														<span class="glyphicon glyphicon-thumbs-down" aria-hidden="true"></span> <?php echo $video['video']['unlikeCounts'] ?>
													<?php endif; ?>
												<?php endif; ?>
											</small>
											<div>
												<?php /* ステータス */ ?>
												<?php echo $this->element('NetCommons.status_label', array(
													'status' => $video['video']['status']
												)); ?>
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
        <footer>
			<div class="row">
				<div class="col-xs-12">
					<div class="text-center">
						<nav>
							<ul class="pagination">
								<?php echo $this->Paginator->first('«', array(
									'tag' => 'li',
								)); ?>
								<?php echo $this->Paginator->numbers(array(
									'tag' => 'li',
									'currentTag' => 'a',
									'currentClass' => 'active',
									'separator' => '',
									'first' => false,
									'last' => false,
									'modulus' => '4',
								)); ?>
								<?php echo $this->Paginator->last('»', array(
									'tag' => 'li',
								)); ?>
							</ul>
						</nav>
					</div>
				</div>
			</div>
        </footer>
	<?php endif; ?>
<?php endif; ?>

</article>
</div>
</div>
