<?php
/**
 * コンテンツ編集 エレメント
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

echo $this->Form->hidden('Block.id', array('value' => isset($block['id']) ? $block['id'] : null));
?>

<div class="row form-group">
	<div class="col-xs-12">
		<?php echo $this->Form->input('Block.name', array(
			'type' => 'text',
			'label' => __d('videos', 'Channel name') . $this->element('NetCommons.required'),
			'error' => false,
			'class' => 'form-control',
			'default' => (isset($block['name']) ? $block['name'] : '')
		)); ?>
	</div>

	<div class="col-xs-12">
		<?php echo $this->element('NetCommons.errors', array(
			'errors' => $this->validationErrors,
			'model' => 'Block',
			'field' => 'name',
		)); ?>
	</div>
</div>

<?php echo $this->element('Blocks.public_type'); ?>

<div class="form-group">
	<div>
		<?php echo $this->Form->input('VideoBlockSetting.use_like', array(
			'label' => '<span class="glyphicon glyphicon-thumbs-up"> </span> ' . __d('likes', 'Use like button'),
			'div' => false,
			'type' => 'checkbox',
			'ng-model' => 'videoBlockSetting.useLike',
		)); ?>
	</div>
	<div style="padding-left: 20px;">
		<?php echo $this->Form->input('VideoBlockSetting.use_unlike', array(
			'label' => '<span class="glyphicon glyphicon-thumbs-down"> </span> ' . __d('likes', 'Use unlike button'),
			'div' => false,
			'type' => 'checkbox',
			'ng-model' => 'videoBlockSetting.useUnlike',
			'ng-disabled' => '!videoBlockSetting.useLike',
		)); ?>
	</div>
</div>

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

<div class="form-group">
	<div>
		<?php echo $this->Form->input('VideoBlockSetting.use_comment', array(
			'label' => __d('content_comments', 'Use comment'),
			'div' => false,
			'type' => 'checkbox',
			//'default' => $videoBlockSetting['useComment'],
			'ng-model' => 'videoBlockSetting.useComment',
		)); ?>
	</div>
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
</div>

<div class="form-group">
	<div>
		<?php echo $this->Form->input('VideoBlockSetting.auto_play', array(
			'label' => __d('videos', 'Automatically play video'),
			'div' => false,
			'type' => 'checkbox',
			'ng-model' => 'videoBlockSetting.autoPlay',
		)); ?>
	</div>
</div>
