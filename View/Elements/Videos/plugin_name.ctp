<?php
/**
 * プラグイン名 template
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<?php /* 登録・編集時の画面上部表示。まだ暫定だよ(;'∀') */ ?>
<?php /* 設定のプラグイン名表示。コアで共通化の予定(*´ω｀) */ ?>

<?php $this->start('title'); ?>
<?php echo h($pluginName); ?>
<?php $this->end(); ?>

<div class="modal-header">
	<div class="row">
		<div class="col-xs-6 text-left">
			<?php $title = $this->fetch('title'); ?>
			<?php if ($title) : ?>
				<?php echo $title; ?>
			<?php else : ?>
				<br />
			<?php endif; ?>
		</div>
		<div class="col-xs-6 text-right" style="display:table-cell;vertical-align:middle;">
			<a href="<?php echo $this->Html->url('/videos/videos/index/' . $frameId) ?>" class="btn btn-default">
				<?php echo __d("videos", "一覧へ") ?>
			</a>
		</div>
	</div>
</div>
