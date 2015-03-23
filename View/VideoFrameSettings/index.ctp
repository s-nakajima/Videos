<?php
/**
 * 一覧表示 template
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
			"pluginName" => __d('videos', 'Plugin name'),
		)
	); ?>

	<div class="modal-body">

		<?php echo $this->element('Videos/tabs',
			array(
				"activeTabIndex" => 0,
			)
		); ?>

		<?php echo $this->Form->create('Videos', array(
			'name' => 'form',
			'novalidate' => true,
		)); ?>

			<div class="panel panel-default" style="border-top: none; border-radius: 0;">
				<div class="panel-body has-feedback">

					<?php //echo $this->element('VideoFrameSettings/edit_form'); ?>
					index.ctp

				</div>
			</div>

		<?php echo $this->Form->end(); ?>
	</div>

</div>
