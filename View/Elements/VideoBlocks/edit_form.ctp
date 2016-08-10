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

<?php echo $this->NetCommonsForm->hidden('VideoBlockSetting.use_workflow'); ?>
<?php echo $this->NetCommonsForm->hidden('VideoBlockSetting.use_comment_approval'); ?>
<?php echo $this->NetCommonsForm->hidden('VideoBlockSetting.total_size'); ?>
<?php echo $this->NetCommonsForm->hidden('VideoFrameSetting.id'); ?>
<?php echo $this->NetCommonsForm->hidden('VideoFrameSetting.frame_key'); ?>
<?php echo $this->NetCommonsForm->hidden('VideoFrameSetting.display_order'); ?>
<?php echo $this->NetCommonsForm->hidden('VideoFrameSetting.display_number'); ?>

<?php echo $this->NetCommonsForm->input('Block.name', array(
	'type' => 'text',
	'label' => __d('videos', 'Channel name'),
	'required' => true
)); ?>

<?php echo $this->element('Blocks.public_type'); ?>

<?php echo $this->Like->setting('VideoBlockSetting.use_like', 'VideoBlockSetting.use_unlike'); ?>

<?php echo $this->NetCommonsForm->inlineCheckbox('VideoBlockSetting.use_comment', array(
	'label' => __d('content_comments', 'Use comment')
)); ?>

<?php echo $this->NetCommonsForm->inlineCheckbox('VideoBlockSetting.auto_play', array(
	'label' => __d('videos', 'Automatically play video')
)); ?>

<?php echo $this->element('Categories.edit_form', array(
	'categories' => isset($categories) ? $categories : null
));
