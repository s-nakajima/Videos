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

		<h1><?php echo __d('videos', 'Video add'); ?></h1>

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
						$videoHelpBlockMessage = __d('videos', 'support of mpeg, avi, mov, wmv, flv, mpg, mp4.');
					} else {
						$videoHelpBlockMessage = __d('videos', 'support of mp4.');
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
							'helpBlockMessage' => null,
							'file' => $thumbnail,
							'deleteEnable' => false,
							'overwriteEnable' => false,
						)); ?>

						<div class="form-group">
							<?php echo $this->Form->input('video_time', array(
								'type' => 'text',
								'label' => __d('videos', 'Video time') . $this->element('NetCommons.required'),
								'class' => 'form-control',
								//'ng-model' => 'video.videoTime',
								'default' => $video['videoTime'],
							)); ?>
						</div>
					<?php endif; ?>

					<div class="form-group">
						<?php echo $this->Form->input('title', array(
							'type' => 'text',
							'label' => __d('videos', 'Title') . $this->element('NetCommons.required'),
							'class' => 'form-control',
							//'required' => 'required',
							'ng-model' => 'video.title',
							//'autofocus' => true,
						)); ?>
					</div>

					<label for="description">
						<?php echo __d('videos', 'Description'); ?>
					</label>
					<div class="nc-wysiwyg-alert">
						<?php echo $this->Form->textarea('description', array(
							'class' => 'form-control',
							'id' => 'description',
							'rows' => 5,
							'ng-model' => 'video.description',
						)); ?>
					</div>

					<div class="form-group"></div>
					<?php $this->Form->unlockField('Tag');
					echo $this->element('Tags.tag_form', array(
						'tagData' => $this->request->data['Tag'],
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