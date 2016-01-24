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
 * @property FileUploadComponent $FileUpload
 * @property DownloadComponent $Download
 * @property ContentComment $ContentComment
 * @property ContentCommentsComponent $ContentComments
 * @property NetCommonsComponent $NetCommons
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
			'Block.room_id = ' . Current::read('Room.id'),
			'OR' => array($activeConditions, $latestConditons)
		);

		// 動画1件 取得条件
		if (!empty($videoKey)) {
			$conditions[$this->Video->alias . '.key'] = $videoKey;
		}

		return $conditions;
	}

/**
 * 権限の取得
 *
 * @return array
 */
	protected function _getPermission() {
		$permissionNames = array(
			'content_readable',
			'content_creatable',
			'content_editable',
			'content_publishable',
		);
		$permission = array();
		foreach ($permissionNames as $key) {
			$permission[$key] = Current::permission($key);
		}
		return $permission;
	}

/**
 * 現在の日時を返す
 *
 * @return string datetime
 */
	protected function _getCurrentDateTime() {
		return date('Y-m-d H:i:s', $this->_getNow());
	}

/**
 * 現在時刻を返す。テストしやすくするためにメソッドに切り出した。
 *
 * @return int
 */
	protected function _getNow() {
		return time();
	}

}
