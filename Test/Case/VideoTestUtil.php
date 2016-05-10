<?php
/**
 * VideoTest Utility
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('TemporaryFolder', 'Files.Utility');

/**
 * VideoTest Utility
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Mails\Utility
 */
class VideoTestUtil {

/**
 * テストファイルData ゲット
 *
 * @param string $plugin プラグイン名 例:Videos
 * @param string $fileName ファイル名 例:video1.mp4
 * @param string $type MIMETYPE 例:video/mp4
 * @return array テストファイルData
 */
	public function getFileData($plugin, $fileName, $type) {
		$tmpFilePath = self::readyTestFile($plugin, $fileName);
		return array(
			'name' => $fileName,
			'type' => $type,
			'tmp_name' => $tmpFilePath,
			'error' => 0,
			'size' => 4544587,
		);
	}

/**
 * テストファイル準備
 *
 * @param string $plugin プラグイン名 例:Videos
 * @param string $fileName ファイル名 例:video1.mp4
 * @return string tmpFullPath
 */
	public function readyTestFile($plugin, $fileName) {
		$tmpFolder = new TemporaryFolder();

		$testFilePath = APP . 'Plugin' . DS . $plugin . DS . 'Test' . DS . 'Fixture' . DS . $fileName;
		$tmpFilePath = $tmpFolder->path . DS . $fileName;
		copy($testFilePath, $tmpFilePath);

		return $tmpFilePath;
	}
}
