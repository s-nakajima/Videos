<?php
/**
 * 一覧 template
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<style>
	@media (min-width: 800px) {
		.videos-row-height {
			height: 190px;
		}
	}
</style>

<?php /* 上部ボタン */ ?>
<?php if ($contentEditable): ?>
	<div class="row">
		<div class="col-xs-12 text-right">
			<span class="nc-tooltip" tooltip="<?php echo __d('net_commons', 'Add'); ?>">
				<a href="<?php echo $this->Html->url('/videos/videos/add/' . $frameId); ?>" class="btn btn-success">
					<span class="glyphicon glyphicon-plus"> </span>
				</a>
			</span>
			<span>
				<a href="<?php echo $this->Html->url('/videos/videoFrameSettings/index/' . $frameId); ?>" class="btn btn-default">
					<span class="glyphicon glyphicon-cog"> </span>
				</a>
			</span>
		</div>
	</div>
<?php endif; ?>

<?php /* 検索 */ ?>
<p>
<div class="row">
	<div class="col-xs-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<?php echo $this->Form->create('Videos', array(
					'name' => 'form',
					'url' => '/videos/videos/index/' . $frameId,
					'type' => 'get',
					'novalidate' => true
				)); ?>

				<div class="input-group">
					<label class="sr-only"><?php echo __d('videos', 'Search'); ?></label>
					<?php echo $this->Form->input('search', array(
						'label' => false,
						'class' => 'form-control',
						'placeholder' => __d('videos', 'Search'),
						'autofocus' => true,
					)); ?>
					<span class="input-group-btn">
							<span class="nc-tooltip" tooltip="<?php echo __d('videos', 'Search'); ?>">
								<?php echo $this->Form->button('<span class="glyphicon glyphicon-search" aria-hidden="true"></span>', array(
									'class' => 'btn btn-primary',
								)); ?>
							</span>
						</span>
				</div>

				<div class="checkbox">
					<label><input type="checkbox"><?php echo __d('videos', 'Title'); ?></label>
					<label><input type="checkbox"><?php echo __d('videos', 'Description'); ?></label>
					<label><input type="checkbox"><?php echo __d('videos', 'Tag'); ?></label>
				</div>

				<?php echo $this->Form->end(); ?>
			</div>
		</div>
	</div>
</div>
</p>

<?php /* 件数、ソート順、表示件数 */ ?>
<p>
<div class="row">
	<div class="col-xs-3">
		<div class="form-inline text-left text-nowrap">
			<strong><?php echo sprintf(__d('videos', '%s'), $this->Paginator->param('count')); ?></strong>
		</div>
	</div>
	<div class="col-xs-9">
		<div class="form-inline text-right">

			<div class="form-group">
				<span class="sr-only"><?php echo __d('videos', '表示順'); ?></span>
				<?php echo $this->Form->input('display_order',
					array(
						'label' => false,
						'type' => 'select',
						'class' => 'form-control',
						'options' => array(
							VideoFrameSetting::DISPLAY_ORDER_NEW => __d('videos', '新着順'),
							VideoFrameSetting::DISPLAY_ORDER_TITLE => __d('videos', 'タイトル順'),
							VideoFrameSetting::DISPLAY_ORDER_PLAY => __d('videos', '再生回数順'),
							VideoFrameSetting::DISPLAY_ORDER_LIKE => __d('videos', '評価順'),
						),
						'selected' => $displayOrder,
					)) ?>
			</div>

			<div class="form-group">
				<span class="sr-only"><?php echo __d('videos', '表示件数'); ?></span>
				<?php echo $this->Form->input('display_number',
					array(
						'label' => false,
						'type' => 'select',
						'class' => 'form-control',
						'options' => array(
							1 => sprintf(__d('videos', '%s'), '1'),
							5 => sprintf(__d('videos', '%s'), '5'),
							10 => sprintf(__d('videos', '%s'), '10'),
							20 => sprintf(__d('videos', '%s'), '20'),
							50 => sprintf(__d('videos', '%s'), '50'),
							100 => sprintf(__d('videos', '%s'), '100'),
						),
						'selected' => $displayNumber,
					)) ?>
			</div>

		</div>
	</div>
