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
 * @param array $video Video
 * @return bool true on success, false on error
 * @throws InternalErrorException
 */
	public function saveConvertVideo(Model $model, $video) {
		// 元動画 取得
		//$noConvert = $model->FileModel->findById($video['Video']['mp4_id']);
		$UploadFile = ClassRegistry::init('Files.UploadFile');
		$noConvert = $UploadFile->getFile('videos', $video['Video']['id'], Video::VIDEO_FILE_FIELD);

		// --- 動画変換
		if (! $this->__convertVideo($model, $video, $noConvert)) {
			//$model->deleteFile($data, $model->alias, 'mp4_id', 0);	//元動画 削除
			$UploadFile->removeFile($video['Video']['id'], Video::VIDEO_FILE_FIELD);	//元動画 削除
			$this->log('VideoBehavior::saveConvertVideo() -> __convertVideo() false', 'debug');
			return false;
		}

		// 変換後動画 取得
		$convert = $UploadFile->getFile('videos', $video['Video']['id'], Video::VIDEO_FILE_FIELD);

		// --- サムネイル自動作成
		$this->__generateThumbnail($model, $video, $convert);

		// --- 再生時間を取得
		$videoTimeSec = $this->__getVideoTime($convert);

		// --- 動画時間のみ更新
		// 値をセット
		// $model->set($video);
		$model->read(null, $video['Video']['id']);
		$model->set('video_time', $videoTimeSec);

		// 動画データ登録
		if (! $model->save(null, false)) {
			throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
		}

		return true;
	}

/**
 * 動画変換
 *
 * @param Model $model モデル
 * @param array $video Video
 * @param array $noConvert File
 * @return mixed Array on success, false on error
 * @throws InternalErrorException
 */
	private function __convertVideo(Model $model, $video, $noConvert) {
		$noConvertExtension = $noConvert['UploadFile']["extension"];

		// mp4は変換しない
		//if ($noConvertMimeType == "video/mp4") {
		if ($noConvertExtension == "mp4") {
			return true;
		}

		// --- 動画変換
		//		$noConvertMimeType = $data['Video'][Video::VIDEO_FILE_FIELD]['type'];
		$noConvertPath = APP . WEBROOT_DIR . DS . $noConvert['UploadFile']['path'] . $noConvert['UploadFile']['id'] . DS;
		$realFileName = $noConvert['UploadFile']["real_file_name"];

		// サムネイル名は動画名で末尾jpgにしたものをセット
		$videoName = explode('.', $realFileName)[0];

		// 例）ffmpeg -y -i /var/www/html/movies/original/MOV_test_movie.MOV -acodec libmp3lame -ab 128k -ar 44100 -ac 2 -vcodec libx264 -r 30 -b 500k MOV_test_movie.mp4
		// 例）/usr/bin/ffmpeg -y -i '/var/www/app/app/webroot/files/upload_file/real_file_name/1/21/bd14317ad1b299f9074b532116c89da8.MOV' -acodec libmp3lame -ab 128k -ar 44100 -ac 2 -vcodec libx264 -r 30 -b 500k '/var/www/app/app/webroot/files/upload_file/real_file_name/1/21/bd14317ad1b299f9074b532116c89da8.mp4' 2>&1
		// 動画変換
		// 動画変換実施(元動画 > H.264)  コマンドインジェクション対策
		//$strCmd = Video::FFMPEG_PATH . ' -y -i ' . escapeshellarg($noConvertPath . $noConvertSlug . '.' . $noConvertExtension) . ' ' . Video::FFMPEG_OPTION . " " . escapeshellarg($noConvertPath . $noConvertSlug . '.mp4') . ' 2>&1';
		$strCmd = Video::FFMPEG_PATH . ' -y -i ' . escapeshellarg($noConvertPath . $realFileName) . ' ' . Video::FFMPEG_OPTION . " " . escapeshellarg($noConvertPath . $videoName . '.mp4') . ' 2>&1';
		exec($strCmd, $arr, $ret);

		// 変換エラー時
		if ($ret != 0) {
			$this->log("--- ffmpeg H.264 変換エラー", 'debug');
			$this->log($strCmd, 'debug');
			$this->log($arr, 'debug');
			$this->log($ret, 'debug');
			return false;
		}

		//変換動画のファイル保存
		// https://github.com/NetCommons3/Blogs/blob/feature/withFilesTest/Controller/BlogEntriesEditController.php#L234 あたり Blogsのfeature/withFilesTestブランチ参考
		$model->attachFile($video, Video::VIDEO_FILE_FIELD, $noConvertPath . $videoName . '.mp4');

		//			// 元動画 ファイルのみ削除
		//			$file = new File($noConvertPath . $noConvertSlug . '.' . $noConvertExtension);
		//			$file->delete();

		return true;
	}

/**
 * 再生時間を取得
 *
 * @param array $convert 動画変換後ファイルデータ
 * @return mixed int on success, false on error
 */
	private function __getVideoTime($convert) {
		// 元動画
		$noConvertPath = APP . WEBROOT_DIR . DS . $convert['UploadFile']['path'] . $convert['UploadFile']['id'] . DS;
		$realFileName = $convert['UploadFile']["real_file_name"];
		$videoName = explode('.', $realFileName)[0];

		// 変換後の動画情報を取得 コマンドインジェクション対策
		// ffmpeg -i の $retInfo はファイルがあってもなくても1(失敗)なので、エラー時処理は省く
		//$strCmd = Video::FFMPEG_PATH . " -i " . escapeshellarg($noConvertPath . $noConvertSlug . '.mp4') . " 2>&1";
		$strCmd = Video::FFMPEG_PATH . " -i " . escapeshellarg($noConvertPath . $videoName . '.mp4') . " 2>&1";
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
 * @param Model $model モデル
 * @param array $video Video
 * @param array $convert 動画変換後ファイルデータ
 * @return mixed Array on success, false on error
 * @throws InternalErrorException
 */
	private function __generateThumbnail(Model $model, $video, $convert) {
		// 元動画
		$convertPath = APP . WEBROOT_DIR . DS . $convert['UploadFile']['path'] . $convert['UploadFile']['id'] . DS;
		$realFileName = $convert['UploadFile']["real_file_name"];
		$videoName = explode('.', $realFileName)[0];


		// --- サムネイル自動作成
		// 例) ffmpeg -ss 1 -vframes 1 -i /var/www/html/movies/play/20130901_072755.mp4 -f image2 /var/www/html/movies/play/20130901_072755.jpg
		// サムネイルは変換後のmp4 から生成する。mts からサムネイルを生成した場合、灰色画像になりうまく生成できなかった。ファイル形式によりサムネイル生成に制限がある可能性があるため。
		// コマンドインジェクション対策
		$strCmd = Video::FFMPEG_PATH . ' -ss 1 -vframes 1 -i ' . escapeshellarg($convertPath . $videoName . ".mp4") . ' -f image2 ' . escapeshellarg($convertPath . $videoName . '.jpg');
		exec($strCmd, $arrImage, $retImage);

		// 変換エラー時
		if ($retImage != 0) {
			$this->log("--- ffmpeg サムネイル 生成エラー", 'debug');
			$this->log($strCmd, 'debug');
			$this->log($arrImage, 'debug');
			$this->log($retImage, 'debug');
			// return はしない。
		} else {
			// サムネイルのファイル保存
			$model->attachFile($video, Video::THUMBNAIL_FIELD, $convertPath . $videoName . '.jpg');
		}

		return true;
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
}