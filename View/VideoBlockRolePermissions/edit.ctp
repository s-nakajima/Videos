<?php
/**
 * 権限設定 template
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<?php echo $this->Html->script('/videos/js/videos.js', false); ?>

<div class="modal-body">
	<?php echo $this->element('NetCommons.setting_tabs', $settingTabs); ?>

	<div class="tab-content">
		<?php echo $this->element('Blocks.setting_tabs', $blockSettingTabs); ?>

		<?php echo $this->element('Blocks.edit_form', array(
			'controller' => 'VideoBlockRolePermission',
			'action' => 'edit' . '/' . $frameId . '/' . $blockId,
			'callback' => 'Videos.VideoBlockRolePermissions/edit_form',
			'cancelUrl' => '/videos/video_blocks/index/' . $frameId,
			'options' => array('ng-controller' => 'VideoBlockSettingsEdit'),
		)); ?>
	</div>
</div>
