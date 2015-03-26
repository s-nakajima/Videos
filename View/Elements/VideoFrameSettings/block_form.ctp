<?php
/**
 * block edit form template
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Ryo Ozawa <ozawa.ryo@withone.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>
<!-- frame setting START -->

<?php //echo $this->Form->hidden('Block.id', array('value' => $block['id'])); ?>
<?php
// https://github.com/ryozawa/Blocks/blob/master/View/Elements/edit_form.ctp よりコピー
//
// 暫定対応(;'∀')
// 一般以下のパートが閲覧可能かどうか。
// （0:非公開,　1:公開, 2:期間限定公開）
const TYPE_PRIVATE = 0;
const TYPE_PUBLIC = 1;
const TYPE_LIMITED_PUBLIC = 2;
?>
<div class="form-group">
	<?php echo $this->Form->input('Block.name',
		array(
			'type' => 'text',
			'label' => $nameLabel,
			'class' => 'form-control',
			'error' => false,
			'ng-model' => 'block.name',
		)); ?>
	<div class="has-error">
		<?php if (isset($this->validationErrors['Block']['name'])): ?>
		<?php foreach ($this->validationErrors['Block']['name'] as $message): ?>
			<div class="help-block">
				<?php echo $message ?>
			</div>
		<?php endforeach ?>
		<?php endif; ?>
	</div>
</div>

<div class="form-group">
	<div>
		<label>
			<?php echo __d('blocks', 'Public Setting'); ?>
		</label>
	</div>
	<?php echo $this->Form->input('Block.public_type',
		array(
			'type' => 'radio',
			'options' => array(
//				Block::TYPE_PRIVATE => __d('blocks', 'Private'),
//				Block::TYPE_PUBLIC => __d('blocks', 'Public'),
//				Block::TYPE_LIMITED_PUBLIC => __d('blocks', 'Limited Public'),
				TYPE_PRIVATE => __d('blocks', 'Private'),
				TYPE_PUBLIC => __d('blocks', 'Public'),
				TYPE_LIMITED_PUBLIC => __d('blocks', 'Limited Public'),
			),
			'div' => false,
			'legend' => false,
			'error' => false,
			//'ng-model' => 'block.publicType',
			'ng-model' => 'block.public_type',
			'checked' => true,
		)); ?>
<!-- <div collapse="block.publicType != <?php //echo Block::TYPE_LIMITED_PUBLIC; ?>"> -->
	<div collapse="block.public_type != <?php echo TYPE_LIMITED_PUBLIC; ?>">
		<div class="row" style="margin-bottom:5px;">
			<div class="col-md-2">
				<?php echo __d('blocks', 'Start'); ?>
			</div>
			<div class="col-md-10">
				<div class="input-group">
					<?php echo $this->Form->input('Block.from',
						array(
							'type' => 'text',
							'class' => 'form-control',
							'error' => false,
							'ng-model' => 'block.from',
							'datepicker-popup' => 'yyyy/MM/dd HH:mm',
							'is-open' => 'isFrom',
							'label' => false,
							'div' => false,
							'style' => 'min-width:170px',
						)); ?>
					<span class="input-group-btn">
						<button type="button" class="btn btn-default" ng-click="showCalendar($event, 'from')">
							<i class="glyphicon glyphicon-calendar"></i>
						</button>
					</span>
				</div>
				<div class="has-error">
					<?php if (isset($this->validationErrors['Block']['from'])): ?>
					<?php foreach ($this->validationErrors['Block']['from'] as $message): ?>
						<div class="help-block">
							<?php echo $message ?>
						</div>
					<?php endforeach ?>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<div class="row" style="margin-bottom:5px;">
			<div class="col-md-2">
				<?php echo __d('blocks', 'End'); ?>
			</div>
			<div class="col-md-10">
				<div class="input-group">
					<?php echo $this->Form->input('Block.to',
						array(
							'type' => 'text',
							'class' => 'form-control',
							'error' => false,
							'ng-model' => 'block.to',
							'datepicker-popup' => 'yyyy/MM/dd HH:mm',
							'is-open' => 'isTo',
							'label' => false,
							'div' => false,
							'style' => 'min-width:170px',
						)); ?>
					<span class="input-group-btn">
						<button type="button" class="btn btn-default" ng-click="showCalendar($event, 'to')">
							<i class="glyphicon glyphicon-calendar"></i>
						</button>
					</span>
				</div>
				<div class="has-error">
					<?php if (isset($this->validationErrors['Block']['to'])): ?>
					<?php foreach ($this->validationErrors['Block']['to'] as $message): ?>
						<div class="help-block">
							<?php echo $message ?>
						</div>
					<?php endforeach ?>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- frame setting E N D -->