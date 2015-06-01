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

echo $this->Form->hidden('Block.id', array('value' => $block['id']));
?>

<div class="inline-block">
	<?php echo sprintf(__d('videos', 'Delete all data associated with the %s.'), __d('videos', 'channel')); ?>
</div>
<?php echo $this->Form->button('<span class="glyphicon glyphicon-trash"> </span> ' . __d('net_commons', 'Delete'), array(
	'name' => 'delete',
	'class' => 'btn btn-danger pull-right',
	'onclick' => 'return confirm(\'' . sprintf(__d('videos', 'Deleting the %s. Are you sure to proceed?'), __d('videos', 'channel')) . '\')'
));
