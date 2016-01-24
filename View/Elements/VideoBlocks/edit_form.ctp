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

<?php echo $this->element('Blocks.form_hidden'); ?>

<?php echo $this->NetCommonsForm->hidden('VideoBlockSetting.id'); ?>
<?php /* 下記をセットする意味は何だろう？ */ ?>
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

<?php /* 暫定対応(;'∀')
<div class="form-group">
	<div>
		 echo $this->Form->input('VideoBlockSetting.mail_notice', array(
			'label' => __d('videos', '動画投稿をメールで通知する'),
			'div' => false,
			'type' => 'checkbox',
			'ng-model' => 'videoBlockSetting.mailNotice',
		));
	</div>
</div> */ ?>

<?php echo $this->NetCommonsForm->inlineCheckbox('VideoBlockSetting.use_comment', array(
	'label' => __d('content_comments', 'Use comment')
)); ?>

<!--<div class="row form-group">-->
	<?php /* 暫定対応(;'∀')
	<div style="padding-left: 20px;">
		echo $this->Form->input('VideoBlockSetting.comment_agree_mail_notice', array(
			'label' => __d('videos', 'コメントの承認完了通知をメールで通知する'),
			'div' => false,
			'type' => 'checkbox',
			//'default' => $videoBlockSetting['commentAgreeMailNotice'],
			'ng-model' => 'videoBlockSetting.commentAgreeMailNotice',
			'ng-disabled' => "!videoBlockSetting.useComment || videoBlockSetting.commentAgree",
		));
	</div> */ ?>
<!--</div>-->

<?php echo $this->NetCommonsForm->inlineCheckbox('VideoBlockSetting.auto_play', array(
	'label' => __d('videos', 'Automatically play video')
));