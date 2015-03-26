<?php
/**
 * add_form template
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<?php /* echo $this->Form->hidden('id'); ?>
<?php echo $this->Form->hidden('Block.id', array(
	'value' => (int)$blockId,
)); */ ?>

<div class="form-group">
	<?php echo $this->Form->input('title', array(
		'type' => 'text',
		'label' => __d('videos', 'Title') . $this->element('NetCommons.required'),
		'class' => 'form-control',
		'required' => 'required',
		'autofocus' => true,
	)); ?>
</div>

<?php /* 'required' => 'required'まだ */ ?>
<?php echo $this->element('Videos/file',  array(
	'pluginName' => 'Videos',
	'label' => __d('videos', 'Video file') . $this->element('NetCommons.required'),
	'field' => Video::VIDEO_FILE_FIELD,
	'fileAccept' => 'video/*',
	'model' => 'Video',
	'pluginKey' => 'video',
	'index' => 0,
	'helpBlockMessage' => __d('videos', 'mpeg,avi,mov,wmv,flv,mpg,mp4に対応しています。'),
	'file' => $videoFile,
)); ?>

<?php echo $this->element('Videos/file',  array(
	'pluginName' => 'Videos',
	'label' => __d('videos', 'Thumbnail'),
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
	)); ?>
</div>

<div class="form-group"></div>
<div class="form-group">
	<label for="tag">
		<?php echo __d('videos', 'Tag'); ?>
	</label>
	<div class="input-group">
		<?php echo $this->Form->input('tag', array(
			'label' => false,
			'class' => 'form-control',
			'id' => 'tag'
		)); ?>
		<span class="input-group-btn">
			<?php echo $this->Form->button( __d('videos', 'タグを追加する'), array(
				'class' => 'btn',
			)); ?>
		</span>
	</div>
</div>
