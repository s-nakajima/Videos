<?php
/**
 * index template
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<?php /* 上部ボタン */ ?>
<?php if ($contentEditable): ?>
	<div class="row">
		<div class="col-xs-12 text-right">
			<span class="nc-tooltip" tooltip="<?php echo h(__d('net_commons', 'Add')); ?>">
				<a href="<?php echo $this->Html->url('/videos/videos/add/' . $frameId) ?>" class="btn btn-success">
					<span class="glyphicon glyphicon-plus"> </span>
				</a>
			</span>
			<span>
				<a href="<?php echo $this->Html->url('/videos/videoFrameSettings/index/' . $frameId) ?>" class="btn btn-default">
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
					<label class="sr-only"><?php echo h(__d('videos', 'Search')); ?></label>
					<?php echo $this->Form->input('search',
						array(
							'label' => false,
							'class' => 'form-control',
							'placeholder' => h(__d('videos', 'Search')),
							'autofocus' => true,
						)) ?>
					<span class="input-group-btn">
							<span class="nc-tooltip" tooltip="<?php echo h(__d('videos', 'Search')); ?>">
								<?php echo $this->Form->button('<span class="glyphicon glyphicon-search" aria-hidden="true"></span>',
									array(
										'class' => 'btn btn-primary',
									)) ?>
							</span>
						</span>
				</div>

				<div class="checkbox">
					<label><input type="checkbox"><?php echo h(__d('videos', 'title')); ?></label>
					<label><input type="checkbox"><?php echo h(__d('videos', 'description')); ?></label>
					<label><input type="checkbox"><?php echo h(__d('videos', 'tag')); ?></label>
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
			<strong><?php echo h(__d('videos', '999件')); ?></strong>
		</div>
	</div>
	<div class="col-xs-9">
		<div class="form-inline text-right">

			<div class="form-group">
				<span class="sr-only"><?php echo h(__d('videos', '表示順')); ?></span>
				<?php echo $this->Form->input('displayOrder',
					array(
						'label' => false,
						'type' => 'select',
						'class' => 'form-control',
						'options' => array(
							'new' => h(__d('videos', '新着順')),
							'title' => h(__d('videos', 'タイトル順')),
							'play' => h(__d('videos', '再生回数順')),
							'like' => h(__d('videos', '評価順')),
						),
						'selected' => 'new',
					)) ?>
			</div>

			<div class="form-group">
				<span class="sr-only"><?php echo h(__d('videos', '表示件数')); ?></span>
				<?php echo $this->Form->input('displayNumber',
					array(
						'label' => false,
						'type' => 'select',
						'class' => 'form-control',
						'options' => array(
							1 => h(__d('videos', '1件')),
							5 => h(__d('videos', '5件')),
							10 => h(__d('videos', '10件')),
							20 => h(__d('videos', '20件')),
							50 => h(__d('videos', '50件')),
							100 => h(__d('videos', '100件')),
						),
						'selected' => 5,
						'autofocus' => true,
					)) ?>
			</div>

		</div>
	</div>
</div>
</p>

