<?php
/**
 * VideoBlocks edit template
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<article class="block-setting-body">
	<?php echo $this->BlockTabs->main(BlockTabsHelper::MAIN_TAB_BLOCK_INDEX); ?>

	<div class="tab-content">
		<?php echo $this->BlockTabs->block(BlockTabsHelper::BLOCK_TAB_SETTING); ?>

		<?php echo $this->BlockForm->displayEditForm(array(
			'model' => 'VideoBlockSetting',
			'callback' => 'Videos.VideoBlocks/edit_form',
			'cancelUrl' => NetCommonsUrl::backToIndexUrl('default_setting_action'),
			'displayModified' => true,
		)); ?>

		<?php echo $this->BlockForm->displayDeleteForm(array(
			'model' => 'VideoBlock',
			'callback' => 'Videos.VideoBlocks/delete_form'
		)); ?>
	</div>
</article>
