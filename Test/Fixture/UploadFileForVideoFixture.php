<?php
/**
 * UploadFileForVideoFixture
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('UploadFileFixture', 'Files.Test/Fixture');

/**
 * UploadFileForVideoFixture
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Videos\Test\Fixture
 */
class UploadFileForVideoFixture extends UploadFileFixture {

/**
 * Model name
 *
 * @var string
 */
	public $name = 'UploadFile';

/**
 * Full Table Name
 *
 * @var string
 */
	public $table = 'upload_files';

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array( // video
			'id' => 11,
			'plugin_key' => 'videos',
			'content_key' => 'content_key_1',
			'field_name' => 'video_file',
			'original_name' => 'video1.mp4',
			'path' => 'files/upload_file/real_file_name/1/',
			'real_file_name' => 'video1.mp4',
			'extension' => 'mp4',
			'mimetype' => 'video/mp4',
			'size' => 4544587,
			'download_count' => 11,
			'total_download_count' => 11,
			'room_id' => 1,
			'block_key' => 'Lorem ipsum dolor sit amet',
			'created_user' => 1,
			'created' => '2016-02-25 03:44:14',
			'modified_user' => 1,
			'modified' => '2016-02-25 03:44:14'
		),
		array( // video
			'id' => 12,
			'plugin_key' => 'videos',
			'content_key' => 'content_key_2',
			'field_name' => 'video_file',
			'original_name' => 'video2.MOV',
			'path' => 'files/upload_file/real_file_name/1/',
			'real_file_name' => 'video2.MOV',
			'extension' => 'MOV',
			'mimetype' => 'video/quicktime',
			'size' => 4544587,
			'download_count' => 12,
			'total_download_count' => 12,
			'room_id' => 1,
			'block_key' => 'Lorem ipsum dolor sit amet',
			'created_user' => 1,
			'created' => '2016-02-25 03:44:14',
			'modified_user' => 1,
			'modified' => '2016-02-25 03:44:14'
		),
	);

}
