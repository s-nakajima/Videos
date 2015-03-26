<?php
/**
 * 表示方法変更 template
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
	 ng-controller="VideoFrameSettings"
	 ng-init="initialize(<?php echo h(json_encode($videoFrameSetting)); ?>)">

	<?php echo $this->element('Videos/plugin_name', array(
		"pluginName" => __d('videos', 'Plugin name'),
	)); ?>

	<div class="modal-body">

		<?php echo $this->element('VideoFrameSettings/tabs', array(
			"activeTab" => 'display',
		)); ?>

		<?php echo $this->Form->create('VideoFrameSetting', array(
			'name' => 'form',
			'novalidate' => true,
		)); ?>
			<div class="panel panel-default" style="border-top: none; border-radius: 0;">
				<div class="panel-body has-feedback">

					<div class="form-group">
						<?php echo $this->Form->input('display_order', array(
							'label' => __d('videos', '表示順'),
							'type' => 'select',
							'class' => 'form-control',
							'options' => array(
								VideoFrameSetting::DISPLAY_ORDER_NEW => __d('videos', '新着順'),
								VideoFrameSetting::DISPLAY_ORDER_TITLE => __d('videos', 'タイトル順'),
								VideoFrameSetting::DISPLAY_ORDER_PLAY => __d('videos', '再生回数順'),
								VideoFrameSetting::DISPLAY_ORDER_LIKE => __d('videos', '評価順'),
							),
							'selected' => $videoFrameSetting['displayOrder'],
							'autofocus' => true,
						)); ?>
					</div>
					<div class="form-group">
						<?php echo $this->Form->input('display_number', array(
							'label' => __d('videos', '表示件数'),
							'type' => 'select',
							'class' => 'form-control',
							'options' => array(
								1 => sprintf(__d('videos', '%s'), '1'),
								5 => sprintf(__d('videos', '%s'), '5'),
								10 => sprintf(__d('videos', '%s'), '10'),
								20 => sprintf(__d('videos', '%s'), '20'),
								50 => sprintf(__d('videos', '%s'), '50'),
								100 => sprintf(__d('videos', '%s'), '100'),
							),
							'selected' => $videoFrameSetting['displayNumber'],
						)); ?>
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
