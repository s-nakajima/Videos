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
	ng-controller="VideoBlockSettingsEdit"
	ng-init="initialize(<?php echo h(json_encode($videoBlockSetting)) .','. h(json_encode($block)); ?>)">

	<?php echo $this->element('plugin_name', array(
		"pluginName" => __d('videos', 'Plugin name'),
	)); ?>

	<div class="modal-body">

		<?php echo $this->element('tabs', array(
			"activeTab" => 'content',
		)); ?>

		<?php echo $this->Form->create('VideoBlockSetting', array(
			'name' => 'form',
			'novalidate' => true,
		)); ?>

			<div class="panel panel-default" style="border-top: none; border-radius: 0;">
				<div class="panel-body has-feedback">

					<?php echo $this->element('VideoBlockSettings/block_form', array(
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
								'ng-model' => 'videoBlockSetting.useLike',
							)); ?>
						</div>
						<div style="padding-left: 20px;">
							<?php echo $this->Form->input('display_unlike', array(
								'label' => '<span class="glyphicon glyphicon-thumbs-down"> </span> ' . __d('videos', '低く評価も利用する'),
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
							<?php echo $this->Form->input('agree', array(
								'label' => __d('videos', '動画投稿を自動的に承認する'),
								'div' => false,
								'type' => 'checkbox',
								'ng-model' => 'videoBlockSetting.agree',
							)); ?>
						</div>
						<div>
							<?php echo $this->Form->input('mail_notice', array(
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
								<?php echo __d('videos', '動画再生プレイヤー'); ?>
							</label>
						</div>
						<div>
							<?php echo $this->Form->input('video_player', array(
								'type' => 'radio',
								'options' => array(
									VideoBlockSetting::VIDEO_PLAYER_JPLAYER => __d('blocks', 'jPlayer'),
									VideoBlockSetting::VIDEO_PLAYER_HTML5 => __d('blocks', 'HTML5'),
								),
								'div' => false,
								'legend' => false,
								'ng-model' => 'videoBlockSetting.videoPlayer',
							)); ?>
						</div>
						<div>
							<?php echo $this->Form->input('auto_play', array(
								'label' => __d('videos', '自動再生する'),
								'div' => false,
								'type' => 'checkbox',
								'ng-model' => 'videoBlockSetting.autoPlay',
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
							<?php echo $this->Form->input('display_comment', array(
								'label' => __d('videos', 'コメントを利用する'),
								'div' => false,
								'type' => 'checkbox',
								'ng-model' => 'videoBlockSetting.useComment',
							)); ?>
						</div>
						<div style="padding-left: 20px;">
							<?php echo $this->Form->input('comment_agree', array(
								'label' => __d('videos', 'コメントを自動的に承認する'),
								'div' => false,
								'type' => 'checkbox',
								//'checked' => $videoBlockSetting['commentAgree'],
								'ng-model' => 'videoBlockSetting.commentAgree',
								'ng-disabled' => '!videoBlockSetting.useComment',
							)); ?>
						</div>
						<div style="padding-left: 20px;">
							<?php echo $this->Form->input('comment_agree_mail_notice', array(
								'label' => __d('videos', 'コメントの承認完了通知をメールで通知する'),
								'div' => false,
								'type' => 'checkbox',
								//'checked' => $videoBlockSetting['commentAgreeMailNotice'],
								'ng-model' => 'videoBlockSetting.commentAgreeMailNotice',
								'ng-disabled' => "!videoBlockSetting.useComment || videoBlockSetting.commentAgree",
							)); ?>
						</div>
					</div>

					<?php /* 編集の時のみ表示する(;'∀') */ ?>
					<div class="panel panel-danger">
							<div class="panel-heading">
							<?php echo __d('videos', '危険領域'); ?>
						</div>
						<div class="panel-body text-right">
							<a href="<?php echo $this->Html->url('/videos/videoBlockSettings/delete/' . $frameId); ?>" class="btn btn-danger">
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
