<?php
/**
 * 登録・編集用タブ template
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<?php /* 登録・編集時の画面上部表示。まだ暫定だよ(;'∀') */ ?>

<?php $activeTabIndex = 0; ?>
<?php $tabs = array(
	array(
		"tabName" => h($tabName),
		"url" => '',
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
<?php endif; ?>
&nbsp;