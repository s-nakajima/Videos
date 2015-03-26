<?php
/**
 * コンテンツ template
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<?php echo $this->Html->script('/videos/js/videos.js', false); ?>

<div id="nc-videos-<?php echo (int)$frameId; ?>"
	ng-controller="VideoFrameSettingsContent"
	ng-init="initialize(<?php echo h(json_encode($videoFrameSetting)) .','. h(json_encode($block)); ?>)">

	<?php echo $this->element('Videos/plugin_name', array(
		"pluginName" => __d('videos', 'Plugin name'),
	)); ?>

	<div class="modal-body">

		<?php echo $this->element('VideoFrameSettings/tabs', array(
			"activeTab" => 'content',
		)); ?>

		<?php echo $this->Form->create('VideoFrameSetting', array(
			'name' => 'form',
			'novalidate' => true,
		)); ?>

			<div class="panel panel-default" style="border-top: none; border-radius: 0;">
				<div class="panel-body has-feedback">

					<?php echo $this->element('VideoFrameSettings/block_form', array(
						"nameLabel" => __d('videos', 'チャンネル名') . $this->element('NetCommons.required'),
					)); ?>

					<div class="form-group">
						<div>
							<label>
								<?php echo __d('videos', '評価機能'); ?>
							</label>
						</div>
						<div>
							<?php echo $this->Form->input('display_like', array(
								'label' => '<span class="glyphicon glyphicon-thumbs-up"> </span> ' . __d('videos', '高く評価を利用する'),
								'div' => false,
								'type' => 'checkbox',
								'ng-model' => 'video_frame_setting.display_like',
							)); ?>
						</div>
						<div style="padding-left: 20px;">
							<?php echo $this->Form->input('display_unlike', array(
								'label' => '<span class="glyphicon glyphicon-thumbs-down"> </span> ' . __d('videos', '低く評価も利用する'),
								'div' => false,
								'type' => 'checkbox',
								'ng-model' => 'video_frame_setting.display_unlike',
								'ng-disabled' => '!video_frame_setting.display_like',
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
							<?php echo $this->Form->input('agree', array(
								'label' => __d('videos', '動画投稿を自動的に承認する'),
								'div' => false,
								'type' => 'checkbox',
								'ng-model' => 'video_frame_setting.agree',
							)); ?>
						</div>
						<div>
							<?php echo $this->Form->input('mail_notice', array(
								'label' => __d('videos', '動画投稿をメールで通知する'),
								'div' => false,
								'type' => 'checkbox',
								'ng-model' => 'video_frame_setting.mail_notice',
							)); ?>
						</div>
						<div>
							<?php echo $this->Form->input('auto_video_convert', array(
								'label' => __d('videos', '動画を自動変換する'),
								'div' => false,
								'type' => 'checkbox',
								'ng-model' => 'video_frame_setting.auto_video_convert',
							)); ?>
						</div>
					</div>
					<div class="form-group">
						<div>
							<label>
								<?php echo __d('videos', '動画再生プレイヤー'); ?>
							</label>
						</div>
						<div>
							<?php echo $this->Form->input('video_player', array(
								'type' => 'radio',
								'options' => array(
									VideoFrameSetting::VIDEO_PLAYER_JPLAYER => __d('blocks', 'jPlayer'),
									VideoFrameSetting::VIDEO_PLAYER_HTML5 => __d('blocks', 'HTML5'),
								),
								'div' => false,
								'legend' => false,
								'ng-model' => 'video_frame_setting.video_player',
							)); ?>
						</div>
						<div>
							<?php echo $this->Form->input('auto_play', array(
								'label' => __d('videos', '自動再生する'),
								'div' => false,
								'type' => 'checkbox',
								'ng-model' => 'video_frame_setting.auto_play',
							)); ?>
						</div>
					</div>
					<div class="form-group">
						<?php echo $this->Form->input('buffer_time', array(
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
							'ng-model' => 'video_frame_setting.buffer_time',
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
							<?php echo $this->Form->input('display_comment', array(
								'label' => __d('videos', 'コメントを利用する'),
								'div' => false,
								'type' => 'checkbox',
								'ng-model' => 'video_frame_setting.display_comment',
							)); ?>
						</div>
						<div style="padding-left: 20px;">
							<?php echo $this->Form->input('comment_agree', array(
								'label' => __d('videos', 'コメントを自動的に承認する'),
								'div' => false,
								'type' => 'checkbox',
								'checked' => $videoFrameSetting['comment_agree'],
								'ng-model' => 'video_frame_setting.comment_agree',
								'ng-disabled' => '!video_frame_setting.display_comment',
							)); ?>
						</div>
						<div style="padding-left: 20px;">
							<?php echo $this->Form->input('comment_agree_mail_notice', array(
								'label' => __d('videos', 'コメントの承認完了通知をメールで通知する'),
								'div' => false,
								'type' => 'checkbox',
								'checked' => $videoFrameSetting['comment_agree_mail_notice'],
								'ng-model' => 'video_frame_setting.comment_agree_mail_notice',
								'ng-disabled' => "!video_frame_setting.display_comment || video_frame_setting.comment_agree",
							)); ?>
						</div>
					</div>

					<?php /* 編集の時のみ表示する(;'∀') */ ?>
					<div class="panel panel-danger">
							<div class="panel-heading">
							<?php echo __d('videos', '危険領域'); ?>
						</div>
						<div class="panel-body text-right">
							<a href="<?php echo $this->Html->url('/videos/videoFrameSettings/delete/' . $frameId); ?>" class="btn btn-danger">
								<?php echo __d('videos', 'Delete'); ?>
							</a>
						</div>
					</div>

				</div>
				<div class="panel-footer text-center">
					<a href="<?php echo $this->Html->url('/videos/videos/index/' . $frameId); ?>" class="btn btn-default">
						<span class="glyphicon glyphicon-remove"></span><?php echo __d("net_commons", "Cancel"); ?>
					</a>
					<?php echo $this->Form->button(__d('net_commons', 'OK'), array(
						'class' => 'btn btn-primary',
						'name' => 'save_' . NetCommonsBlockComponent::STATUS_PUBLISHED,
					)); ?>
				</div>
			</div>

		<?php echo $this->Form->end(); ?>
	</div>

</div>
