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

<?php echo $this->Html->script('/videos/js/video_frame_settings.js', false); ?>

<div id="nc-videos-<?php echo (int)$frameId; ?>" ng-controller="VideoFrameSettings">

	<?php echo $this->element('Videos/plugin_name',
		array(
			"pluginName" => h(__d('videos', 'Plugin name')),
		)
	); ?>

	<div class="modal-body">

		<?php echo $this->element('Videos/tabs',
			array(
				"activeTabIndex" => 1,
			)
		); ?>

		<?php echo $this->Form->create('Videos', array(
			'name' => 'form',
			'novalidate' => true,
		)); ?>

			<div class="panel panel-default">
				<div class="panel-body has-feedback">

					<div class="form-group">
						<?php echo $this->Form->input('displayOrder',
							array(
								'label' => h(__d('videos', '表示順')),
								'type' => 'select',
								'class' => 'form-control',
								'options' => array(
									'new' => h(__d('videos', '新着順')),
									'title' => h(__d('videos', 'タイトル順')),
									'play' => h(__d('videos', '再生回数順')),
									'like' => h(__d('videos', '評価順')),
								),
								'selected' => 'new',
								'autofocus' => true,
							)) ?>
					</div>

					<div class="form-group">
						<?php echo $this->Form->input('displayNumber',
							array(
								'label' => h(__d('videos', '表示件数')),
								'type' => 'select',
								'class' => 'form-control',
								'options' => array(
									1 => h(__d('videos', '1件')),
									5 => h(__d('videos', '5件')),
									10 => h(__d('videos', '10件')),
									20 => h(__d('videos', '20件')),
									50 => h(__d('videos', '50件')),
									100 => h(__d('videos', '100件')),
								),
								'selected' => 5,
							)) ?>
					</div>

				</div>
				<div class="panel-footer text-center">
					<a href="<?php echo $this->Html->url('/videos/videos/index/' . $frameId) ?>" class="btn btn-default">
						<span class="glyphicon glyphicon-remove"></span><?php echo h(__d("net_commons", "Cancel")) ?>
					</a>
					<?php echo $this->Form->button(
						__d('net_commons', 'OK'),
						array(
							'class' => 'btn btn-primary',
							'name' => 'save_' . NetCommonsBlockComponent::STATUS_PUBLISHED,
						)); ?>
				</div>
			</div>

		<?php echo $this->Form->end(); ?>
	</div>

</div>
