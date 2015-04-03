<?php
/**
 * 動画 template
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<?php echo $this->Html->script('/videos/js/videos.js', false); ?>

<!--
<div id="nc-videos-<?php echo (int)$frameId; ?>"
	 ng-controller="VideoFrameSettings"
	 ng-init="initialize(<?php //echo h(json_encode($videoFrameSetting)); ?>)">
-->
<div id="nc-videos-<?php echo (int)$frameId; ?>" ng-controller="VideoFrameSettings">

	<?php echo $this->element('plugin_name', array(
		"pluginName" => __d('videos', 'Plugin name'),
	)); ?>

	<div class="modal-body">

		<?php echo $this->element('tabs', array(
			"activeTab" => 'video',
		)); ?>

		<?php echo $this->Form->create('VideoBlockSetting', array(
			'name' => 'form',
			'novalidate' => true,
		)); ?>

			<div class="panel panel-default" style="border-top: none; border-radius: 0;">
				<div class="panel-body has-feedback">

					<?php //echo $this->element('VideoFrameSettings/edit_form'); ?>
					video.ctp

				</div>
			</div>

		<?php echo $this->Form->end(); ?>
	</div>

</div>
