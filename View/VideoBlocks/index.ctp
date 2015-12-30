<?php
/**
 * block index template
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Ryo Ozawa <ozawa.ryo@withone.co.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<article class="block-setting-body">
	<?php echo $this->BlockTabs->main(BlockTabsComponent::MAIN_TAB_BLOCK_INDEX); ?>

	<div class="tab-content">
		<div class="text-right">
			<?php echo $this->Button->addLink(); ?>
		</div>

		<?php echo $this->NetCommonsForm->create('', array(
			'url' => NetCommonsUrl::actionUrl(array('plugin' => 'frames', 'controller' => 'frames', 'action' => 'edit'))
		)); ?>

			<?php echo $this->NetCommonsForm->hidden('Frame.id'); ?>

			<table class="table table-hover">
				<thead>
					<tr>
						<th></th>
						<th>
							<?php echo $this->Paginator->sort('Block.name', __d('videos', 'Channel name')); ?>
						</th>
						<th>
							<?php echo $this->Paginator->sort('Block.public_type', __d('blocks', 'Publishing setting')); ?>
						</th>
						<th class="text-right">
							<?php echo $this->Paginator->sort('VideoBlockSetting.file_size', __d('videos', 'File capacity')); ?>
						</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($videoBlockSettings as $videoBlockSetting) : ?>
						<tr<?php echo ( Current::read('Block.id') === $videoBlockSetting['Block']['id'] ? ' class="active"' : ''); ?>>
							<td>
								<?php echo $this->Form->input('Frame.block_id', array(
									'type' => 'radio',
									'name' => 'data[Frame][block_id]',
									'options' => array((int)$videoBlockSetting['Block']['id'] => ''),
									'div' => false,
									'legend' => false,
									'label' => false,
									'hiddenField' => false,
									'checked' => (int)$videoBlockSetting['Block']['id'] === Current::read('Block.id'),
									'onclick' => 'submit()'
								)); ?>
							</td>
							<td>
								<?php //var_dump($videoBlockSetting) ?>
								<?php echo $this->NetCommonsHtml->editLink($videoBlockSetting['Block']['name'], array('block_id' => $videoBlockSetting['Block']['id'])); ?>
							</td>
							<td>
								<?php if ($videoBlockSetting['Block']['public_type'] === '0') : ?>
									<?php echo __d('blocks', 'Private'); ?>
								<?php elseif ($videoBlockSetting['Block']['public_type'] === '1') : ?>
									<?php echo __d('blocks', 'Public'); ?>
								<?php elseif ($videoBlockSetting['Block']['public_type'] === '2') : ?>
									<?php echo __d('blocks', 'Limited Public'); ?>
								<?php endif; ?>
							</td>
							<td class="text-right">
								<?php //echo $this->Number->toReadableSize((int)$videoBlockSetting['Size']['size_byte']); ?>
								<?php echo $this->Number->toReadableSize(0); ?>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		<?php echo $this->NetCommonsForm->end(); ?>

		<?php echo $this->element('NetCommons.paginator'); ?>

	</div>
</article>