<?php /* 動画一覧 */ ?>
<p>
<div class="row">

	<div class="col-md-4 col-xs-12">
		<div style="border: 1px solid #ddd; padding: 5px; margin-bottom: 5px;">
			<div class="pull-left" style="margin: 5px;">
				<div>
					<!-- <a href="<?php //echo $this->Html->url('/videos/videos/view/' . $frameId . '/' . $videos['id']); ?>"> -->
					<a href="<?php echo $this->Html->url('/videos/videos/view/' . $frameId . '/1' ); ?>">
						<?php echo $this->Html->image('/videos/img/thumbnail.jpg', array('class' => 'img-responsive','alt' => '動画タイトル動画タイトル動画タイトル動画タイトル')); ?>
					</a>
				</div>
				<div style="margin-top: -18px; height: 14px;text-align: right;">
					<span style="background-color: #000; color: #FFF; font-weight: bold; font-size: 11px; opacity: 0.75; padding: 0px 7px;">99:99</span>
				</div>
			</div>
			<div class="pull-left" style="margin-top: 5px;">
				<!-- <a href="<?php //echo $this->Html->url('/videos/videos/view/' . $frameId . '/' . $videos['id']); ?>"> -->
				<a href="<?php echo $this->Html->url('/videos/videos/view/' . $frameId . '/1' ); ?>">
					<strong>動画タイトル動画タイトル動画タイトル動画タイトル</strong>
				</a>
				<br />
				<span style="padding-right: 15px;">投稿者</span><a href="#">大学 太郎</a><br />
				<span style="padding-right: 15px;">
					<span class="glyphicon glyphicon-play" aria-hidden="true"></span> 999
				</span>
				<span class="glyphicon glyphicon-comment" aria-hidden="true"></span> 999<br>
				<span style="padding-right: 15px;">
					<span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span> 999
				</span>
				<span class="glyphicon glyphicon-thumbs-down" aria-hidden="true"></span> 999
			</div>

			<?php /* ステータス */ ?>
			<?php echo $this->element('NetCommons.status_label',
				array('status' => '')); ?>

			<div class="clearfix"></div>
		</div>
	</div>

	<div class="col-md-4 col-xs-12">
		<div style="border: 1px solid #ddd; padding: 5px; margin-bottom: 5px;">
			<div class="pull-left" style="margin: 5px;">
				<div>
					<!-- <a href="<?php //echo $this->Html->url('/videos/videos/view/' . $frameId . '/' . $videos['id']); ?>"> -->
					<a href="<?php echo $this->Html->url('/videos/videos/view/' . $frameId . '/1' ); ?>">
						<?php echo $this->Html->image('/videos/img/thumbnail.jpg', array('class' => 'img-responsive','alt' => '動画タイトル動画タイトル動画タイトル動画タイトル')); ?>
					</a>
				</div>
				<div style="margin-top: -18px; height: 14px;text-align: right;">
					<span style="background-color: #000; color: #FFF; font-weight: bold; font-size: 11px; opacity: 0.75; padding: 0px 7px;">99:99</span>
				</div>
			</div>
			<div class="pull-left" style="margin-top: 5px;">
				<!-- <a href="<?php //echo $this->Html->url('/videos/videos/view/' . $frameId . '/' . $videos['id']); ?>"> -->
				<a href="<?php echo $this->Html->url('/videos/videos/view/' . $frameId . '/1' ); ?>">
					<strong>動画タイトル動画タイトル動画タイトル動画タイトル</strong>
				</a>
				<br />
				<span style="padding-right: 15px;">投稿者</span><a href="#">大学 太郎</a><br />
				<span style="padding-right: 15px;">
					<span class="glyphicon glyphicon-play" aria-hidden="true"></span> 999
				</span>
				<span class="glyphicon glyphicon-comment" aria-hidden="true"></span> 999<br>
				<span style="padding-right: 15px;">
					<span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span> 999
				</span>
				<span class="glyphicon glyphicon-thumbs-down" aria-hidden="true"></span> 999
			</div>

			<?php /* ステータス */ ?>
			<?php echo $this->element('NetCommons.status_label',
				array('status' => '')); ?>

			<div class="clearfix"></div>
		</div>
	</div>

	<div class="col-md-4 col-xs-12">
		<div style="border: 1px solid #ddd; padding: 5px; margin-bottom: 5px;">
			<div class="pull-left" style="margin: 5px;">
				<div>
					<!-- <a href="<?php //echo $this->Html->url('/videos/videos/view/' . $frameId . '/' . $videos['id']); ?>"> -->
					<a href="<?php echo $this->Html->url('/videos/videos/view/' . $frameId . '/1' ); ?>">
						<?php echo $this->Html->image('/videos/img/thumbnail.jpg', array('class' => 'img-responsive','alt' => '動画タイトル動画タイトル動画タイトル動画タイトル')); ?>
					</a>
				</div>
				<div style="margin-top: -18px; height: 14px;text-align: right;">
					<span style="background-color: #000; color: #FFF; font-weight: bold; font-size: 11px; opacity: 0.75; padding: 0px 7px;">99:99</span>
				</div>
			</div>
			<div class="pull-left" style="margin-top: 5px;">
				<!-- <a href="<?php //echo $this->Html->url('/videos/videos/view/' . $frameId . '/' . $videos['id']); ?>"> -->
				<a href="<?php echo $this->Html->url('/videos/videos/view/' . $frameId . '/1' ); ?>">
					<strong>動画タイトル動画タイトル動画タイトル動画タイトル</strong>
				</a>
				<br />
				<span style="padding-right: 15px;">投稿者</span><a href="#">大学 太郎</a><br />
				<span style="padding-right: 15px;">
					<span class="glyphicon glyphicon-play" aria-hidden="true"></span> 999
				</span>
				<span class="glyphicon glyphicon-comment" aria-hidden="true"></span> 999<br>
				<span style="padding-right: 15px;">
					<span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span> 999
				</span>
				<span class="glyphicon glyphicon-thumbs-down" aria-hidden="true"></span> 999
			</div>

			<?php /* ステータス */ ?>
			<?php echo $this->element('NetCommons.status_label',
				array('status' => '')); ?>

			<div class="clearfix"></div>
		</div>
	</div>

	<div class="col-md-4 col-xs-12">
		<div style="border: 1px solid #ddd; padding: 5px; margin-bottom: 5px;">
			<div class="pull-left" style="margin: 5px;">
				<div>
					<!-- <a href="<?php //echo $this->Html->url('/videos/videos/view/' . $frameId . '/' . $videos['id']); ?>"> -->
					<a href="<?php echo $this->Html->url('/videos/videos/view/' . $frameId . '/1' ); ?>">
						<?php echo $this->Html->image('/videos/img/thumbnail.jpg', array('class' => 'img-responsive','alt' => '動画タイトル動画タイトル動画タイトル動画タイトル')); ?>
					</a>
				</div>
				<div style="margin-top: -18px; height: 14px;text-align: right;">
					<span style="background-color: #000; color: #FFF; font-weight: bold; font-size: 11px; opacity: 0.75; padding: 0px 7px;">99:99</span>
				</div>
			</div>
			<div class="pull-left" style="margin-top: 5px;">
				<!-- <a href="<?php //echo $this->Html->url('/videos/videos/view/' . $frameId . '/' . $videos['id']); ?>"> -->
				<a href="<?php echo $this->Html->url('/videos/videos/view/' . $frameId . '/1' ); ?>">
					<strong>動画タイトル動画タイトル動画タイトル動画タイトル</strong>
				</a>
				<br />
				<span style="padding-right: 15px;">投稿者</span><a href="#">大学 太郎</a><br />
				<span style="padding-right: 15px;">
					<span class="glyphicon glyphicon-play" aria-hidden="true"></span> 999
				</span>
				<span class="glyphicon glyphicon-comment" aria-hidden="true"></span> 999<br>
				<span style="padding-right: 15px;">
					<span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span> 999
				</span>
				<span class="glyphicon glyphicon-thumbs-down" aria-hidden="true"></span> 999
			</div>

			<?php /* ステータス */ ?>
			<?php echo $this->element('NetCommons.status_label',
				array('status' => '')); ?>

			<div class="clearfix"></div>
		</div>
	</div>

	<div class="col-md-4 col-xs-12">
		<div style="border: 1px solid #ddd; padding: 5px; margin-bottom: 5px;">
			<div class="pull-left" style="margin: 5px;">
				<div>
					<!-- <a href="<?php //echo $this->Html->url('/videos/videos/view/' . $frameId . '/' . $videos['id']); ?>"> -->
					<a href="<?php echo $this->Html->url('/videos/videos/view/' . $frameId . '/1' ); ?>">
						<?php echo $this->Html->image('/videos/img/thumbnail.jpg', array('class' => 'img-responsive','alt' => '動画タイトル動画タイトル動画タイトル動画タイトル')); ?>
					</a>
				</div>
				<div style="margin-top: -18px; height: 14px;text-align: right;">
					<span style="background-color: #000; color: #FFF; font-weight: bold; font-size: 11px; opacity: 0.75; padding: 0px 7px;">99:99</span>
				</div>
			</div>
			<div class="pull-left" style="margin-top: 5px;">
				<!-- <a href="<?php //echo $this->Html->url('/videos/videos/view/' . $frameId . '/' . $videos['id']); ?>"> -->
				<a href="<?php echo $this->Html->url('/videos/videos/view/' . $frameId . '/1' ); ?>">
					<strong>動画タイトル動画タイトル動画タイトル動画タイトル</strong>
				</a>
				<br />
				<span style="padding-right: 15px;">投稿者</span><a href="#">大学 太郎</a><br />
				<span style="padding-right: 15px;">
					<span class="glyphicon glyphicon-play" aria-hidden="true"></span> 999
				</span>
				<span class="glyphicon glyphicon-comment" aria-hidden="true"></span> 999<br>
				<span style="padding-right: 15px;">
					<span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span> 999
				</span>
				<span class="glyphicon glyphicon-thumbs-down" aria-hidden="true"></span> 999
			</div>

			<?php /* ステータス */ ?>
			<?php echo $this->element('NetCommons.status_label',
				array('status' => '')); ?>

			<div class="clearfix"></div>
		</div>
	</div>

</div>
</p>

<?php /* ページャ */ ?>
<p>
<div class="row">
	<div class="col-xs-12">
		<div class="text-center">
			<nav>
				<ul class="pagination">
					<li class="disabled">
						<a aria-label="Previous" href=""><span aria-hidden="true">&laquo;</span></a>
					</li>
					<li class="active">
						<a href="#">1<span class="sr-only">(current)</span></a>
					</li>
					<li>
						<a href="#">2</a>
					</li>
					<li>
						<a href="#">3</a>
					</li>
					<li>
						<a href="#">4</a>
					</li>
					<li>
						<a href="#">5</a>
					</li>
					<li>
						<a aria-label="Next" href="#">
							<span aria-hidden="true">&raquo;</span>
						</a>
					</li>
				</ul>
			</nav>
		</div>
	</div>
</div>
</p>
