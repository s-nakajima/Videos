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

<?php echo $this->Form->hidden('id'); ?>
<?php echo $this->Form->hidden('Block.id', array(
	'value' => (int)$blockId,
)); ?>

<div class="form-group">
	<?php echo $this->Form->input('title',
		array(
			'label' => __d('videos', 'Title') . $this->element('NetCommons.required'),
			'class' => 'form-control',
			'required' => 'required',
			'autofocus' => true,
		)) ?>
</div>

<div class="form-group">
	<?php echo $this->Form->input('videoFile',
		array(
			'type' => 'file',
			'label' => __d('videos', 'Video file') . $this->element('NetCommons.required'),
			'required' => 'required',
		)) ?>
	<p class="help-block"><?php echo __d('videos', 'mpeg,avi,mov,wmv,flv,mpg,mp4に対応しています。'); ?></p>
</div>

<div class="form-group">
	<?php echo $this->Form->input('thumbnail',
		array(
			'type' => 'file',
			'label' => __d('videos', 'Thumbnail'),
		)) ?>
	<p class="help-block"><?php echo __d('videos', 'サムネイルは自動で作成できます。変更したい場合に登録してください。'); ?></p>
</div>

<label for="description">
	<?php echo __d('videos', 'Description'); ?>
</label>
<div class="nc-wysiwyg-alert">
	<?php echo $this->Form->textarea('description',
		array(
			'class' => 'form-control',
			'id' => 'description',
			'rows' => 5,
		)) ?>
</div>

<div class="form-group"></div>
<div class="form-group">
	<label for="tag">
		<?php echo __d('videos', 'Tag'); ?>
	</label>
	<div class="input-group">
		<?php echo $this->Form->input('tag',
			array(
				'label' => false,
				'class' => 'form-control',
				'id' => 'tag'
			)) ?>
		<span class="input-group-btn">
			<?php echo $this->Form->button( __d('videos', 'タグを追加する'),
				array(
					'class' => 'btn',
				)) ?>
		</span>
	</div>
</div>

