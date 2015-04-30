<?php
/**
 * 設定用タブ template
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<?php /* 設定のタブ表示。コアで共通化の予定(*´ω｀) */ ?>

<?php /* 下記は動画用に書いてみた('◇')ゞ */ ?>
<?php $tabs = array(
	'index' => array(
		"tabName" => __d('videos', '一覧表示'),
		"url" => '/videos/videoBlockSettings/index/' . $frameId,
	),
	'display' => array(
		"tabName" => __d('videos', '表示方法変更'),
		"url" => '/videos/videoFrameSettings/index/' . $frameId,
	),
	'content' => array(
		"tabName" => __d('videos', 'コンテンツ'),
		"url" => '/videos/videoBlockSettings/edit/' . $frameId,
	),
	'video' => array(
		"tabName" => __d('videos', 'Video'),
		"url" => '/videos/videoBlockSettings/video/' . $frameId,
	),
	'tag' => array(
		"tabName" => __d('videos', 'Tag'),
		"url" => '/videos/videoTags/index/' . $frameId,
	),
); ?>

<?php $this->startIfEmpty('tabs'); ?>
<?php foreach ($tabs as $key => $tab): ?>
	<?php if ($key === $activeTab) : ?>
	<li class="active">
	<?php else: ?>
	<li>
	<?php endif; ?>
		<a href="<?php echo $this->Html->url($tab['url']); ?>">
			<?php echo h($tab['tabName']); ?>
		</a>
	</li>
<?php endforeach; ?>
<?php $this->end(); ?>

<?php $tabs = $this->fetch('tabs'); ?>
<?php if ($tabs) : ?>
	<ul class="nav nav-tabs" role="tablist">
		<?php echo $tabs; ?>
	</ul>
<?php endif; ?>
&nbsp;