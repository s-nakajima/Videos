<?php
/**
 * BbsSettings edit template
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<?php echo $this->Html->script('/bbses/js/bbses.js', false); ?>

<div class="modal-body" ng-controller="Bbses">
	<?php echo $this->element('NetCommons.setting_tabs', array(
			'tabs' => array(
				'block_index' => '/videos/blocks/index/' . $frameId,
				'frame_settings' => '/videos/video_frame_settings/edit/' . $frameId,
			),
			'active' => 'block_index'
		)); ?>

	<div class="tab-content">
		<?php echo $this->element('Blocks.setting_tabs', array(
				'tabs' => array(
					'block_settings' => '/bbses/blocks/' . h($this->request->params['action']) . '/' . $frameId . '/' . $blockId,
					'role_permissions' => '/bbses/block_role_permissions/' . h($this->request->params['action']) . '/' . $frameId . '/' . $blockId
				),
				'active' => 'block_settings'
			)); ?>

		<?php echo $this->element('Blocks.edit_form', array(
				'controller' => 'Blocks',
				'action' => h($this->request->params['action']) . '/' . $frameId . '/' . $blockId,
				'callback' => 'Bbses.Blocks/edit_form',
				'cancel' => '/bbses/blocks/index/' . $frameId
			)); ?>

		<?php if ($this->request->params['action'] === 'edit') : ?>
			<?php echo $this->element('Blocks.delete_form', array(
					'controller' => 'Blocks',
					'action' => 'delete/' . $frameId . '/' . $blockId,
					'callback' => 'Bbses.Blocks/delete_form'
				)); ?>
		<?php endif; ?>
	</div>
</div>
