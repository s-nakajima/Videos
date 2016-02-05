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
$this->NetCommonsHtml->script(array(
	'/tags/js/tags.js',
	'/videos/js/videos.js',
));
?>

<div ng-controller="Videos"
	 ng-init="initialize(<?php echo h(json_encode($video)); ?>)">

	<article class="modal-body">

		<div class="panel panel-default">
			<?php /* ファイル送信は、FormHelperでform作成時、'type' => 'file' 必要。記述すると enctype="multipart/form-data" が追加される */ ?>
			<?php echo $this->Form->create('Video', array(
				'name' => 'form',
				'novalidate' => true,
				'type' => 'file',
			)); ?>
				<div class="panel-body has-feedback">

					<?php // ffmpeg=ON
					if (Video::isFfmpegEnable()) {
						$thumbnailHelpBlockMessage = __d('videos', 'Please register if you want to change.') . sprintf(__d('videos', 'support of %s.'), Video::THUMBNAIL_EXTENSION);
					} else {
						$thumbnailHelpBlockMessage = sprintf(__d('videos', 'support of %s.'), Video::THUMBNAIL_EXTENSION);
					} ?>
<!--					--><?php //echo $this->element('VideosEdit/file', array(
//						'pluginName' => 'Videos',
//						'label' => __d('videos', 'Thumbnail'),
//						'field' => Video::THUMBNAIL_FIELD,
//						'fileAccept' => 'image/*',
//						'model' => 'Video',
//						'pluginKey' => $this->request->params['plugin'],
//						'index' => 1,
//						'helpBlockMessage' => $thumbnailHelpBlockMessage,
//						'file' => $thumbnail,
//						'deleteEnable' => false,
//						'overwriteEnable' => false,
//					)); ?>
					<?php echo $this->NetCommonsForm->uploadFile(Video::THUMBNAIL_FIELD, array(
						'label' => __d('videos', 'Thumbnail'),
					)); ?>
					<?php /* 暫定対応 */ ?>
					<div><p class="help-block"><?php echo h($thumbnailHelpBlockMessage); ?></p></div>

					<?php /* ffmpeg=OFF */ ?>
					<?php /* if (!Video::FFMPEG_ENABLE) : ?>
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
					<?php endif; */ ?>
					<?php echo $this->NetCommonsForm->hidden('Video.block_id'); ?>
					<?php echo $this->NetCommonsForm->hidden('Video.language_id'); ?>
					<?php echo $this->NetCommonsForm->hidden('Video.id'); ?>
					<?php echo $this->NetCommonsForm->hidden('Video.key'); ?>
					<?php echo $this->NetCommonsForm->hidden('UploadFile.' . Video::VIDEO_FILE_FIELD . '.id'); ?>
					<?php echo $this->NetCommonsForm->hidden('UploadFile.' . Video::VIDEO_FILE_FIELD . '.field_name'); ?>
					<?php echo $this->NetCommonsForm->hidden('UploadFile.' . Video::THUMBNAIL_FIELD . '.id'); ?>
					<?php echo $this->NetCommonsForm->hidden('UploadFile.' . Video::THUMBNAIL_FIELD . '.field_name'); ?>

					<?php echo $this->NetCommonsForm->input('Video.title', array(
						'type' => 'text',
						'label' => __d('videos', 'Title'),
						'required' => true
					)); ?>

<!--					<div class="form-group">-->
<!--<!--						--><?php ////echo $this->Form->input('title', array(
////							'type' => 'text',
////							'label' => __d('videos', 'Title') . $this->element('NetCommons.required'),
////							'error' => false,
////							'class' => 'form-control',
////							//'ng-model' => 'video.title',
////							'default' => $video['Video']['title'],
////						)); ?>
<!--					</div>-->

					<?php echo $this->NetCommonsForm->input('Video.description', array(
						'type' => 'textarea',
						'label' => __d('videos', 'Description'),
						'rows' => 5,
					)); ?>


<!--					<label for="description">-->
<!--						--><?php //echo __d('videos', 'Description'); ?>
<!--					</label>-->
<!--					<div class="nc-wysiwyg-alert">-->
<!--						--><?php //echo $this->Form->textarea('description', array(
//							'class' => 'form-control',
//							'id' => 'description',
//							'rows' => 5,
//							//'ng-model' => 'video.description',
//							'default' => $video['description'],
//						)); ?>
<!--					</div>-->

					<div class="form-group"></div>
					<?php $this->NetCommonsForm->unlockField('Tag');
					echo $this->element('Tags.tag_form', array(
						'tagData' => isset($this->request->data['Tag']) ? $this->request->data['Tag'] : array(),
						'modelName' => 'Video',
					)); ?>

					<hr />

					<?php echo $this->Workflow->inputComment('Video.status'); ?>

				</div>
				<?php echo $this->Workflow->buttons('Video.status'); ?>

			<?php echo $this->Form->end(); ?>

			<div class="panel-footer" style="/* border-top-style: none; */">
				<div class="text-right">
					<?php /* 削除 */ ?>
					<?php echo $this->NetCommonsForm->create('Video', array(
						'name' => 'form',
						'url' => array(
							'controller' => 'videos_edit',
							'action' => 'delete',
						),
						'type' => 'delete',
					)); ?>
						<?php echo $this->NetCommonsForm->hidden('Frame.id'); ?>
						<?php echo $this->NetCommonsForm->hidden('Block.id'); ?>
						<?php echo $this->NetCommonsForm->hidden('Block.key'); ?>

						<?php echo $this->NetCommonsForm->hidden('Video.id'); ?>
						<?php echo $this->NetCommonsForm->hidden('Video.key'); ?>
						<?php echo $this->Button->delete('',
							sprintf(__d('net_commons', 'Deleting the %s. Are you sure to proceed?'), __d('videos', 'video'))
						); ?>

					<?php echo $this->NetCommonsForm->end(); ?>
				</div>
			</div>

		</div>
		<?php echo $this->Workflow->comments(); ?>
	</article>
</div>