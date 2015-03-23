<?php
/**
 * 編集 template
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<?php echo $this->Html->script('/net_commons/base/js/workflow.js', false); ?>
<?php echo $this->Html->script('/videos/js/videos.js', false); ?>

<div id="nc-videos-<?php echo (int)$frameId; ?>" ng-controller="Videos"
	 ng-init="initialize(<?php echo h(json_encode($this->viewVars)); ?>)">

	<?php echo $this->element('Videos/plugin_name',
		array(
			"pluginName" => __d('videos', 'Plugin name'),
		)
	); ?>

	<div class="modal-body">

		<?php echo $this->element('Videos/tab',
			array(
				"tabName" => __d('videos', 'Video edit'),
			)
		); ?>

		<?php echo $this->Form->create('Videos', array(
			'name' => 'form',
			'novalidate' => true,
		)); ?>

			<div class="panel panel-default" style="border-top: none; border-radius: 0;">
				<div class="panel-body has-feedback">

					<?php echo $this->element('Videos/add_form'); ?>

					<hr />

					<?php echo $this->element('Comments.form'); ?>

				</div>
				<div class="panel-footer">
					<div class="text-center">
						<?php echo $this->element('NetCommons.workflow_buttons'); ?>
					</div>
				</div>
				<div class="panel-footer">
					<div class="text-center">
						<a href="<?php echo $this->Html->url('/videos/videos/delete/' . $frameId) ?>" class="btn btn-danger">
							<?php echo __d('videos', 'Delete') ?>
						</a>
					</div>
				</div>
			</div>
			<?php echo $this->element('Comments.index'); ?>

		<?php echo $this->Form->end(); ?>

	</div>
</div>