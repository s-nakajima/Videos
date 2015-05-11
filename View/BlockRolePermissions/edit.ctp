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

<div class="modal-body" ng-controller="VideoBlockSettingsEdit"
	 ng-init="initialize(<?php echo h(json_encode($videoBlockSetting)) . ',' . h(json_encode($block)); ?>)">
	<?php echo $this->element('NetCommons.setting_tabs', array(
			'tabs' => array(
				'block_index' => '/videos/blocks/index/' . $frameId,
				'frame_settings' => '/videos/video_frame_settings/edit/' . $frameId,
			),
			'active' => 'block_index'
		)); ?>

	<div class="tab-content">
		<?php echo $this->element('Blocks.setting_tabs', array(
				'tabs' => array(
					'block_settings' => '/videos/blocks/' . h($this->request->params['action']) . '/' . $frameId . '/' . $blockId,
					'role_permissions' => '/videos/block_role_permissions/' . h($this->request->params['action']) . '/' . $frameId . '/' . $blockId
				),
				'active' => 'role_permissions'
			)); ?>
		BlockRolePermissions/edit.ctp
	</div>
</div>
