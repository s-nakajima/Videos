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

echo $this->NetCommonsHtml->css('/videos/css/style.css');
echo $this->NetCommonsHtml->script('/videos/js/videos.js');
?>

<div class="nc-content-list">
	<article>
		<header>
			<?php /* ブロック名, タグ検索表示 */ ?>
			<?php echo $this->NetCommonsHtml->blockTitle($listTitle); ?>

			<?php /* 上部ボタン */ ?>
			<?php if (Current::permission('content_creatable')) : ?>
				<div class="row video-margin-row">
					<div class="col-xs-12 text-right">
						<?php
						$addUrl = $this->NetCommonsHtml->url(array('controller' => 'videos_edit', 'action' => 'add'));
						echo $this->Button->addLink('',
							$addUrl,
							array('tooltip' => __d('videos', 'Add video'))
						);
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
			<div class="row video-margin-row">
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
								'Like.weight.desc' => array(
									'label' => __d('videos', 'Reviews'),
									'sort' => 'Like.weight',
									'direction' => 'desc'
								),
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

			<?php /* 動画一覧 */ ?>
			<div class="row">
				<?php foreach ($videos as $video) : ?>
					<article>
						<?php echo $this->element('Videos.Videos/list', array(
							"video" => $video,
							"videoBlockSetting" => $videoBlockSetting,
							"isFfmpegEnable" => $isFfmpegEnable,
						)); ?>
					</article>
				<?php endforeach; ?>
			</div>

			<?php /* ページャ */ ?>
			<?php echo $this->element('NetCommons.paginator'); ?>
		<?php endif; ?>

	</article>
</div>
