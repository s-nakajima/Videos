<?php
/**
 * 権限設定 template
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
	 ng-controller="VideoFrameSettingsAuthority"
	 ng-init="initialize(<?php echo h(json_encode($videoFrameSetting)); ?>)">

	<?php echo $this->element('Videos/plugin_name', array(
		"pluginName" => __d('videos', 'Plugin name'),
	)); ?>

	<div class="modal-body">

		<?php echo $this->element('VideoFrameSettings/tabs', array(
			"activeTab" => 'authority',
		)); ?>

		<?php echo $this->Form->create('VideoFrameSetting', array(
			'name' => 'form',
			'novalidate' => true,
		)); ?>

			<div class="panel panel-default" style="border-top: none; border-radius: 0;">
				<div class="panel-body has-feedback">

					<div class="form-group">
						<div>
							<label>
								<?php echo __d('videos', '動画投稿権限'); ?>
							</label>
						</div>
						<div>
							<?php /* authorityの値は暫定。nc3.rolesテーブルを参考にした(;'∀') */ ?>
							<?php echo $this->Form->input('authority.5', array(
								'label' => __d('videos', 'Room administrator'),
								'div' => false,
								'type' => 'checkbox',
								'checked' => true,
								'disabled' => true
							)); ?>
							<?php echo $this->Form->input('authority.4', array(
								'label' => __d('videos', 'Cheif editor'),
								'div' => false,
								'type' => 'checkbox',
								'checked' => true,
								'disabled' => true
							)); ?>
							<?php echo $this->Form->input('authority.3', array(
								'label' => __d('videos', 'Editor'),
								'div' => false,
								'type' => 'checkbox',
								'autofocus' => true,
								'ng-model' => 'video_frame_setting.authority_editor',
								'ng-change' => 'changeEditor()',
							)); ?>
							<?php echo $this->Form->input('authority.2', array(
								'label' => __d('videos', 'General'),
								'div' => false,
								'type' => 'checkbox',
								'ng-model' => 'video_frame_setting.authority_general',
								'ng-change' => 'changeGeneral()',
							)); ?>
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
