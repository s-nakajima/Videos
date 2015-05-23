<?php
/**
 * 動画登録・編集 template
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<?php echo $this->Html->script('/net_commons/js/workflow.js', false); ?>
<?php echo $this->Html->script('/videos/js/videos.js', false); ?>
<?php echo $this->Html->script('/tags/js/tags.js', false); ?>

<div id="nc-videos2-<?php echo (int)$frameId; ?>"
	 ng-controller="Videos"
	 ng-init="initialize(<?php echo h(json_encode($video)); ?>)">

	<div class="modal-body">

		<h1><?php echo __d('videos', 'Video edit'); ?></h1>

		<?php /* ファイル送信は、FormHelperでform作成時、'type' => 'file' 必要。記述すると enctype="multipart/form-data" が追加される */ ?>
		<?php echo $this->Form->create('Video', array(
			'name' => 'form',
			'novalidate' => true,
			'type' => 'file',
		)); ?>

			<div class="panel panel-default">
				<div class="panel-body has-feedback">

					<?php /* 登録時のみ表示 */ ?>
					<?php if ($this->request->action == 'add') : ?>
						<?php echo $this->element('VideosEdit/file', array(
							'pluginName' => 'Videos',
							'label' => __d('videos', 'Video file') . $this->element('NetCommons.required'),
							'field' => Video::VIDEO_FILE_FIELD,
							'fileAccept' => 'video/*',
							'model' => 'Video',
							'pluginKey' => $this->request->params['plugin'],
							'index' => 0,
							'helpBlockMessage' => __d('videos', 'mpeg,avi,mov,wmv,flv,mpg,mp4に対応しています。'),
							'file' => $videoFile,
							'deleteEnable' => false,
						)); ?>
					<?php endif; ?>

					<?php /* 編集時のみ表示 */ ?>
					<?php if ($this->request->action == 'edit') : ?>
						<?php echo $this->element('VideosEdit/file', array(
							'pluginName' => 'Videos',
							'label' => __d('videos', 'Thumbnail'),
							'field' => Video::THUMBNAIL_FIELD,
							'fileAccept' => 'image/*',
							'model' => 'Video',
							'pluginKey' => $this->request->params['plugin'],
							'index' => 1,
							'helpBlockMessage' => __d('videos', '変更したい場合に登録してください。'),
							'file' => $thumbnail,
							'deleteEnable' => false,
							'overwriteEnable' => false,
						)); ?>
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
				<?php /* edit時のみ表示 */ ?>
				<?php if ($this->request->action == 'edit') : ?>
					<div class="panel-footer">
						<div class="text-right">
							<a href="<?php echo $this->Html->url('/videos/videos/delete/' . $frameId); ?>" class="btn btn-danger">
								<span class="glyphicon glyphicon-trash"> </span>
							</a>
						</div>
					</div>
				<?php endif; ?>
			</div>
			<?php /* edit時のみ表示 */ ?>
			<?php if ($this->request->action == 'edit') : ?>
				<?php echo $this->element('Comments.index'); ?>
			<?php endif; ?>

		<?php echo $this->Form->end(); ?>

	</div>
</div>