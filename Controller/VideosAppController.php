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
}
