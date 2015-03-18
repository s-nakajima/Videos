<?php
/**
 * view_contents template
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 * @package NetCommons\Videos\View\Elements\Videos
 */
?>

<p>
<div class="row">
	<div class="col-xs-12">

		<div class="panel panel-default">
			<div class="panel-heading">

		<div class="input-group">
			<label class="sr-only"><?php echo h(__d('videos', 'Search')); ?></label>
			<input type="text" class="form-control" placeholder="<?php echo h(__d('videos', 'Search')); ?>">
			<span class="input-group-btn">
				<span class="nc-tooltip" tooltip="<?php echo __d('videos', 'Search'); ?>">
					<button type="submit" class="btn btn-primary">
						<span class="glyphicon glyphicon-search" aria-hidden="true"></span>
					</button>
				</span>
			</span>
		</div>

		<div class="checkbox">
			<label><input type="checkbox"><?php echo h(__d('videos', 'title')); ?></label>
			<label><input type="checkbox"><?php echo h(__d('videos', 'description')); ?></label>
			<label><input type="checkbox"><?php echo h(__d('videos', 'tag')); ?></label>
		</div>

			</div>
		</div>

	</div>
</div>
</p>
<p>
<div class="row">
	<div class="col-xs-6">
		<div class="form-inline text-left">
			<strong>999件</strong>
		</div>
	</div>
	<div class="col-xs-6">
		<div class="form-inline text-right">

			<div class="btn-group">
				<button class="btn btn-default" type="button">新着順</button>
				<button aria-expanded="false" data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button">
					<span class="caret"></span>
					<span class="sr-only">並び替え</span>
				</button>
				<ul role="menu" class="dropdown-menu">
					<li>
						<a href="#">新着順</a>
					</li>
					<li>
						<a href="#">タイトル順</a>
					</li>
					<li>
						<a href="#">再生回数順</a>
					</li>
					<li>
						<a href="#">評価順</a>
					</li>
				</ul>
			</div>

			<div class="btn-group">
				<button class="btn btn-default" type="button">5件</button>
				<button aria-expanded="false" data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button">
					<span class="caret"></span>
					<span class="sr-only">表示件数</span>
				</button>
				<ul role="menu" class="dropdown-menu">
					<li>
						<a href="#">1件</a>
					</li>
					<li>
						<a href="#">5件</a>
					</li>
					<li>
						<a href="#">10件</a>
					</li>
					<li>
						<a href="#">20件</a>
					</li>
					<li>
						<a href="#">50件</a>
					</li>
					<li>
						<a href="#">100件</a>
					</li>
				</ul>
			</div>

		</div>
	</div>
</div>
</p>
<p>
<div class="row">

	<div class="col-md-4 col-xs-12">
		<div style="border: 1px solid #ddd; padding: 5px; margin-bottom: 5px;">
			<div class="pull-left" style="margin: 5px;">
				<div>
					<a href="#">
						<?php echo $this->Html->image('/videos/img/thumbnail.jpg', array('class' => 'img-responsive','alt' => '動画タイトル動画タイトル動画タイトル動画タイトル')); ?>
					</a>
				</div>
				<div style="margin-top: -18px; height: 14px;text-align: right;">
					<span style="background-color: #000; color: #FFF; font-weight: bold; font-size: 11px; opacity: 0.75; padding: 0px 7px;">99:99</span>
				</div>
			</div>
			<div class="pull-left" style="margin-top: 5px;">
				<a href="#">
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

			<!-- ステータス -->
			<?php echo $this->element('NetCommons.status_label',
				array('status' => '')); ?>

			<div class="clearfix"></div>
		</div>
	</div>

	<div class="col-md-4 col-xs-12">
		<div style="border: 1px solid #ddd; padding: 5px; margin-bottom: 5px;">
			<div class="pull-left" style="margin: 5px;">
				<div>
					<a href="#">
						<?php echo $this->Html->image('/videos/img/thumbnail.jpg', array('class' => 'img-responsive','alt' => '動画タイトル動画タイトル動画タイトル動画タイトル')); ?>
					</a>
				</div>
				<div style="margin-top: -18px; height: 14px;text-align: right;">
					<span style="background-color: #000; color: #FFF; font-weight: bold; font-size: 11px; opacity: 0.75; padding: 0px 7px;">99:99</span>
				</div>
			</div>
			<div class="pull-left" style="margin-top: 5px;">
				<a href="#">
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

			<!-- ステータス -->
			<?php echo $this->element('NetCommons.status_label',
				array('status' => '')); ?>

			<div class="clearfix"></div>
		</div>
	</div>
	<div class="col-md-4 col-xs-12">
		<div style="border: 1px solid #ddd; padding: 5px; margin-bottom: 5px;">
			<div class="pull-left" style="margin: 5px;">
				<div>
					<a href="#">
						<?php echo $this->Html->image('/videos/img/thumbnail.jpg', array('class' => 'img-responsive','alt' => '動画タイトル動画タイトル動画タイトル動画タイトル')); ?>
					</a>
				</div>
				<div style="margin-top: -18px; height: 14px;text-align: right;">
					<span style="background-color: #000; color: #FFF; font-weight: bold; font-size: 11px; opacity: 0.75; padding: 0px 7px;">99:99</span>
				</div>
			</div>
			<div class="pull-left" style="margin-top: 5px;">
				<a href="#">
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

			<!-- ステータス -->
			<?php echo $this->element('NetCommons.status_label',
				array('status' => '')); ?>

			<div class="clearfix"></div>
		</div>
	</div>
	<div class="col-md-4 col-xs-12">
		<div style="border: 1px solid #ddd; padding: 5px; margin-bottom: 5px;">
			<div class="pull-left" style="margin: 5px;">
				<div>
					<a href="#">
						<?php echo $this->Html->image('/videos/img/thumbnail.jpg', array('class' => 'img-responsive','alt' => '動画タイトル動画タイトル動画タイトル動画タイトル')); ?>
					</a>
				</div>
				<div style="margin-top: -18px; height: 14px;text-align: right;">
					<span style="background-color: #000; color: #FFF; font-weight: bold; font-size: 11px; opacity: 0.75; padding: 0px 7px;">99:99</span>
				</div>
			</div>
			<div class="pull-left" style="margin-top: 5px;">
				<a href="#">
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

			<!-- ステータス -->
			<?php echo $this->element('NetCommons.status_label',
				array('status' => '')); ?>

			<div class="clearfix"></div>
		</div>
	</div>
	<div class="col-md-4 col-xs-12">
		<div style="border: 1px solid #ddd; padding: 5px; margin-bottom: 5px;">
			<div class="pull-left" style="margin: 5px;">
				<div>
					<a href="#">
						<?php echo $this->Html->image('/videos/img/thumbnail.jpg', array('class' => 'img-responsive','alt' => '動画タイトル動画タイトル動画タイトル動画タイトル')); ?>
					</a>
				</div>
				<div style="margin-top: -18px; height: 14px;text-align: right;">
					<span style="background-color: #000; color: #FFF; font-weight: bold; font-size: 11px; opacity: 0.75; padding: 0px 7px;">99:99</span>
				</div>
			</div>
			<div class="pull-left" style="margin-top: 5px;">
				<a href="#">
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

			<!-- ステータス -->
			<?php echo $this->element('NetCommons.status_label',
				array('status' => '')); ?>

			<div class="clearfix"></div>
		</div>
	</div>

</div>
</p>
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
