<?php
/**
 * メール設定 template
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<div class="block-setting-body">
	<?php echo $this->BlockTabs->main(BlockTabsComponent::MAIN_TAB_BLOCK_INDEX); ?>

	<div class="tab-content">
		<?php echo $this->BlockTabs->block(BlockTabsComponent::BLOCK_TAB_MAIL); ?>

		<?php echo $this->element('Mails.edit_form', array(
			'mailBodyPopoverMessage' => __d('videos', '{X-SITE_NAME} : サイト名称<br />{X-PLUGIN_NAME} : プラグイン名称<br />{X-ROOM} : ルーム名称<br />{X-BLOCK_NAME} : チャンネル名<br />{X-SUBJECT} : 動画タイトル<br />{X-USER} : 投稿者<br />{X-TO_DATE} : 投稿日時<br />{X-BODY} : 登録内容<br />{X-URL} : 登録内容のURL'),
			'cancelUrl' => NetCommonsUrl::backToIndexUrl('default_setting_action'),
		)); ?>
	</div>
</div>
