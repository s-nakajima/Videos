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

// プラグイン名 小文字版
$pluginNameLower = mb_strtolower($pluginName);

// コアでこの言語、共通化希望
$labelDeleteFile = __d('videos', 'Delete file.');
?>

<div class="form-group">
	<div>
		<?php echo $this->Form->label($field, $label); ?>
	</div>

	<div>
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

			<?php echo $this->Form->checkbox('DeleteFile.'.$index.'.File.id', array(
				'value' => $file['id'],
				//'ng-model' => 'deleteFile'
			)); ?>
			<?php echo $this->Form->label('DeleteFile.'.$index.'.File.id', $labelDeleteFile); ?>
		<?php endif; ?>

		<?php /* 必須は要検討 */ ?>
		<?php echo $this->Form->file($field, array(
			'accept' => $fileAccept,
			//'ng-disabled' => 'deleteFile'
		)); ?>

		<?php echo $this->Form->hidden($field . '.File.status', array(
			'value' => 1    // const化希望
		)); ?>
		<?php echo $this->Form->hidden($field . '.File.role_type', array(
			'value' => 'room_file_role'
		)); ?>
		<?php echo $this->Form->hidden($field . '.File.path', array(
			'value' => '{ROOT}' . $pluginNameLower . '{DS}' . $roomId . '{DS}'
		)); ?>
		<?php echo $this->Form->hidden($field . '.FilesPlugin.plugin_key', array(
			'value' => $pluginKey
		)); ?>
		<?php echo $this->Form->hidden($field . '.FilesRoom.room_id', array(
			'value' => $roomId
		)); ?>
		<?php echo $this->Form->hidden($field . '.FilesUser.user_id', array(
			'value' => (int)AuthComponent::user('id')
		)); ?>
		<p class="help-block"><?php echo h($helpBlockMessage); ?></p>
	</div>

	<div>
		<?php if (isset($this->validationErrors[$model][$field])): ?>
			<div class="has-error">
				<?php foreach ($this->validationErrors[$model][$field] as $message): ?>
			<div class="help-block">
				<?php echo $message; ?>
			</div>
		<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</div>
