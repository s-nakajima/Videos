<?php
/**
 * 表示方法変更 エレメント
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Ryo Ozawa <ozawa.ryo@withone.co.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<div class="row form-group">
	<div class="col-xs-12">
		<?php echo $this->Form->label(__d('videos', '表示順')); ?>
	</div>
	<div class="col-xs-12">
		<?php echo $this->Form->select('VideoFrameSetting.display_order',
				VideoFrameSetting::getDisplayOrderOptions(),
				array(
					//'label' => false,
					'type' => 'select',
					'class' => 'form-control',
					'value' => $videoFrameSetting['displayOrder'],
					//'legend' => false,
					'empty' => false,
				)
			); ?>
	</div>
</div>

<div class="row form-group">
	<div class="col-xs-12">
		<?php echo $this->Form->label(__d('videos', '表示件数')); ?>
	</div>
	<div class="col-xs-12">
		<?php echo $this->Form->select('VideoFrameSetting.display_number',
				VideoFrameSetting::getDisplayNumberOptions(),
				array(
					//'label' => false,
					'type' => 'select',
					'class' => 'form-control',
					'value' => $videoFrameSetting['displayNumber'],
					//'legend' => false,
					'empty' => false,
				)
			); ?>
	</div>
</div>
