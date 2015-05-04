<?php
/**
 * 詳細 template
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<?php echo $this->Html->css('/videos/jplayer/dist/skin/blue.monday/css/jplayer.blue.monday.min.css'); ?>
<?php echo $this->Html->css('/videos/css/style.css', false); ?>
<?php echo $this->Html->script('/videos/jplayer/dist/jplayer/jquery.jplayer.min.js', false); ?>
<?php echo $this->Html->script('/videos/js/videos.js', false); ?>

<?php /* jPlayer設定 */ ?>
<script type="text/javascript">
	$(document).ready(function(){
		$("#jquery_jplayer_1").jPlayer({
			ready: function () {
				$(this).jPlayer("setMedia", {
					//title: "Big Buck Bunny Trailer",
					//m4v: "http://www.jplayer.org/video/m4v/Big_Buck_Bunny_Trailer.m4v",
					//ogv: "http://www.jplayer.org/video/ogv/Big_Buck_Bunny_Trailer.ogv",
					//poster: "http://www.jplayer.org/video/poster/Big_Buck_Bunny_Trailer_480x270.png"
					//title: "<?php //echo $video['video']['title']; ?>",
					m4v: "<?php echo $fileMp4Url = isset($video['fileMp4']['url']) ? $this->Html->url($video['fileMp4']['url']) : ''; ?>",
					ogv: "<?php echo $fileOggUrl = isset($video['fileOgg']['url']) ? $this->Html->url($video['fileOgg']['url']) : ''; ?>",
					poster: "<?php echo $fileThumbnailUrlMedium = isset($video['fileThumbnail']['urlMedium']) ? $this->Html->url($video['fileThumbnail']['urlMedium']) : ''; ?>",
				});
			},
			cssSelectorAncestor: "#jp_container_1",
			swfPath: "/js",
			supplied: "m4v, ogv",
			useStateClassSkin: true,
			autoBlur: false,
			smoothPlayBar: true,
			keyEnabled: true,
			remainingDuration: true,
			toggleDuration: true,
			size: {
				width: "640px",
				height: "360px",
				cssClass: "jp-video-360p"
			}
		});
	});
</script>

<?php /* 上部ボタン */ ?>
<p>
<div class="row">
	<div class="col-xs-12 text-right">
		<?php if ($contentEditable): ?>
			<span class="nc-tooltip" tooltip="<?php echo __d('net_commons', 'Edit'); ?>">
				<a href="<?php echo $this->Html->url('/videos/videos/edit/' . $frameId . '/' . $video['video']['key']) ?>" class="btn btn-primary">
					<span class="glyphicon glyphicon-edit"> </span>
				</a>
			</span>
		<?php endif; ?>
	</div>
</div>
</p>

