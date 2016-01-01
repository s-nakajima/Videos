<?php
/**
 * VideoBlockRolePermissions edit template
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<?php echo $this->NetCommonsForm->hidden('Block.id'); ?>
<?php echo $this->NetCommonsForm->hidden('VideoBlockSetting.id'); ?>
<?php echo $this->NetCommonsForm->hidden('VideoBlockSetting.block_key'); ?>

<?php echo $this->element('Blocks.block_creatable_setting', array(
	'settingPermissions' => array(
		'content_creatable' => __d('blocks', 'Content creatable roles'),
	),
)); ?>

<?php echo $this->element('Blocks.block_approval_setting', array(
	'model' => 'VideoBlockSetting',
	'useWorkflow' => 'agree',
	'options' => array(
		Block::NEED_APPROVAL => __d('blocks', 'Need approval in both %s and comments ', __d('videos', 'video')),
		Block::NEED_COMMENT_APPROVAL => __d('blocks', 'Need only comments approval'),
		Block::NOT_NEED_APPROVAL => __d('blocks', 'Not need approval'),
	),
));