</div>
</p>

<?php /* 動画一覧 */ ?>
<p>
<div class="row">
	<?php foreach ($videos as $video) : ?>
		<div class="col-md-4 col-xs-12">
			<div style="border: 1px solid #ddd; padding: 5px; margin-bottom: 5px;">
				<?php /* サムネイル */ ?>
				<div class="row videos-row-height">
					<div class="col-md-12 col-xs-4">
						<div>
							<div>
								<a href="<?php echo $this->Html->url('/videos/videos/view/' . $frameId . '/' . $video['video']['key']); ?>">
									<?php if (isset($video['fileThumbnail']['urlThumbnail'])) : ?>
										<?php echo $this->Html->image($video['fileThumbnail']['urlThumbnail'], array('alt' => $video['video']['title'])); ?>
									<?php else : ?>
										<?php /* サムネイルなし */ ?>
										<?php echo $this->Html->image('/videos/img/noImage.png', array('alt' => $video['video']['title'])); ?>
									<?php endif; ?>
								</a>
							</div>
							<div style="margin-top: -18px; margin-left: 65px;">
								<?php
								$video_time = $video['video']['videoTime'];
								$video_time = floor($video_time / 60) . ":" . floor($video_time - 60 * floor($video_time / 60));
								?>
								<span style="background-color: #000; color: #FFF; font-weight: bold; font-size: 11px; opacity: 0.75; padding: 0px 7px;">
									<?php echo $video_time ?>
									</span>
							</div>
						</div>
					</div>
					<?php /* タイトル、投稿者、各種回数 */ ?>
					<div class="col-md-12 col-xs-8">
						<small>
							<div>
								<a href="<?php echo $this->Html->url('/videos/videos/view/' . $frameId . '/' . $video['video']['key']); ?>">
									<strong><?php echo $this->Text->Truncate($video['video']['title'], VIDEO::SHORT_TITLE_LENGTH); ?></strong>
								</a>
							</div>
							<span style="padding-right: 15px;"><?php echo __d('videos', '投稿者'); ?></span><a href="#"><?php echo $video['userAttributesUser']['value'] ?></a><br />
							<span style="padding-right: 15px;">
								<span class="glyphicon glyphicon-play" aria-hidden="true"></span> <?php echo $video['video']['playNumber'] ?>
							</span>
							<span class="glyphicon glyphicon-comment" aria-hidden="true"></span> <?php echo $video['video']['commentsNumber'] ?><br />

							<?php if ($videoFrameSetting['displayLike']) : ?>
								<span style="padding-right: 15px;">
									<span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span> <?php echo $video['video']['likesNumber'] ?>
								</span>
								<?php if ($videoFrameSetting['displayUnlike']) : ?>
									<span class="glyphicon glyphicon-thumbs-down" aria-hidden="true"></span> <?php echo $video['video']['unlikesNumber'] ?>
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
	<?php endforeach; ?>
</div>
</p>

<?php /* ページャ */ ?>
<p>
<div class="row">
	<div class="col-xs-12">
		<div class="text-center">
			<nav>
				<ul class="pagination">
					<?php echo $this->Paginator->first('«', array(
						'tag' => 'li',
						'url' => Hash::merge(
							array('controller' => 'videos', 'action' => 'index', $frameId),
							$this->Paginator->params['named']
						)
					)); ?>
					<?php echo $this->Paginator->numbers(array(
						'tag' => 'li',
						'currentTag' => 'a',
						'currentClass' => 'active',
						'separator' => '',
						'first' => false,
						'last' => false,
						'modulus' => '4',
						'url' => Hash::merge(
							array('controller' => 'videos', 'action' => 'index', $frameId),
							$this->Paginator->params['named']
						)
					)); ?>
					<?php echo $this->Paginator->last('»', array(
						'tag' => 'li',
						'url' => Hash::merge(
							array('controller' => 'videos', 'action' => 'index', $frameId),
							$this->Paginator->params['named']
						)
					)); ?>
				</ul>
			</nav>
		</div>
	</div>
</div>
</p>
