<?php
/**
 * viewForEditor template
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 * @package NetCommons\Videos\View\Videos
 */
?>

<div id="nc-videos-<?php echo (int)$frameId; ?>">
	<div class="row">
		<div class="col-xs-12 text-right">
			<span class="nc-tooltip" tooltip="<?php echo __d('net_commons', 'Add'); ?>">
				<a href="<?php echo $this->Html->url('/videos/videos/edit/' . $frameId) ?>" class="btn btn-success">
					<span class="glyphicon glyphicon-plus"> </span>
				</a>
			</span>
			<span>
				<a href="<?php echo $this->Html->url('/videos/videoFrameSettings/edit/' . $frameId) ?>" class="btn btn-default">
					<span class="glyphicon glyphicon-cog"> </span>
				</a>
			</span>
		</div>
	</div>
</div>

<?php echo $this->element('Videos/view_contents'); ?>
