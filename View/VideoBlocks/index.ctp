<?php
/**
 * VideoBlocks index template
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
	<?php echo $this->BlockTabs->main(BlockTabsHelper::MAIN_TAB_BLOCK_INDEX); ?>

	<?php echo $this->BlockIndex->description(); ?>

	<div class="tab-content">
		<?php echo $this->BlockIndex->create(); ?>
			<?php echo $this->BlockIndex->addLink(); ?>

			<?php echo $this->BlockIndex->startTable(); ?>
				<thead>
					<tr>
						<?php echo $this->BlockIndex->tableHeader(
							'Frame.block_id'
						); ?>
						<?php echo $this->BlockIndex->tableHeader(
							'Block.name', __d('videos', 'Channel name'),
							array('sort' => true)
						); ?>
						<?php echo $this->BlockIndex->tableHeader(
							'TrackableCreator.handlename', __d('net_commons', 'Created user'),
							array('sort' => true, 'type' => 'handle')
						); ?>
						<?php echo $this->BlockIndex->tableHeader(
							'Block.public_type', __d('blocks', 'Publishing setting'),
							array('sort' => true)
						); ?>
						<?php echo $this->BlockIndex->tableHeader(
							'VideoBlockSetting.file_size', __d('videos', 'File capacity'),
							array('sort' => false, 'type' => 'right')
						); ?>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($videoBlockSettings as $videoBlockSetting) : ?>
						<?php echo $this->BlockIndex->startTableRow($videoBlockSetting['Block']['id']); ?>
							<?php echo $this->BlockIndex->tableData(
								'Frame.block_id', $videoBlockSetting['Block']['id']
							); ?>
							<?php echo $this->BlockIndex->tableData(
								'Block.name', $videoBlockSetting['Block']['name'],
								array('editUrl' => array('block_id' => $videoBlockSetting['Block']['id']))
							); ?>
							<?php echo $this->BlockIndex->tableData(
								'TrackableCreator', $videoBlockSetting,
								array('type' => 'handle')
							); ?>
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
								<?php echo $this->Number->toReadableSize((int)$videoBlockSetting['Size']['size_byte']); ?>
							</td>
						<?php echo $this->BlockIndex->endTableRow(); ?>
					<?php endforeach; ?>
				</tbody>
			<?php echo $this->BlockIndex->endTable(); ?>

		<?php echo $this->BlockIndex->end(); ?>

		<?php echo $this->element('NetCommons.paginator'); ?>
	</div>

</article>
