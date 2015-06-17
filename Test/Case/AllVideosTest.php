<?php
/**
 * Videos All Test Case
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

/**
 * Videos All Test Case
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Videos\Test\Case
 * @codeCoverageIgnore
 */
class AllVideosTest extends CakeTestSuite {

/**
 * All test suite
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @return CakeTestSuite
 */
	public static function suite() {
		$plugin = preg_replace('/^All([\w]+)Test$/', '$1', __CLASS__);
		$suite = new CakeTestSuite(sprintf('All %s Plugin tests', $plugin));

		//$suite->addTestDirectoryRecursive(CakePlugin::path($plugin) . 'Test' . DS . 'Case');
		$directory = CakePlugin::path($plugin) . 'Test' . DS . 'Case';
		$Folder = new Folder($directory);
		$exceptions = array(
			'VideoTestBase.php',
			'VideoBehaviorTestBase.php',
			'VideosTestBase.php',
			'VideoValidationTestBase.php',
		);
		$files = $Folder->tree(null, $exceptions, 'files');
		foreach ($files as $file) {
			if (substr($file, -4) === '.php') {
				$suite->addTestFile($file);
			}
		}

		return $suite;
	}
}
