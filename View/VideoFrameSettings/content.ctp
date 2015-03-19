<?php
/**
 * edit template
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<?php echo $this->Html->script('/videos/js/video_frame_settings.js', false); ?>

<div id="nc-videos-<?php echo (int)$frameId; ?>" ng-controller="VideoFrameSettings">

	<?php echo $this->element('Videos/plugin_name',
		array(
			"pluginName" => h(__d('videos', 'Plugin name')),
		)
	); ?>

	<div class="modal-body">

		<?php echo $this->element('Videos/tabs',
			array(
				"activeTabIndex" => 2,
			)
		); ?>

		<?php echo $this->Form->create('Videos', array(
			'name' => 'form',
			'novalidate' => true,
			'type' => 'file'
		)); ?>

			<div class="panel panel-default">
				<div class="panel-body has-feedback">

					<?php //echo $this->element('VideoFrameSettings/edit_form'); ?>

				</div>
				<div class="panel-footer text-center">
					<a href="<?php echo $this->Html->url('/videos/videos/index/' . $frameId) ?>" class="btn btn-default">
						<span class="glyphicon glyphicon-remove"></span><?php echo h(__d("net_commons", "Cancel")) ?>
					</a>
					<?php echo $this->Form->button(
						__d('net_commons', 'OK'),
						array(
							'class' => 'btn btn-primary',
							'name' => 'save_' . NetCommonsBlockComponent::STATUS_PUBLISHED,
						)); ?>
				</div>
			</div>

		<?php echo $this->Form->end(); ?>
	</div>

</div>
