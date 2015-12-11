<?php
/**
 * Video Behavior
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

/**
 * Summary for Video Behavior
 */
class VideoBehavior extends ModelBehavior {

/**
 * 動画変換とデータ保存
 *
 * @param Model $model モデル
 * @param array $data received post data
 * @param array $video Video
 * @return bool true on success, false on error
 * @throws InternalErrorException
 */
	public function saveConvertVideo(Model $model, $data, $video) {
		// 元動画 取得
//		$noConvert = $model->FileModel->findById($video['Video']['mp4_id']);

		// C:\projects\NetCommons3\app\Plugin\Blogs\Controller\BlogEntriesEditController.php #219あたり
		$UploadFile = ClassRegistry::init('Files.UploadFile');
//		$path = '/var/www/app/app/Plugin/Files/Test/Fixture/logo.gif';
//		$path2 = TMP . 'logo.gif';
//		copy($path, $path2);
		//$UploadFile->registByFilePath($path2, 'blogs', 'content_key..', 'photo');
		$UploadFile->registByFilePath($model->date['Video']['video_file']['tmp_name'], 'videos', $video['Video']['key'], Video::VIDEO_FILE_FIELD);


		// --- 動画変換
		if (! $data = $this->__convertVideo($model, $data, $video, $noConvert)) {
			$model->deleteFile($data, $model->alias, 'mp4_id', 0);	//元動画 削除
			return false;
		}

		// --- 再生時間を取得
		if (!$videoTimeSec = $this->__getVideoTime($noConvert)) {
			$model->deleteFile($data, $model->alias, 'mp4_id', 0);	//元動画 削除
			return false;
		}
		$data['Video']['video_time'] = $videoTimeSec;

		// --- サムネイル自動作成
		$data = $this->__generateThumbnail($data, $video[Video::VIDEO_FILE_FIELD]['FilesPlugin']['plugin_key'], $noConvert);

		// ファイルチェック サムネイル
		if (! $data = $model->validateVideoFile($data, Video::THUMBNAIL_FIELD, $model->alias, 'thumbnail_id', 1)) {
			$this->log($model->validationErrors, 'debug');
			//変換後動画、サムネイル 削除
			$model->deleteFile($data, $model->alias, 'mp4_id', 0);
			$model->deleteFile($data, $model->alias, 'thumbnail_id', 1);

			return false;
		}

		// ファイルの登録 サムネイル
		$data = $model->saveVideoFile($data, Video::THUMBNAIL_FIELD, $model->alias, 'thumbnail_id', 1);

		// --- 動画テーブルを更新
		// 値をセット
		$model->set($data);

		// 動画データ登録
		$video = $model->save(null, false);
		if (!$video) {
			throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
		}

		return true;
	}

/**
 * 動画変換
 *
 * @param Model $model モデル
 * @param array $data received post data
 * @param array $video Video
 * @param array $noConvert File
 * @return mixed Array on success, false on error
 * @throws InternalErrorException
 */
	private function __convertVideo(Model $model, $data, $video, $noConvert) {
		// --- 動画変換

		// アップロードファイルの受け取りと移動
//		$noConvertPath = $noConvert['File']["path"];
//		$noConvertSlug = $noConvert['File']["slug"];
//		$noConvertExtension = $noConvert['File']["extension"];

		$noConvertPath = $model->data['Video'][Video::VIDEO_FILE_FIELD]['tmp_name'];
		//$noConvertSlug = $noConvert['File']["slug"];
		$noConvertMimeType = $model->data['Video'][Video::VIDEO_FILE_FIELD]['type'];

		// サムネイル名は動画名で末尾jpgにしたものをセット
		$videoName = explode('.', $noConvert['File']["name"])[0];

		// アップロード済みのvideoFileの入力値を、$dataから除外
		unset($data[$model->alias]['videoFile']);

		// mp4は変換しない
//		if ($noConvertExtension != "mp4") {
		if ($noConvertMimeType != "video/mp4") {

			// 例）ffmpeg -y -i /var/www/html/movies/original/MOV_test_movie.MOV -acodec libmp3lame -ab 128k -ar 44100 -ac 2 -vcodec libx264 -r 30 -b 500k MOV_test_movie.mp4
			// 動画変換
			// 動画変換実施(元動画 > H.264)  コマンドインジェクション対策
//			$strCmd = Video::FFMPEG_PATH . ' -y -i ' . escapeshellarg($noConvertPath . $noConvertSlug . '.' . $noConvertExtension) . ' ' . Video::FFMPEG_OPTION . " " . escapeshellarg($noConvertPath . $noConvertSlug . '.mp4') . ' 2>&1';
			$strCmd = Video::FFMPEG_PATH . ' -y -i ' . escapeshellarg($noConvertPath) . ' ' . Video::FFMPEG_OPTION . " " . escapeshellarg($noConvertPath . $noConvertSlug . '.mp4') . ' 2>&1';
			exec($strCmd, $arr, $ret);

			// 変換エラー時
			if ($ret != 0) {
				$this->log("--- ffmpeg H.264 変換エラー", 'debug');
				$this->log($strCmd, 'debug');
				$this->log($arr, 'debug');
				$this->log($ret, 'debug');
				return false;
			}

			// Filesテーブルに変換後動画を登録。Delete->Insert
			$data[Video::VIDEO_FILE_FIELD]['File']['type'] = 'video/mp4';
			$data[Video::VIDEO_FILE_FIELD]['File']['mimetype'] = 'video/mp4';
			$data[Video::VIDEO_FILE_FIELD]['File']['path'] = '{ROOT}' . 'videos' . '{DS}' . Current::read('Room.id') . '{DS}' . $video['Video']['id'] . '{DS}';
			$data[Video::VIDEO_FILE_FIELD]['File']['name'] = $videoName . '.mp4';
			$data[Video::VIDEO_FILE_FIELD]['File']['alt'] = $videoName . '.mp4';
			$data[Video::VIDEO_FILE_FIELD]['File']['extension'] = 'mp4';
			$data[Video::VIDEO_FILE_FIELD]['File']['tmp_name'] = $noConvertPath . $noConvertSlug . '.mp4';
			$data[Video::VIDEO_FILE_FIELD]['File']['size'] = filesize($noConvertPath . $noConvertSlug . '.mp4');

			// ファイルチェック 変換後動画ファイル
			if (!$data = $model->validateVideoFile($data, Video::VIDEO_FILE_FIELD, $model->alias, 'mp4_id', 0)) {
				$this->log($model->validationErrors, 'debug');
				return false;
			}

			// ファイルの登録 変換後動画ファイル
			$data = $model->saveVideoFile($data, Video::VIDEO_FILE_FIELD, $model->alias, 'mp4_id', 0);

			// 元動画 ファイルのみ削除
			$file = new File($noConvertPath . $noConvertSlug . '.' . $noConvertExtension);
			$file->delete();
		}

		return $data;
	}

/**
 * 再生時間を取得
 *
 * @param array $noConvert File data
 * @return mixed int on success, false on error
 */
	private function __getVideoTime($noConvert) {
		// 元動画
		$noConvertPath = $noConvert['File']["path"];
		$noConvertSlug = $noConvert['File']["slug"];

		// 変換後の動画情報を取得 コマンドインジェクション対策
		// ffmpeg -i の $retInfo はファイルがあってもなくても1(失敗)なので、エラー時処理は省く
		$strCmd = Video::FFMPEG_PATH . " -i " . escapeshellarg($noConvertPath . $noConvertSlug . '.mp4') . " 2>&1";
		exec($strCmd, $arrInfo);

		//動画情報から時間を取得
		$videoTimeSec = 0;
		foreach ($arrInfo as $line) {
			//時間を取得(フォーマット：Duration: 00:00:00.0)
			preg_match("/Duration: [0-9]{2}:[0-9]{2}:[0-9]{2}\.\d+/s", $line, $matches);

			//時間を取得出来た場合
			if (count($matches) > 0) {
				//「:」で文字列分割
				$resultLine = explode(':', $matches[0]);

				//動画の時間を計算
				$videoTimeSec = intval(trim($resultLine[1])) * 3600 + intval($resultLine[2]) * 60 + $resultLine[3];
				break;
			}
		}

		return $videoTimeSec;
	}

/**
 * サムネイル自動作成
 *
 * @param array $data received post data
 * @param string $pluginKey plugin key
 * @param array $noConvert File data
 * @return mixed Array on success, false on error
 * @throws InternalErrorException
 */
	private function __generateThumbnail($data, $pluginKey, $noConvert) {
		// 元動画
		$noConvertPath = $noConvert['File']["path"];
		$noConvertSlug = $noConvert['File']["slug"];
		$videoName = explode('.', $noConvert['File']["name"])[0];

		// --- サムネイル自動作成
		// 動画変換実施(元動画 > サムネイル)
		$thumbnailSlug = Security::hash(
			$noConvertPath . $noConvertSlug . '.mp4' . mt_rand() . microtime(), 'md5'
		);

		// 例) ffmpeg -ss 1 -vframes 1 -i /var/www/html/movies/play/20130901_072755.mp4 -f image2 /var/www/html/movies/play/20130901_072755.jpg
		// サムネイルは変換後のmp4 から生成する。mts からサムネイルを生成した場合、灰色画像になりうまく生成できなかった。ファイル形式によりサムネイル生成に制限がある可能性があるため。
		// コマンドインジェクション対策
		$strCmd = Video::FFMPEG_PATH . ' -ss 1 -vframes 1 -i ' . escapeshellarg($noConvertPath . $noConvertSlug . ".mp4") . ' -f image2 ' . escapeshellarg($noConvertPath . $thumbnailSlug . '.jpg');
		exec($strCmd, $arrImage, $retImage);

		// 変換エラー時
		if ($retImage != 0) {
			$this->log("--- ffmpeg サムネイル 生成エラー", 'debug');
			$this->log($strCmd, 'debug');
			$this->log($arrImage, 'debug');
			$this->log($retImage, 'debug');
			// return はしない。

		} else {
			// サムネイルデータ準備
			$data['Video'][Video::THUMBNAIL_FIELD]['name'] = $videoName . '.jpg';	// サムネイル名は動画名で末尾jpgにしたものをセット
			$data['Video'][Video::THUMBNAIL_FIELD]['type'] = 'image/jpeg';
			$data['Video'][Video::THUMBNAIL_FIELD]['tmp_name'] = $noConvertPath . $thumbnailSlug . '.jpg';
			$data['Video'][Video::THUMBNAIL_FIELD]['error'] = UPLOAD_ERR_OK;
			$data['Video'][Video::THUMBNAIL_FIELD]['size'] = filesize($noConvertPath . $thumbnailSlug . '.jpg');

			// Filesテーブルにサムネイルを登録
			$data[Video::THUMBNAIL_FIELD]['File']['status'] = 1;
			$data[Video::THUMBNAIL_FIELD]['File']['role_type'] = 'room_file_role';
			$data[Video::THUMBNAIL_FIELD]['File']['name'] = $videoName . '.jpg';		// サムネイル名は動画名をjpgにしたものをセット
			$data[Video::THUMBNAIL_FIELD]['File']['alt'] = $videoName . '.jpg';
			$data[Video::THUMBNAIL_FIELD]['File']['mimetype'] = 'image/jpeg';
			$data[Video::THUMBNAIL_FIELD]['File']['path'] = '{ROOT}' . 'videos' . '{DS}' . Current::read('Room.id') . '{DS}';		// 自動的に $video['Video']['id'] . '{DS}' が末尾に追記されるので、ここでは追記しない
			$data[Video::THUMBNAIL_FIELD]['File']['extension'] = 'jpg';
			$data[Video::THUMBNAIL_FIELD]['File']['tmp_name'] = $noConvertPath . $thumbnailSlug . '.jpg';
			$data[Video::THUMBNAIL_FIELD]['File']['size'] = filesize($noConvertPath . $thumbnailSlug . '.jpg');
			$data[Video::THUMBNAIL_FIELD]['File']['slug'] = $thumbnailSlug;
			$data[Video::THUMBNAIL_FIELD]['File']['original_name'] = $thumbnailSlug;

			$data[Video::THUMBNAIL_FIELD]['FilesPlugin']['plugin_key'] = $pluginKey;	// plugin_keyは、元動画のをセット
			$data[Video::THUMBNAIL_FIELD]['FilesRoom']['room_id'] = Current::read('Room.id');
			$data[Video::THUMBNAIL_FIELD]['FilesUser']['user_id'] = AuthComponent::user('id');
		}

		return $data;
	}

/**
 * 秒を時：分：秒に変更 (表示用)
 *
 * @param Model $model モデル
 * @param int $totalSec 秒
 * @return string 時：分：秒
 */
	public function convSecToHour(Model $model, $totalSec) {
		$sec = $totalSec % 60;
		$min = (int)($totalSec / 60) % 60;
		$hour = (int)($totalSec / (60 * 60));
		if ($hour > 0) {
			return sprintf("%d:%02d:%02d", $hour, $min, $sec);
		}
		return sprintf("%d:%02d", $min, $sec);
	}

/**
 * 秒を時：分：秒に変更 (編集用)
 *
 * @param Model $model モデル
 * @param int $totalSec 秒
 * @return string 時：分：秒
 */
	//	public function convSecToHourEdit(Model $model, $totalSec) {
	//		$sec = $totalSec % 60;
	//		$min = (int)($totalSec / 60) % 60;
	//		$hour = (int)($totalSec / (60 * 60));
	//		return sprintf("%02d:%02d:%02d", $hour, $min, $sec);
	//	}
}