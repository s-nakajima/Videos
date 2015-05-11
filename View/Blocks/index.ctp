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

<div class="modal-body">
	<?php echo $this->element('NetCommons.setting_tabs', array(
		'tabs' => array(
			'block_index' => '/videos/blocks/index/' . $frameId,
			'frame_settings' => '/videos/video_frame_settings/edit/' . $frameId,
		),
		'active' => 'block_index'
	)); ?>

	<div class="tab-content">
		<div class="text-right">
			<a class="btn btn-success" href="<?php echo $this->Html->url('/videos/blocks/add/' . $frameId);?>">
				<span class="glyphicon glyphicon-plus"> </span>
			</a>
		</div>

		<div id="nc-bbs-setting-<?php echo $frameId; ?>">
			<?php echo $this->Form->create('', array(
				'url' => '/frames/frames/edit/' . $frameId
			)); ?>

				<?php echo $this->Form->hidden('Frame.id', array(
					'value' => $frameId,
				)); ?>

				<table class="table table-hover">
					<thead>
						<tr>
							<th>#</th>
							<th>
								<?php echo $this->Paginator->sort('Block.name', __d('videos', 'チャンネル名')); ?>
							</th>
							<th>
								<?php echo $this->Paginator->sort('Block.public_type', __d('videos', 'Public Type')); ?>
							</th>
							<th>
								<?php echo $this->Paginator->sort('VideoBlockSetting.count', __d('videos', '件数')); ?>
							</th>
							<th>
								<?php echo $this->Paginator->sort('VideoBlockSetting.file_size', __d('videos', 'ファイル容量')); ?>
							</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($videoBlockSettings as $videoBlockSetting) : ?>
							<tr<?php echo ($blockId === $videoBlockSetting['block']['id'] ? ' class="active"' : ''); ?>>
								<td>
									<?php echo $this->Form->input('Frame.block_id', array(
										'type' => 'radio',
										'name' => 'data[Frame][block_id]',
										'options' => array((int)$videoBlockSetting['block']['id'] => ''),
										'div' => false,
										'legend' => false,
										'label' => false,
										'hiddenField' => false,
										'checked' => (int)$videoBlockSetting['block']['id'] === (int)$blockId,
										'onclick' => 'submit()'
									)); ?>
								</td>
								<td>
									<a href="<?php echo $this->Html->url('/videos/blocks/edit/' . $frameId . '/' . (int)$videoBlockSetting['block']['id']); ?>">
										<?php echo h($videoBlockSetting['block']['name']); ?>
									</a>
								</td>
								<td>
									<?php if ($videoBlockSetting['block']['publicType'] === '0') : ?>
										<?php echo __d('videos', 'Private'); ?>
									<?php elseif ($videoBlockSetting['block']['publicType'] === '1') : ?>
										<?php echo __d('videos', 'Public'); ?>
									<?php elseif ($videoBlockSetting['block']['publicType'] === '2') : ?>
										<?php echo __d('videos', 'Limited Public'); ?>
									<?php endif; ?>
								</td>
								<td>
									<?php //echo $this->Date->dateFormat($videoBlockSetting['videoBlockSetting']['modified']); ?>
								</td>
								<td>
									<?php //echo $this->Date->dateFormat($videoBlockSetting['videoBlockSetting']['modified']); ?>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			<?php echo $this->Form->end(); ?>

			<div class="text-center">
				<?php echo $this->element('NetCommons.paginator', array(
					'url' => Hash::merge(
						array('controller' => 'blocks', 'action' => 'index', $frameId),
						$this->Paginator->params['named']
					)
				)); ?>
			</div>
		</div>
	</div>
</div>