<p>
<div class="row">
	<?php /* 左側 */ ?>
	<div class="col-lg-7 col-xs-12">
		<div style="padding-bottom: 20px;">
			<?php /* jPlayer */ ?>
			<div id="jp_container_1" class="jp-video" role="application" aria-label="media player">
				<div class="jp-type-single">
					<div id="jquery_jplayer_1" class="jp-jplayer"></div>
					<div class="jp-gui">
						<div class="jp-video-play">
							<button class="jp-video-play-icon" role="button" tabindex="0">play</button>
						</div>
						<div class="jp-interface">
							<div class="jp-progress">
								<div class="jp-seek-bar">
									<div class="jp-play-bar"></div>
								</div>
							</div>
							<div class="jp-current-time" role="timer" aria-label="time">&nbsp;</div>
							<div class="jp-duration" role="timer" aria-label="duration">&nbsp;</div>
							<div class="jp-controls-holder">
								<div class="jp-controls">
									<button class="jp-play" role="button" tabindex="0">play</button>
									<button class="jp-stop" role="button" tabindex="0">stop</button>
								</div>
								<div class="jp-volume-controls">
									<button class="jp-mute" role="button" tabindex="0">mute</button>
									<button class="jp-volume-max" role="button" tabindex="0">max volume</button>
									<div class="jp-volume-bar">
										<div class="jp-volume-bar-value"></div>
									</div>
								</div>
								<div class="jp-toggles">
									<button class="jp-repeat" role="button" tabindex="0">repeat</button>
									<button class="jp-full-screen" role="button" tabindex="0">full screen</button>
								</div>
							</div>
							<!-- <div class="jp-details">
								<div class="jp-title" aria-label="title">&nbsp;</div>
							</div> -->
						</div>
					</div>
					<div class="jp-no-solution">
						<span>Update Required</span>
						To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.
					</div>
				</div>
			</div>
		</div>

		<div class="panel panel-default video-description">
			<div>
				<?php /* タイトル */ ?>
				<h4><?php echo $video['video']['title']; ?></h4>
			</div>
			<p>
			<div>
				<?php /* ステータス */ ?>
				<?php echo $this->element('NetCommons.status_label', array(
					'status' => $video['video']['status']
				)); ?>
			</div>
			</p>
			<div class="row">
				<div class="col-xs-2">
					<?php /* アバター */ ?>
					<a href="#">
						<?php echo $this->Html->image('/videos/img/avatar.png', array(
							//'class' => 'img-responsive',
							'alt' => $video['userAttributesUser']['value'],
							'align' => 'left',
							'width' => '60',
							'height' => '60',
						)); ?>
					</a>
				</div>
				<div class="col-xs-10">
					<div class="row">
						<div class="col-xs-6">
							<?php /* 投稿者 */ ?>
							<span style="padding-left: 5px; padding-right: 15px;"><a href="#"><?php echo $video['userAttributesUser']['value'] ?></a><br />
						</div>
						<div class="col-xs-6 text-right" style="font-size: 18px;">
							<?php /* 再生回数 */ ?>
							<?php echo sprintf(__d('videos', '再生回数 %s回'), $video['video']['playNumber']); ?>
						</div>
					</div>

					<div class="row">
						<div class="col-xs-12 text-right">
							<span style="padding-right: 15px;">
								<?php /* 埋め込みコード */ ?>
								<a href="#"><?php echo __d('videos', '埋め込みコード'); ?></a>
							</span>

							<?php /* いいね */ ?>
							<?php if ($videoBlockSetting['useLike']) : ?>
								<span class="text-left">
									<?php /* 高く評価、暫定対応(;'∀') */ ?>
									<?php /* コンテンツが読めたらいいね、よくないね出来る */ ?>
									<?php if ($contentReadable): ?>
										<a href="#"><span class="glyphicon glyphicon-thumbs-up" style="padding-right: 3px;"></span><?php //echo $video['video']['likesNumber']; ?>0</a>
									<?php else : ?>
										<span class="glyphicon glyphicon-thumbs-up" style="padding-right: 3px;"><?php //echo $video['video']['likesNumber']; ?>0</span>
									<?php endif; ?>
								</span>

								<?php /* よくないね */ ?>
								<?php if ($videoBlockSetting['useUnlike']) : ?>
									&nbsp;
									<span class="text-left">
										<?php /* コンテンツが読めたらいいね、よくないね出来る */ ?>
										<?php if ($contentReadable): ?>
											<a href="#"><span class="glyphicon glyphicon-thumbs-down" style="padding-right: 3px;"></span><?php //echo $video['video']['unlikesNumber']; ?>0</a>
										<?php else : ?>
											<span class="glyphicon glyphicon-thumbs-down" style="padding-right: 3px;"></span><?php //echo $video['video']['unlikesNumber']; ?>0
										<?php endif; ?>
									</span>
								<?php endif; ?>
							<?php endif; ?>
							&nbsp;
						</div>
					</div>
				</div>
				<div class="clearfix"></div>
			</div>
			<div>
				<?php /* 登録日 */ ?>
				<strong><?php echo __d('videos', '登録日') . '：' . $this->Date->dateFormat($video['video']['created']); ?></strong>
			</div>
			<div>
				<?php /* 本文 */ ?>
				<?php echo $video['video']['description']; ?>
			</div>
		</div>

		<?php /* コンテンツコメント */ ?>
		<div class="panel panel-default">
			<?php echo $this->element('ContentComments.form', array(
				'formName' => 'Video',
			)); ?>
			<?php echo $this->element('ContentComments.index', array(
				'formName' => 'Video',
			)); ?>
		</div>
	</div>

	<?php /* 右側 */ ?>
	<div class="col-lg-5 col-xs-0">

		<?php /* 関連動画 */ ?>
		<p>
		<div class="row">
			<?php foreach ($relatedVideos as $relatedVideo) : ?>
				<div class="col-xs-12">
					<div style="border: 1px solid #ddd; padding: 5px; margin-bottom: 5px;">
						<?php /* サムネイル */ ?>
						<div class="row videos-row-height">
							<div class="col-xs-4">
								<div>
									<div>
										<a href="<?php echo $this->Html->url('/videos/videos/view/' . $frameId . '/' . $relatedVideo['video']['key']); ?>">
											<?php if (isset($relatedVideo['fileThumbnail']['urlThumbnail'])) : ?>
												<?php echo $this->Html->image($relatedVideo['fileThumbnail']['urlThumbnail'], array('alt' => $relatedVideo['video']['title'])); ?>
											<?php else : ?>
												<?php /* サムネイルなし */ ?>
												<?php echo $this->Html->image('/videos/img/noImage.png', array('alt' => $relatedVideo['video']['title'])); ?>
											<?php endif; ?>
										</a>
									</div>
									<div style="margin-top: -18px; margin-left: 65px;">
										<?php
										$videoTime = $relatedVideo['video']['videoTime'];
										$videoTime = floor($videoTime / 60) . ":" . str_pad(floor($videoTime - 60 * floor($videoTime / 60)), 2, '0');
										?>
										<span style="background-color: #000; color: #FFF; font-weight: bold; font-size: 11px; opacity: 0.75; padding: 0px 7px;">
									<?php echo $videoTime ?>
									</span>
									</div>
								</div>
							</div>
							<?php /* タイトル、投稿者、各種回数 */ ?>
							<div class="col-xs-8">
								<small>
									<div>
										<a href="<?php echo $this->Html->url('/videos/videos/view/' . $frameId . '/' . $relatedVideo['video']['key']); ?>">
											<strong><?php echo $this->Text->Truncate($relatedVideo['video']['title'], VIDEO::SHORT_TITLE_LENGTH); ?></strong>
										</a>
									</div>
									<span style="padding-right: 15px;"><?php echo __d('videos', '投稿者'); ?></span><a href="#"><?php echo $relatedVideo['userAttributesUser']['value'] ?></a><br />
									<span style="padding-right: 15px;">
										<span class="glyphicon glyphicon-play" aria-hidden="true"></span> <?php echo $video['video']['playNumber'] ?>
									</span>
									<?php /* コメント数、暫定対応(;'∀') */ ?>
									<span class="glyphicon glyphicon-comment" aria-hidden="true"></span> <?php //echo $relatedVideo[0]['commentsNumber'] ?>0<br />

									<?php if ($videoBlockSetting['useLike']) : ?>
										<?php /* 高く評価、暫定対応(;'∀') */ ?>
										<span style="padding-right: 15px;">
											<span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span> <?php //echo $relatedVideo['video']['likesNumber'] ?>0
										</span>
										<?php if ($videoBlockSetting['useUnlike']) : ?>
											<?php /* 低く評価、暫定対応(;'∀') */ ?>
											<span class="glyphicon glyphicon-thumbs-down" aria-hidden="true"></span> <?php //echo $relatedVideo['video']['unlikesNumber'] ?>0
										<?php endif; ?>
									<?php endif; ?>
								</small>
							</div>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		</p>

	</div>

</div>
</p>

<?php /* 下部ボタン */ ?>
<p>
<div class="row">
	<div class="col-xs-12 text-center">
		<a href="<?php echo $this->Html->url('/videos/videos/index/' . $frameId) ?>" class="btn btn-default">
			<?php echo __d("videos", "一覧へ戻る") ?>
		</a>
	</div>
	<div class="col-xs-6 text-right">
	</div>
</div>
</p>
