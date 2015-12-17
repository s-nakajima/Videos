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
		//'/net_commons/js/workflow.js',
		'/tags/js/tags.js',
		'/videos/js/videos.js',
	),
	array('plugin' => false, 'once' => true, 'inline' => false)
);?>

<div>
	<article class="modal-body">

		<?php /* ファイル送信は、FormHelperでform作成時、'type' => 'file' 必要。記述すると enctype="multipart/form-data" が追加される */ ?>
		<?php echo $this->NetCommonsForm->create('Video', array(
			'name' => 'form',
			'novalidate' => true,
			'type' => 'file',
		)); ?>

			<div class="panel panel-default">
				<div class="panel-body has-feedback">

					<?php // ffmpeg=ON
					if (Video::isFfmpegEnable()) {
						$videoHelpBlockMessage = sprintf(__d('videos', 'support of %s.'), Video::VIDEO_EXTENSION);
					} else {
						$videoHelpBlockMessage = sprintf(__d('videos', 'support of %s.'), 'mp4');
					} ?>
<!--					--><?php //echo $this->element('VideosEdit/file', array(
//						'pluginName' => 'Videos',
//						'label' => __d('videos', 'Video file') . $this->element('NetCommons.required'),
//						'field' => Video::VIDEO_FILE_FIELD,
//						'fileAccept' => 'video/*',
//						'model' => 'Video',
//						'pluginKey' => $this->request->params['plugin'],
//						'index' => 0,
//						'helpBlockMessage' => $videoHelpBlockMessage,
//						'file' => $videoFile,
//						'deleteEnable' => false,
//					)); ?>
					<?php echo $this->NetCommonsForm->uploadFile(Video::VIDEO_FILE_FIELD, array(
						'label' => __d('videos', 'Video file'),
						'required' => true,
					)); ?>
					<?php /* 暫定対応 */ ?>
					<div><p class="help-block"><?php echo h($videoHelpBlockMessage); ?></p></div>

					<?php //echo $this->NetCommonsForm->uploadFile('videoFile'); ?>

					<?php /* ffmpeg=OFF */ ?>
					<?php if (!Video::isFfmpegEnable()) : ?>
<!--						--><?php //echo $this->element('VideosEdit/file', array(
//							'pluginName' => 'Videos',
//							'label' => __d('videos', 'Thumbnail') . $this->element('NetCommons.required'),
//							'field' => Video::THUMBNAIL_FIELD,
//							'fileAccept' => 'image/*',
//							'model' => 'Video',
//							'pluginKey' => $this->request->params['plugin'],
//							'index' => 1,
//							'helpBlockMessage' => sprintf(__d('videos', 'support of %s.'), Video::THUMBNAIL_EXTENSION),
//							'file' => $thumbnail,
//							'deleteEnable' => false,
//							'overwriteEnable' => false,
//						)); ?>
						<?php echo $this->NetCommonsForm->uploadFile('thumbnail', array(
							'label' => __d('videos', 'Thumbnail'),
							'required' => true,
						)); ?>
						<?php /* 暫定対応 */ ?>
						<div><p class="help-block"><?php echo sprintf(__d('videos', 'support of %s.'), Video::THUMBNAIL_EXTENSION); ?></p></div>

					<?php endif; ?>
					<?php echo $this->NetCommonsForm->hidden('Video.block_id'); ?>
					<?php echo $this->NetCommonsForm->hidden('Video.language_id'); ?>

					<?php echo $this->NetCommonsForm->input('Video.title', array(
						'type' => 'text',
						'label' => __d('videos', 'Title'),
						'required' => true,
					)); ?>

					<?php echo $this->NetCommonsForm->input('Video.description', array(
						'type' => 'textarea',
						'label' => __d('videos', 'Description'),
						'rows' => 5,
					)); ?>

					<div class="form-group"></div>
					<?php $this->Form->unlockField('Tag');
					echo $this->element('Tags.tag_form', array(
						'tagData' => isset($this->request->data['Tag']) ? $this->request->data['Tag'] : array(),
						'modelName' => 'Video',
					)); ?>

					<hr />

					<?php echo $this->Workflow->inputComment('Video.status'); ?>

				</div>
				<?php echo $this->Workflow->buttons('Video.status'); ?>
			</div>

		<?php echo $this->NetCommonsForm->end(); ?>

		<?php echo $this->Workflow->comments(); ?>

	</article>
</div>