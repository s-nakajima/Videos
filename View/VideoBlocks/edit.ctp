<?php
/**
 * コンテンツ template
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<?php echo $this->Html->script('/videos/js/videos.js', array('plugin' => false, 'once' => true, 'inline' => false)); ?>

<div class="modal-body" ng-controller="VideoBlockSettingsEdit"
	 ng-init="initialize(<?php echo h(json_encode($videoBlockSetting)) . ',' . h(json_encode($block)); ?>)">

	<?php echo $this->element('NetCommons.setting_tabs', $settingTabs); ?>

	<div class="tab-content">
		<?php echo $this->element('Blocks.setting_tabs', $blockSettingTabs); ?>

		<?php echo $this->element('Blocks.edit_form', array(
			'controller' => 'VideoBlockSettings',
			'action' => h($this->request->params['action']) . '/' . $frameId . '/' . $blockId,
			'callback' => 'Videos.VideoBlockSettings/edit_form',
			'cancelUrl' => '/videos/video_blocks/index/' . $frameId
		)); ?>

		<?php if ($this->request->params['action'] === 'edit') : ?>
			<?php echo $this->element('Blocks.delete_form', array(
				'controller' => 'VideoBlockSettings',
				'action' => 'delete/' . $frameId . '/' . $blockId,
				'callback' => 'Videos.VideoBlockSettings/delete_form'
			)); ?>
		<?php endif; ?>
	</div>
</div>
