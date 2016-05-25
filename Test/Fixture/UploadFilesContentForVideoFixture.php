<?php
/**
 * UploadFilesContentForVideoFixture
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 */

App::uses('UploadFilesContentFixture', 'Files.Test/Fixture');

/**
 * UploadFilesContentForVideoFixture
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Videos\Test\Fixture
 */
class UploadFilesContentForVideoFixture extends UploadFilesContentFixture {

/**
 * Model name
 *
 * @var string
 */
	public $name = 'UploadFilesContent';

/**
 * Full Table Name
 *
 * @var string
 */
	public $table = 'upload_files_contents';

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array( // video
			'id' => 7,
			'plugin_key' => 'videos',
			'content_id' => 2,
			'upload_file_id' => 11,
			'created_user' => 1,
			'created' => '2015-10-29 08:50:56',
			'modified_user' => 1,
			'modified' => '2015-10-29 08:50:56'
		),
		array( // video
			'id' => 8,
			'plugin_key' => 'videos',
			'content_id' => 1,
			'upload_file_id' => 12,
			'created_user' => 1,
			'created' => '2015-10-29 08:50:56',
			'modified_user' => 1,
			'modified' => '2015-10-29 08:50:56'
		),
	);

}
