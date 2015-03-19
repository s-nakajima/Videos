<?php
/**
 * tabs template
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<?php $tabs = array(
	array(
		"tabName" => h(__d('videos', '一覧表示')),
		"url" => '/videos/videoFrameSettings/index/' . $frameId,
	),
	array(
		"tabName" => h(__d('videos', '表示方法変更')),
		"url" => '/videos/videoFrameSettings/display/' . $frameId,
	),
	array(
		"tabName" => h(__d('videos', 'コンテンツ')),
		"url" => '/videos/videoFrameSettings/content/' . $frameId,
	),
	array(
		"tabName" => h(__d('videos', '権限設定')),
		"url" => '/videos/videoFrameSettings/authority/' . $frameId,
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
