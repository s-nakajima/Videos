<?php
/**
 * add template
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

<div id="nc-videos-<?php echo (int)$frameId; ?>" ng-controller="Videos">

	<?php echo $this->element('Videos/plugin_name',
		array(
			"pluginName" => h(__d('videos', 'Plugin name')),
		)
	); ?>

	<div class="modal-body">

		<?php echo $this->element('Videos/tab',
			array(
				"tabName" => h(__d('videos', 'Video add')),
			)
		); ?>

		<?php echo $this->Form->create('Videos', array(
			'name' => 'form',
			'novalidate' => true,
		)); ?>

		<div class="panel panel-default">
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
