<?php
/**
 * コンテンツ削除 エレメント
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<?php echo $this->NetCommonsForm->hidden('Block.id'); ?>
<?php echo $this->NetCommonsForm->hidden('VideoBlockSetting.key'); ?>

<div class="inline-block">
	<?php echo sprintf(__d('net_commons', 'Delete all data associated with the %s.'), __d('videos', 'channel')); ?>
</div>
<?php echo $this->Button->delete(
	__d('net_commons', 'Delete'),
	sprintf(__d('net_commons', 'Deleting the %s. Are you sure to proceed?'), __d('videos', 'channel')),
	array('addClass' => 'pull-right')
);