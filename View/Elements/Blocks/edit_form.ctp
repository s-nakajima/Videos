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

/*echo $this->Form->hidden('id', array('value' => isset($bbsSetting['id']) ? (int)$bbsSetting['id'] : null));
echo $this->Form->hidden('Frame.id', array('value' => $frameId));
echo $this->Form->hidden('Block.id', array('value' => $block['id']));
echo $this->Form->hidden('Block.key', array('value' => $block['key']));
echo $this->Form->hidden('Block.language_id', array('value' => $languageId));
echo $this->Form->hidden('Block.room_id', array('value' => $roomId));
echo $this->Form->hidden('Block.plugin_key', array('value' => 'videos'));
echo $this->Form->hidden('Bbs.id', array('value' => isset($bbs['id']) ? (int)$bbs['id'] : null));
echo $this->Form->hidden('Bbs.key', array('value' => isset($bbs['key']) ? $bbs['key'] : null));
echo $this->Form->hidden('BbsSetting.id', array('value' => isset($bbsSetting['id']) ? (int)$bbsSetting['id'] : null));*/

echo $this->Form->hidden('Block.id', array('value' => isset($block['id']) ? $block['id'] : null));
?>

<div class="row form-group">
	<div class="col-xs-12">
		<?php echo $this->Form->input('Block.name', array(
			'type' => 'text',
			'label' => __d('videos', 'チャンネル名') . $this->element('NetCommons.required'),
			'error' => false,
			'class' => 'form-control',
			'value' => (isset($block['name']) ? $block['name'] : '')
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
		<label>
			<?php echo __d('videos', '評価機能'); ?>
		</label>
	</div>
	<div>
		<?php echo $this->Form->input('VideoBlockSetting.use_like', array(
			'label' => '<span class="glyphicon glyphicon-thumbs-up"> </span> ' . __d('videos', 'ボタンを利用する'),
			'div' => false,
			'type' => 'checkbox',
			'ng-model' => 'videoBlockSetting.useLike',
		)); ?>
	</div>
	<div style="padding-left: 20px;">
		<?php echo $this->Form->input('VideoBlockSetting.use_unlike', array(
			'label' => '<span class="glyphicon glyphicon-thumbs-down"> </span> ' . __d('videos', 'ボタンも利用する'),
			'div' => false,
			'type' => 'checkbox',
			'ng-model' => 'videoBlockSetting.useUnlike',
			'ng-disabled' => '!videoBlockSetting.useLike',
		)); ?>
	</div>
</div>

<div class="form-group">
	<div>
		<label>
			<?php echo __d('videos', '動画配信設定'); ?>
		</label>
	</div>
	<div>
		<?php echo $this->Form->input('VideoBlockSetting.agree', array(
			'label' => __d('videos', '動画投稿を自動的に承認する'),
			'div' => false,
			'type' => 'checkbox',
			'ng-model' => 'videoBlockSetting.agree',
		)); ?>
	</div>
	<div>
		<?php echo $this->Form->input('VideoBlockSetting.mail_notice', array(
			'label' => __d('videos', '動画投稿をメールで通知する'),
			'div' => false,
			'type' => 'checkbox',
			'ng-model' => 'videoBlockSetting.mailNotice',
		)); ?>
	</div>
	<div>
		<?php echo $this->Form->input('auto_video_convert', array(
			'label' => __d('videos', '動画を自動変換する'),
			'div' => false,
			'type' => 'checkbox',
			'ng-model' => 'videoBlockSetting.autoVideoConvert',
		)); ?>
	</div>
</div>
<div class="form-group">
	<div>
		<label>
			<?php echo __d('videos', '動画再生'); ?>
		</label>
	</div>
	<div>
		<?php echo $this->Form->input('VideoBlockSetting.auto_play', array(
			'label' => __d('videos', '自動再生する'),
			'div' => false,
			'type' => 'checkbox',
			'ng-model' => 'videoBlockSetting.autoPlay',
		)); ?>
	</div>
</div>
<div class="form-group">
	<?php echo $this->Form->input('VideoBlockSetting.buffer_time', array(
		'label' => __d('videos', 'バッファ時間'),
		'type' => 'select',
		'class' => 'form-control',
		'options' => array(
			2 => sprintf(__d('videos', '%s秒'), '2'),
			4 => sprintf(__d('videos', '%s秒'), '4'),
			6 => sprintf(__d('videos', '%s秒'), '6'),
			10 => sprintf(__d('videos', '%s秒'), '10'),
			20 => sprintf(__d('videos', '%s秒'), '20'),
			45 => sprintf(__d('videos', '%s秒'), '45'),
			60 => sprintf(__d('videos', '%s秒'), '60'),
		),
		'ng-model' => 'videoBlockSetting.bufferTime',
	)); ?>
	<p class="help-block">
		<?php echo __d('videos', '動画の再生が遅いときは、バッファ時間を長めに設定してください。'); ?>
	</p>
</div>
<div class="form-group">
	<div>
		<label>
			<?php echo __d('videos', 'コメント設定'); ?>
		</label>
	</div>
	<div>
		<?php echo $this->Form->input('VideoBlockSetting.use_comment', array(
			'label' => __d('videos', 'コメントを利用する'),
			'div' => false,
			'type' => 'checkbox',
			'ng-model' => 'videoBlockSetting.useComment',
		)); ?>
	</div>
	<div style="padding-left: 20px;">
		<?php echo $this->Form->input('VideoBlockSetting.comment_agree', array(
			'label' => __d('videos', 'コメントを自動的に承認する'),
			'div' => false,
			'type' => 'checkbox',
			//'checked' => $videoBlockSetting['commentAgree'],
			'ng-model' => 'videoBlockSetting.commentAgree',
			'ng-disabled' => '!videoBlockSetting.useComment',
		)); ?>
	</div>
	<div style="padding-left: 20px;">
		<?php echo $this->Form->input('VideoBlockSetting.comment_agree_mail_notice', array(
			'label' => __d('videos', 'コメントの承認完了通知をメールで通知する'),
			'div' => false,
			'type' => 'checkbox',
			//'checked' => $videoBlockSetting['commentAgreeMailNotice'],
			'ng-model' => 'videoBlockSetting.commentAgreeMailNotice',
			'ng-disabled' => "!videoBlockSetting.useComment || videoBlockSetting.commentAgree",
		)); ?>
	</div>
</div>
