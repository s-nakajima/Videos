<?php
/**
 * 動画編集 template
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

<div ng-controller="Videos"
	 ng-init="initialize(<?php echo h(json_encode($video)); ?>)">

	<div class="modal-body">

		<div class="panel panel-default">
			<?php /* ファイル送信は、FormHelperでform作成時、'type' => 'file' 必要。記述すると enctype="multipart/form-data" が追加される */ ?>
			<?php echo $this->Form->create('Video', array(
				'name' => 'form',
				'novalidate' => true,
				'type' => 'file',
			)); ?>
				<div class="panel-body has-feedback">

					<?php // ffmpeg=ON
					if (Video::FFMPEG_ENABLE) {
						$thumbnailHelpBlockMessage = __d('videos', 'Please register if you want to change.') . sprintf(__d('videos', 'support of %s.'), Video::THUMBNAIL_EXTENSION);
					} else {
						$thumbnailHelpBlockMessage = sprintf(__d('videos', 'support of %s.'), Video::THUMBNAIL_EXTENSION);
					} ?>
					<?php echo $this->element('VideosEdit/file', array(
						'pluginName' => 'Videos',
						'label' => __d('videos', 'Thumbnail'),
						'field' => Video::THUMBNAIL_FIELD,
						'fileAccept' => 'image/*',
						'model' => 'Video',
						'pluginKey' => $this->request->params['plugin'],
						'index' => 1,
						'helpBlockMessage' => $thumbnailHelpBlockMessage,
						'file' => $thumbnail,
						'deleteEnable' => false,
						'overwriteEnable' => false,
					)); ?>

					<?php /* ffmpeg=OFF */ ?>
					<?php if (!Video::FFMPEG_ENABLE) : ?>
						<div class="form-group">
							<?php echo $this->Form->input('video_time', array(
								'type' => 'text',
								'label' => __d('videos', 'Play time') . $this->element('NetCommons.required'),
								'error' => false,
								'class' => 'form-control',
								//'ng-model' => 'video.videoTime',
								'default' => $video['videoTimeEdit'],
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
							'default' => $video['title'],
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
							'default' => $video['description'],
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

			<?php echo $this->Form->end(); ?>

			<div class="panel-footer" style="border-top-style: none;">
				<div class="text-right">
					<?php /* 削除 */ ?>
					<?php echo $this->Form->create('Video', array(
						'type' => 'delete',
						'style' => 'display: inline;',
						'url' => '/videos/videos_edit/delete/' . $frameId,
					)); ?>

						<?php echo $this->Form->hidden('Video.id', array(
							'value' => $video['id'],
						)); ?>
						<?php echo $this->Form->hidden('Video.key', array(
							'value' => $video['key'],
						)); ?>
						<?php echo $this->Form->button("<span class='glyphicon glyphicon-trash'></span>", array(
							'name' => 'delete',
							'class' => 'btn btn-danger',
							'onclick' => 'return confirm(\'' . sprintf(__d('net_commons', 'Deleting the %s. Are you sure to proceed?'), __d('videos', 'video')) . '\')'
						)); ?>

					<?php echo $this->Form->end(); ?>
				</div>
			</div>

		</div>
		<?php echo $this->element('Comments.index'); ?>
	</div>
</div>