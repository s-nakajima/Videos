<?php
/**
 * 表示方法変更 template
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Ryo Ozawa <ozawa.ryo@withone.co.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<article class="block-setting-body">
	<?php echo $this->BlockTabs->main(BlockTabsComponent::MAIN_TAB_FRAME_SETTING); ?>

	<div class="tab-content">
		<?php echo $this->element('Blocks.edit_form', array(
			'model' => 'VideoFrameSetting',
			'callback' => 'Videos.VideoFrameSettings/edit_form',
			'cancelUrl' => NetCommonsUrl::backToPageUrl(),
		)); ?>
	</div>
</article>
