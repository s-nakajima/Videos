<?php
/**
 * ZipDownloader Test
 *
 * @author   Ryuji AMANO <ryuji@ryus.co.jp>
 * @author   AllCreator <info@allcreator.net>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 */

/**
 * Class ZipDownloader Test
 */
class ZipDownloader {

/**
 * @var TemporaryFolder 作業用フォルダ
 */
	protected $_tmpFolder;

/**
 * @var string|null password
 */
	protected $_password = null;

/**
 * @var string zip filepath
 */
	public $path = __FILE__;

/**
 * @var bool ファイルオープン常態
 */
	protected $_open = false;

/**
 * @var string Zipコマンド
 */
	protected $_zipCommand = 'zip';

/**
 * files
 *
 * @var array
 */
	public $addFiles = array();

/**
 * addString
 *
 * @var array
 */
	public $addStrings = array();

/**
 * ZipDownloader constructor.
 * @param array $options options
 * @return void
 */
	public function __construct($options = array()) {
	}

/**
 * close
 */
	public function close() {
	}

/**
 * ファイル追加
 *
 * @param string $filePath 追加するファイルのパス
 * @param string|null $localName  ZIPに追加するときのファイル名
 *
 * @return void
 * @throws InternalErrorException
 */
	public function addFile($filePath, $localName = null) {
		$this->addFiles[] = $localName;
	}

	///**
	// * add from string
	// *
	// * @param string $localname zipファイルに追加するときのファイル名
	// * @param string $contents 追加するファイルの中身
	// *
	// * @return void
	// */
	//	public function addFromString($localname, $contents) {
	//		$this->addStrings[$localname] = $contents;
	//	}

/**
 * Download
 *
 * @param string $filename download時のファイル名
 * @return string ファイル名
 */
	public function download($filename) {
		return $filename;
	}

/**
 * set password
 *
 * @param string $password ZIPの解凍・圧縮に使うパスワード
 *
 * @return void
 */
	public function setPassword($password) {
		$this->_password = $password;
	}
}
