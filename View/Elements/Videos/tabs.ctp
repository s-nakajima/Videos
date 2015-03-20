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
	0 => array(
		"tabName" => h(__d('videos', '一覧表示')),
		"url" => '/videos/videoFrameSettings/index/' . $frameId,
	),
	1 => array(
		"tabName" => h(__d('videos', '表示方法変更')),
		"url" => '/videos/videoFrameSettings/display/' . $frameId,
	),
	2 => array(
		"tabName" => h(__d('videos', 'コンテンツ')),
		"url" => '/videos/videoFrameSettings/content/' . $frameId,
	),
	3 => array(
		"tabName" => h(__d('videos', '権限設定')),
		"url" => '/videos/videoFrameSettings/authority/' . $frameId,
	),
	4 => array(
		"tabName" => h(__d('videos', 'Video')),
		"url" => '/videos/videoFrameSettings/video/' . $frameId,
	),
	5 => array(
		"tabName" => h(__d('videos', 'Tag')),
		"url" => '/videos/videoFrameSettings/tag/' . $frameId,
	),
); ?>

<?php $this->startIfEmpty('tabs'); ?>
<?php foreach ($tabs as $key => $tab): ?>
	<?php if ($key === $activeTabIndex) : ?>
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
	<br />
	<?php $tabId = $this->fetch('tabIndex'); ?>
	<div class="tab-content" ng-init="tab.setTab(<?php echo (int)$tabId; ?>)"></div>
<?php endif; ?>
