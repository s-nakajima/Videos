<?php
/**
 * VideoBlocks edit form element
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<?php echo $this->NetCommonsForm->hidden('VideoBlockSetting.id'); ?>
<?php echo $this->NetCommonsForm->hidden('VideoFrameSetting.id'); ?>
<?php echo $this->NetCommonsForm->hidden('VideoFrameSetting.frame_key'); ?>
<?php echo $this->NetCommonsForm->hidden('VideoFrameSetting.display_order'); ?>
<?php echo $this->NetCommonsForm->hidden('VideoFrameSetting.display_number'); ?>
<?php echo $this->BlockForm->blockSettingHidden('BlockSetting.use_like'); ?>
<?php echo $this->BlockForm->blockSettingHidden('BlockSetting.use_unlike'); ?>
<?php echo $this->BlockForm->blockSettingHidden('BlockSetting.use_comment'); ?>
<?php echo $this->BlockForm->blockSettingHidden('BlockSetting.auto_play'); ?>
<?php echo $this->BlockForm->blockSettingHidden('BlockSetting.use_workflow', true); ?>
<?php echo $this->BlockForm->blockSettingHidden('BlockSetting.use_comment_approval', true); ?>
<?php /* 総容量はここで定義して、初期値を登録させる */ ?>
<?php echo $this->BlockForm->blockSettingHidden('BlockSetting.total_size', true); ?>

<?php //echo $this->NetCommonsForm->input('Block.name', array(
echo $this->NetCommonsForm->input('VideoBlockSetting.name', array(
	'type' => 'text',
	'label' => __d('videos', 'Channel name'),
	'required' => true
)); ?>

<?php echo $this->element('Blocks.public_type'); ?>

<?php //echo $this->Like->setting('VideoBlockSetting.use_like', 'VideoBlockSetting.use_unlike'); ?>
<?php echo $this->Like->setting('BlockSetting.use_like.value', 'BlockSetting.use_unlike.value'); ?>

<?php //echo $this->NetCommonsForm->inlineCheckbox('VideoBlockSetting.use_comment', array(
//	'label' => __d('content_comments', 'Use comment')
//)); ?>
<?php echo $this->NetCommonsForm->inlineCheckbox('BlockSetting.use_comment.value', array(
	'label' => __d('content_comments', 'Use comment')
)); ?>

<?php echo $this->NetCommonsForm->inlineCheckbox('BlockSetting.auto_play.value', array(
	'label' => __d('videos', 'Automatically play video')
)); ?>

<?php echo $this->element('Categories.edit_form', array(
	'categories' => isset($categories) ? $categories : null
));
