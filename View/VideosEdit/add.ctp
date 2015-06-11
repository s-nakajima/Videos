<?php
/**
 * 動画登録 template
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<?php
$this->Html->script(
	array(
		'/net_commons/js/workflow.js',
		'/tags/js/tags.js',
		'/videos/js/videos.js',
	),
	array('plugin' => false, 'once' => true, 'inline' => false)
);?>

<div>
	<div class="modal-body">

		<?php /* ファイル送信は、FormHelperでform作成時、'type' => 'file' 必要。記述すると enctype="multipart/form-data" が追加される */ ?>
		<?php echo $this->Form->create('Video', array(
			'name' => 'form',
			'novalidate' => true,
			'type' => 'file',
		)); ?>

			<div class="panel panel-default">
				<div class="panel-body has-feedback">

					<?php // ffmpeg=ON
					if (Video::FFMPEG_ENABLE) {
						$videoHelpBlockMessage = sprintf(__d('videos', 'support of %s.'), Video::VIDEO_EXTENSION);
					} else {
						$videoHelpBlockMessage = sprintf(__d('videos', 'support of %s.'), 'mp4');
					} ?>
					<?php echo $this->element('VideosEdit/file', array(
						'pluginName' => 'Videos',
						'label' => __d('videos', 'Video file') . $this->element('NetCommons.required'),
						'field' => Video::VIDEO_FILE_FIELD,
						'fileAccept' => 'video/*',
						'model' => 'Video',
						'pluginKey' => $this->request->params['plugin'],
						'index' => 0,
						'helpBlockMessage' => $videoHelpBlockMessage,
						'file' => $videoFile,
						'deleteEnable' => false,
					)); ?>

					<?php /* ffmpeg=OFF */ ?>
					<?php if (!Video::FFMPEG_ENABLE) : ?>
						<?php echo $this->element('VideosEdit/file', array(
							'pluginName' => 'Videos',
							'label' => __d('videos', 'Thumbnail') . $this->element('NetCommons.required'),
							'field' => Video::THUMBNAIL_FIELD,
							'fileAccept' => 'image/*',
							'model' => 'Video',
							'pluginKey' => $this->request->params['plugin'],
							'index' => 1,
							'helpBlockMessage' => sprintf(__d('videos', 'support of %s.'), Video::THUMBNAIL_EXTENSION),
							'file' => $thumbnail,
							'deleteEnable' => false,
							'overwriteEnable' => false,
						)); ?>

						<div class="form-group">
							<?php echo $this->Form->input('video_time', array(
								'type' => 'text',
								'label' => __d('videos', 'Play time') . $this->element('NetCommons.required'),
								'error' => false,
								'class' => 'form-control',
								//'ng-model' => 'video.videoTime',
								'default' => '00:00:00',
							)); ?>

							<?php echo $this->element(
								'NetCommons.errors', [
								'errors' => $this->validationErrors,
								'model' => 'Video',
								'field' => 'video_time',
							]); ?>
						</div>
					<?php endif; ?>

					<div class="form-group">
						<?php echo $this->Form->input('title', array(
							'type' => 'text',
							'label' => __d('videos', 'Title') . $this->element('NetCommons.required'),
							'error' => false,
							'class' => 'form-control',
							//'ng-model' => 'video.title',
						)); ?>

						<?php echo $this->element(
							'NetCommons.errors', [
							'errors' => $this->validationErrors,
							'model' => 'Video',
							'field' => 'title',
						]); ?>
					</div>

					<label for="description">
						<?php echo __d('videos', 'Description'); ?>
					</label>
					<div class="nc-wysiwyg-alert">
						<?php echo $this->Form->textarea('description', array(
							'class' => 'form-control',
							'id' => 'description',
							'rows' => 5,
							//'ng-model' => 'video.description',
						)); ?>
					</div>

					<div class="form-group"></div>
					<?php $this->Form->unlockField('Tag');
					echo $this->element('Tags.tag_form', array(
						'tagData' => isset($this->request->data['Tag']) ? $this->request->data['Tag'] : array(),
						'modelName' => 'Video',
					)); ?>

					<hr />

					<?php echo $this->element('Comments.form'); ?>

				</div>
				<div class="panel-footer">
					<div class="text-center">
						<?php echo $this->element('NetCommons.workflow_buttons'); ?>
					</div>
				</div>
			</div>

		<?php echo $this->Form->end(); ?>

	</div>
</div>