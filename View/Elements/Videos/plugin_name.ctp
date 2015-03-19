<?php
/**
 * plugin_name template
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<?php $this->start('title'); ?>
<?php echo h($pluginName); ?>
<?php $this->end(); ?>

<div class="modal-header">
	<?php $title = $this->fetch('title'); ?>
	<?php if ($title) : ?>
		<?php echo $title; ?>
	<?php else : ?>
		<br />
	<?php endif; ?>
</div>
