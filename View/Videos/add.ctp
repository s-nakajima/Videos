<?php
/**
 * 動画登録 template
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

<div id="nc-videos-<?php echo (int)$frameId; ?>" ng-controller="Videos">

	<?php /*echo $this->element('plugin_name', array(
		"pluginName" => __d('videos', 'Plugin name'),
	));*/ ?>

	<div class="modal-body">

		<?php echo $this->element('Videos/tab', array(
			"tabName" => __d('videos', 'Video add'),
		)); ?>

		<?php /* ファイル送信は、FormHelperでform作成時、'type' => 'file' 必要。記述すると enctype="multipart/form-data" が追加される */ ?>
		<?php echo $this->Form->create('Video', array(
			'name' => 'form',
			'novalidate' => true,
			'type' => 'file',
		)); ?>

			<div class="panel panel-default" style="border-top: none; border-radius: 0;">
				<div class="panel-body has-feedback">

					<?php echo $this->element('Videos/add_form'); ?>

					<hr />

					<?php echo $this->element('Comments.form'); ?>

				</div>
				<div class="panel-footer text-center">
					<?php echo $this->element('NetCommons.workflow_buttons'); ?>
				</div>
			</div>
			<?php echo $this->element('Comments.index'); ?>

		<?php echo $this->Form->end(); ?>
	</div>
</div>
