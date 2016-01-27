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

<?php echo $this->NetCommonsForm->hidden('Frame.id'); ?>
<?php echo $this->NetCommonsForm->hidden('VideoFrameSetting.id'); ?>
<?php echo $this->NetCommonsForm->hidden('VideoFrameSetting.frame_key'); ?>

<div class="row form-group">
	<div class="col-xs-12">
		<?php echo $this->NetCommonsForm->label(__d('videos', 'Display order')); ?>
	</div>
	<div class="col-xs-12">
		<?php /* @link http://book.cakephp.org/2.0/ja/core-libraries/helpers/form.html#FormHelper::select */ ?>
		<?php echo $this->NetCommonsForm->select('VideoFrameSetting.display_order',
			array(
				VideoFrameSetting::DISPLAY_ORDER_NEW => __d('videos', 'Newest'),
				VideoFrameSetting::DISPLAY_ORDER_TITLE => __d('videos', 'By title'),
				VideoFrameSetting::DISPLAY_ORDER_PLAY => __d('videos', 'Viewed'),
				//VideoFrameSetting::DISPLAY_ORDER_LIKE => __d('videos', 'Reviews'),
			),
			array(
				'type' => 'select',
				'class' => 'form-control',
				'default' => $this->request->data['VideoFrameSetting']['display_order'],
				'empty' => false,
			)
		); ?>
	</div>
</div>

<?php echo $this->DisplayNumber->select('VideoFrameSetting.display_number', array(
	'label' => __d('videos', 'Display number'),
));