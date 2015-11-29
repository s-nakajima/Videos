<?php
/**
 * ファイルアップロード部品 template
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<?php /* 暫定部品だよ(;'∀') */ ?>
<?php
/*
// --- パラメータ説明
// プラグイン名
$pluginName = 'Videos';

// ラベル
$label = __d('videos', 'Video file') . $this->element('NetCommons.required');

// フィールド名
$field = Video::VIDEO_FILE_FILED;

// <file>タグ accept属性
$fileAccept = 'image/*';

// モデル
$model = 'Video';

// プラグインキー
$pluginKey = 'videos';

// 下部メッセージ
$helpBlockMessage = __d('videos', 'mpeg,avi,mov,wmv,flv,mpg,mp4に対応しています。');

// ファイル
$file = $videoFile;
*/

// File inputを使う時に指定。2つFile Inputがあれば、1つ目は$index = 0;、2つ目は$index = 1;を指定する。
$index = isset($index) ? $index : 0;

// 削除有効フラグ
$deleteEnable = isset($deleteEnable) ? $deleteEnable : true;

// 上書き有効フラグ
$overwriteEnable = isset($overwriteEnable) ? $overwriteEnable : true;

// プラグイン名 小文字版
$pluginNameLower = mb_strtolower($pluginName);

// コアでこの言語、共通化希望
$labelDeleteFile = __d('files', 'Delete file.');
$labelOverwriteFile = __d('files', 'Overwrite file.');
?>

<div class="form-group">
	<div>
		<?php echo $this->Form->label($field, $label); ?>
	</div>

	<div>
		<?php /* 必須は要検討 */ ?>
		<?php echo $this->Form->file($field, array(
			'accept' => $fileAccept,
			//'ng-disabled' => 'deleteFile'
		)); ?>

		<?php /* ファイルあり=編集時 サムネイル、削除チェックボックス */ ?>
		<?php if (isset($file)) :?>
			<?php if (isset($file['urlThumbnail'])) :?>
				<?php echo $this->Html->image(h($file['urlThumbnail']), array(
					'alt' => h($file['name']),
					'class' => 'img-responsive img-thumbnail',
				)); ?>
			<?php elseif (isset($file['name'])) : ?>
				<span class="img-thumbnail">
					<strong><?php echo h($file['name']); ?></strong>
				</span>
			<?php endif; ?>

			<?php if ($deleteEnable) :?>
				<br />
				<?php echo $this->Form->checkbox('DeleteFile.' . $index . '.File.id', array(
					'value' => $file['id'],
					//'ng-model' => 'deleteFile'
				)); ?>
				<?php echo $this->Form->label('DeleteFile.' . $index . '.File.id', $labelDeleteFile); ?>
			<?php endif; ?>

			<?php /* ファイルあり=編集時 ファイル上書き保存 */ ?>
			<?php if ($overwriteEnable) :?>
				<br />
				<?php echo $this->Form->checkbox('OverwriteFile.' . $index . '.File.id', array(
					'value' => $file['id'],
				)); ?>
				<?php echo $this->Form->label('OverwriteFile.' . $index . '.File.id', $labelOverwriteFile); ?>
			<?php endif; ?>
		<?php endif; ?>


		<?php echo $this->Form->hidden($field . '.File.status', array(
			'value' => 1 // const化希望
		)); ?>
		<?php echo $this->Form->hidden($field . '.File.role_type', array(
			'value' => 'room_file_role'
		)); ?>
		<?php echo $this->Form->hidden($field . '.File.path', array(
			'value' => '{ROOT}' . $pluginNameLower . '{DS}' . Current::read('Room.id') . '{DS}'
		)); ?>
		<?php echo $this->Form->hidden($field . '.FilesPlugin.plugin_key', array(
			'value' => $pluginKey
		)); ?>
		<?php echo $this->Form->hidden($field . '.FilesRoom.room_id', array(
			'value' => Current::read('Room.id')
		)); ?>
		<?php echo $this->Form->hidden($field . '.FilesUser.user_id', array(
			'value' => (int)AuthComponent::user('id')
		)); ?>
		<p class="help-block"><?php echo h($helpBlockMessage); ?></p>
	</div>

<!--	C:\projects\NetCommons3\app\Plugin\NetCommons\View\Helper\NetCommonsFormHelper.php-->
<!--	$output .= $this->Form->input($fieldName, $inputOptions);-->
<!---->
<!--	if (is_array($options['error'])) {-->
<!--	$output .= '<div class="has-error">';-->
<!--		$output .= $this->Form->error($fieldName, null, Hash::merge(array('class' => 'help-block'), $options['error']));-->
<!--		$output .= '</div>';-->
<!--	}-->
	<div class="has-error">
		<?php echo $this->Form->error($field, null, array('class' => 'help-block')); ?>
	</div>
</div>
