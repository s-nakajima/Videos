<?php
/**
 * 動画登録・編集 エレメント template
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<div class="form-group">
	<?php echo $this->Form->input('title', array(
		'type' => 'text',
		'label' => __d('videos', 'Title') . $this->element('NetCommons.required'),
		'class' => 'form-control',
		'required' => 'required',
		'ng-model' => 'video.title',
		'autofocus' => true,
	)); ?>
</div>

<?php /* 'required' => 'required'まだ */ ?>
<?php echo $this->element('Videos/file', array(
	'pluginName' => 'Videos',
	'label' => __d('videos', 'Video file') . $this->element('NetCommons.required'),
	'field' => Video::VIDEO_FILE_FIELD,
	'fileAccept' => 'video/*',
	'model' => 'Video',
	'pluginKey' => 'video',
	'index' => 0,
	'helpBlockMessage' => __d('videos', 'mpeg,avi,mov,wmv,flv,mpg,mp4に対応しています。'),
	'file' => $videoFile,
	'deleteEnable' => false,
)); ?>

<?php echo $this->element('Videos/file', array(
	'pluginName' => 'Videos',
	'label' => __d('videos', 'Thumbnail') . $this->element('NetCommons.required'),
	'field' => Video::THUMBNAIL_FIELD,
	'fileAccept' => 'image/*',
	'model' => 'Video',
	'pluginKey' => 'video',
	'index' => 1,
	'helpBlockMessage' => __d('videos', 'サムネイルは自動で作成できます。変更したい場合に登録してください。'),
	'file' => $thumbnail,
)); ?>

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
));
