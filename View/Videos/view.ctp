<?php
/**
 * view template
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

Videos view
<?php if ($contentEditable): ?>
	<div class="row">
		<div class="col-xs-12 text-right">
			<span class="nc-tooltip" tooltip="<?php echo h(__d('net_commons', 'Edit')); ?>">
				<a href="<?php echo $this->Html->url('/videos/videos/edit/' . $frameId) ?>" class="btn btn-primary">
					<span class="glyphicon glyphicon-edit"> </span>
				</a>
			</span>
		</div>
	</div>
<?php endif; ?>