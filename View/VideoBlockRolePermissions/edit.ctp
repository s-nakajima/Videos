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

<?php //echo $this->Html->script('/videos/js/videos.js', false); ?>

<div class="block-setting-body">
	<?php echo $this->BlockTabs->main(BlockTabsComponent::MAIN_TAB_BLOCK_INDEX); ?>

	<div class="tab-content">
		<?php echo $this->BlockTabs->block(BlockTabsComponent::BLOCK_TAB_PERMISSION); ?>

		<?php echo $this->element('Blocks.edit_form', array(
			'model' => 'VideoBlockRolePermission',
			'callback' => 'Videos.VideoBlockRolePermissions/edit_form',
			'cancelUrl' => NetCommonsUrl::backToIndexUrl('default_setting_action'),
		)); ?>
	</div>
</div>
