<?php
/**
 * 動画編集 template
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<?php echo $this->Html->script('/net_commons/js/workflow.js', false); ?>
<?php echo $this->Html->script('/videos/js/videos.js', false); ?>
<?php echo $this->Html->script('/tags/js/tags.js', false); ?>

<div id="nc-videos2-<?php echo (int)$frameId; ?>"
	 ng-controller="Videos"
	 ng-init="initialize(<?php echo h(json_encode($video)); ?>)">

	<div class="modal-body">

		<h1><?php echo __d('videos', 'Video edit'); ?></h1>

		<?php /* ファイル送信は、FormHelperでform作成時、'type' => 'file' 必要。記述すると enctype="multipart/form-data" が追加される */ ?>
		<?php echo $this->Form->create('Video', array(
			'name' => 'form',
			'novalidate' => true,
			'type' => 'file',
		)); ?>

			<div class="panel panel-default">
				<div class="panel-body has-feedback">

					<?php echo $this->element('VideosEdit/edit_form'); ?>

					<hr />

					<?php echo $this->element('Comments.form'); ?>

				</div>
				<div class="panel-footer">
					<div class="text-center">
						<?php echo $this->element('NetCommons.workflow_buttons'); ?>
					</div>
				</div>
				<div class="panel-footer">
					<div class="text-right">
						<a href="<?php echo $this->Html->url('/videos/videos/delete/' . $frameId); ?>" class="btn btn-danger">
							<span class="glyphicon glyphicon-trash"> </span>
						</a>
					</div>
				</div>
			</div>
			<?php echo $this->element('Comments.index'); ?>

		<?php echo $this->Form->end(); ?>

	</div>
</div>