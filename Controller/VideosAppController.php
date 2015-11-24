<?php
/**
 * VideosApp Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AppController', 'Controller');

/**
 * VideosApp Controller
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Videos\Controller
 * @property Comment $Comment
 * @property FileModel $FileModel
 * @property FileUploadComponent $FileUpload
 * @property ContentCommentsComponent $ContentComments
 * @property WorkflowComponent $Workflow
 * @property PermissionComponent $Permission
 * @property PageLayoutComponent $PageLayout
 * @property Video $Video
 * @property VideoFrameSetting $VideoFrameSetting
 * @property VideoBlockSetting $VideoBlockSetting
 */
class VideosAppController extends AppController {

/**
 * use component
 *
 * @var array
 */
	public $components = array(
		'Pages.PageLayout',
		'Security',
	);

/**
 * initTabs
 *
 * @param string $mainActiveTab Main active tab
 * @param string $blockActiveTab Block active tab
 * @return void
 */
	public function initTabs($mainActiveTab, $blockActiveTab) {
//		if (isset($this->params['pass'][1])) {
//			$blockId = (int)$this->params['pass'][1];
//		} else {
//			$blockId = null;
//		}
//
//		//タブの設定
//		$settingTabs = array(
//			'tabs' => array(
//				'block_index' => array(
//					'url' => array(
//						'plugin' => $this->params['plugin'],
//						'controller' => 'video_block_settings',
//						'action' => 'index',
//						$this->viewVars['frameId'],
//					)
//				),
//				'frame_settings' => array(
//					'url' => array(
//						'plugin' => $this->params['plugin'],
//						'controller' => 'video_frame_settings',
//						'action' => 'edit',
//						$this->viewVars['frameId'],
//					)
//				),
//			),
//			'active' => $mainActiveTab
//		);
//		$this->set('settingTabs', $settingTabs);
//
//		$blockSettingTabs = array(
//			'tabs' => array(
//				'block_settings' => array(
//					'url' => array(
//						'plugin' => $this->params['plugin'],
//						'controller' => 'video_block_settings',
//						'action' => $this->params['action'],
//						$this->viewVars['frameId'],
//						$blockId
//					)
//				),
//				'role_permissions' => array(
//					'url' => array(
//						'plugin' => $this->params['plugin'],
//						'controller' => 'video_block_role_permissions',
//						'action' => 'edit',
//						$this->viewVars['frameId'],
//						$blockId
//					)
//				),
//			),
//			'active' => $blockActiveTab
//		);
//
//		$this->set('blockSettingTabs', $blockSettingTabs);
	}

/**
 * namedパラメータ取得
 *
 * @param string $name namedパラメータ名
 * @param null $default パラメータが存在しなかったときのデフォルト値
 * @return int|string
 */
	protected function _getNamed($name, $default = null) {
		$value = isset($this->request->params['named'][$name]) ? $this->request->params['named'][$name] : $default;
		return $value;
	}

/**
 * ワークフロー表示条件 取得
 *
 * @param string $videoKey Videos.key
 * @return array Conditions data
 */
	protected function _getWorkflowConditions($videoKey = null) {
		//ゲスト
		$activeConditions = array(
			$this->Video->alias . '.is_active' => true,
		);
		$latestConditons = array();

		//コンテンツ編集 許可あり
		if ($this->viewVars['contentEditable']) {
			$activeConditions = array();
			$latestConditons = array(
				$this->Video->alias . '.is_latest' => true,
			);

			//コンテンツ作成 許可あり
		} elseif ($this->viewVars['contentCreatable']) {
			$activeConditions = array(
				$this->Video->alias . '.is_active' => true,
				$this->Video->alias . '.created_user !=' => (int)$this->viewVars['userId'],
			);
			$latestConditons = array(
				$this->Video->alias . '.is_latest' => true,
				$this->Video->alias . '.created_user' => (int)$this->viewVars['userId'],
			);
		}

		$conditions = array(
			'Block.id = ' . $this->viewVars['blockId'],
			'Block.language_id = ' . $this->viewVars['languageId'],
			'Block.room_id = ' . $this->viewVars['roomId'],
			'OR' => array($activeConditions, $latestConditons)
		);

		// 動画1件 取得条件
		if (!empty($videoKey)) {
			$conditions[$this->Video->alias . '.key'] = $videoKey;
		}

		return $conditions;
	}
}
