<?php
/**
 * VideoFixture
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

/**
 * VideoFixture
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Videos\Test\Fixture
 */
class VideoFixture extends CakeTestFixture {

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		// * ルーム管理者が書いたコンテンツ＆一度公開して、下書き中
		//   (id=1とid=2で区別できるものをセットする)
		array(
			'id' => '1',
			'block_id' => '2',
			'category_id' => '1',
			'key' => 'content_key_1',
			'language_id' => '2',
			'status' => '1',
			'is_active' => true,
			'is_latest' => false,
			'title_icon' => '',
			'title' => 'Title 1',
			'video_time' => 1,
			'play_number' => 1,
			'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'created_user' => '1'
		),
		array(
			'id' => '2',
			'block_id' => '2',
			'category_id' => '1',
			'key' => 'content_key_1',
			'language_id' => '2',
			'status' => '3',
			'is_active' => false,
			'is_latest' => true,
			'title_icon' => '',
			'title' => 'Title 2',
			'video_time' => 1,
			'play_number' => 1,
			'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'created_user' => '1'
		),
		// * 一般が書いたコンテンツ＆一度も公開していない（承認待ち）
		array(
			'id' => '3',
			'block_id' => '2',
			'category_id' => '0',
			'key' => 'content_key_2',
			'language_id' => '2',
			'status' => '2',
			'is_active' => false,
			'is_latest' => true,
			'title_icon' => '',
			'title' => 'Title 3',
			'video_time' => 1,
			'play_number' => 1,
			'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'created_user' => '4'
		),
		// * 一般が書いたコンテンツ＆公開して、一時保存
		//   (id=4とid=5で区別できるものをセットする)
		array(
			'id' => '4',
			'block_id' => '2',
			'category_id' => '0',
			'key' => 'content_key_3',
			'language_id' => '2',
			'status' => '1',
			'is_active' => true,
			'is_latest' => false,
			'title_icon' => '',
			'title' => 'Title 4',
			'video_time' => 1,
			'play_number' => 1,
			'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'created_user' => '4'
		),
		array(
			'id' => '5',
			'block_id' => '2',
			'category_id' => '0',
			'key' => 'content_key_3',
			'language_id' => '2',
			'status' => '3',
			'is_active' => false,
			'is_latest' => true,
			'title_icon' => '',
			'title' => 'Title 5',
			'video_time' => 1,
			'play_number' => 1,
			'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'created_user' => '4'
		),
		// * 編集者が書いたコンテンツ＆一度公開して、差し戻し
		//   (id=6とid=7で区別できるものをセットする)
		array(
			'id' => '6',
			'block_id' => '2',
			'category_id' => '0',
			'key' => 'content_key_4',
			'language_id' => '2',
			'status' => '1',
			'is_active' => true,
			'is_latest' => false,
			'title_icon' => '',
			'title' => 'Title 6',
			'video_time' => 1,
			'play_number' => 1,
			'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'created_user' => '3'
		),
		array(
			'id' => '7',
			'block_id' => '2',
			'category_id' => '0',
			'key' => 'content_key_4',
			'language_id' => '2',
			'status' => '4',
			'is_active' => false,
			'is_latest' => true,
			'title_icon' => '',
			'title' => 'Title 7',
			'video_time' => 1,
			'play_number' => 1,
			'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'created_user' => '3'
		),
		// * 編集長が書いたコンテンツ＆一度も公開していない（下書き中）
		array(
			'id' => '8',
			'block_id' => '2',
			'category_id' => '0',
			'key' => 'content_key_5',
			'language_id' => '2',
			'status' => '3',
			'is_active' => false,
			'is_latest' => true,
			'title_icon' => '',
			'title' => 'Title 8',
			'video_time' => 1,
			'play_number' => 1,
			'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'created_user' => '2'
		),
	);

/**
 * Initialize the fixture.
 *
 * @return void
 */
	public function init() {
		require_once App::pluginPath('Videos') . 'Config' . DS . 'Schema' . DS . 'schema.php';
		$this->fields = (new VideosSchema())->tables[Inflector::tableize($this->name)];
		parent::init();
	}

}
