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
							array('sort' => true, 'editUrl' => true)
						); ?>
						<?php echo $this->BlockIndex->tableHeader(
							'VideoBlockSetting.total_size', __d('videos', 'File capacity'),
							array('sort' => false, 'type' => 'right')
						); ?>
						<?php echo $this->BlockIndex->tableHeader(
							'Block.content_count', __d('videos', 'Number'),
							//array('sort' => true, 'type' => 'numeric')
							array('sort' => false, 'type' => 'numeric')
						); ?>
						<?php echo $this->BlockIndex->tableHeader(
							'Block.public_type', __d('blocks', 'Publishing setting'),
							array('sort' => true)
						); ?>
						<?php echo $this->BlockIndex->tableHeader(
							'TrackableUpdater.handlename', __d('net_commons', 'Modified user'),
							array('sort' => true, 'type' => 'handle')
						); ?>
						<?php echo $this->BlockIndex->tableHeader(
							'Block.modified', __d('net_commons', 'Modified datetime'),
							array('sort' => true, 'type' => 'datetime')
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
							<td class="text-right">
								<?php echo $this->Number->toReadableSize((int)$videoBlockSetting['Size']['size_byte']); ?>
								<?php //echo $this->Number->toReadableSize((int)$videoBlockSetting['VideoBlockSetting']['total_size']); ?>
							</td>
							<?php echo $this->BlockIndex->tableData(
								'Block.content_count', (int)$videoBlockSetting['Count']['count'],
								//'Block.content_count', (int)$videoBlockSetting['Block']['content_count'],
								array('type' => 'numeric')
							); ?>
							<?php echo $this->BlockIndex->tableData(
								'Block.public_type', $videoBlockSetting
							); ?>
							<?php echo $this->BlockIndex->tableData(
								'TrackableUpdater', $videoBlockSetting,
								array('type' => 'handle')
							); ?>
							<?php echo $this->BlockIndex->tableData(
								'Block.modified', $videoBlockSetting['Block']['modified'],
								array('type' => 'datetime')
							); ?>
						<?php echo $this->BlockIndex->endTableRow(); ?>
					<?php endforeach; ?>
				</tbody>
			<?php echo $this->BlockIndex->endTable(); ?>

		<?php echo $this->BlockIndex->end(); ?>

		<?php echo $this->element('NetCommons.paginator'); ?>
	</div>

</article>
