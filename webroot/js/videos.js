/**
 * @fileoverview Videos Javascript
 * @author mutaguchi@opensource-workshop.jp (Mitsuru Mutaguchi)
 */


/**
 * Videos Javascript
 *
 * @param {string} Controller name
 * @param {function($scope, NetCommonsTab, NetCommonsWorkflow)} Controller
 */
NetCommonsApp.controller('Videos',
    function($scope, NetCommonsTab, NetCommonsWorkflow) {

      /**
       * tab
       *
       * @type {object}
       */
      $scope.tab = NetCommonsTab.new();

      /**
       * workflow
       *
       * @type {object}
       */
      $scope.workflow = NetCommonsWorkflow.new($scope);

      /**
       * Initialize
       *
       * @return {void}
       */
      $scope.initialize = function(video) {
        $scope.video = angular.copy(video);
      };
    });


/**
 * 動画詳細 Javascript
 *
 * @param {string} Controller name
 * @param {function($scope)} Controller
 */
NetCommonsApp.controller('VideoView',
    function($scope) {

      /**
       * 埋め込みコード
       *
       * @return {void}
       */
      $scope.embed = function() {
        // jquery 表示・非表示
        $('div.video-embed').toggle('normal');
        // 表示後埋め込みコード選択
        $('input.video-embed-text').select();
      };
    });


/**
 * 関連動画 Javascript
 *
 * @param {string} Controller name
 * @param {function($scope)} Controller
 */
NetCommonsApp.controller('RelatedVideos',
    function($scope) {

      /**
       * もっと見る
       *
       * @return {void}
       */
      $scope.more = function() {
        $('div.related-video:hidden').removeClass('hidden');
        $('button.related-video-more').hide(0);
      };
    });


/**
 * VideoFrameSettings Javascript
 *
 * @param {string} Controller name
 * @param {function($scope, NetCommonsTab)} Controller
 */
NetCommonsApp.controller('VideoFrameSettings',
    function($scope, NetCommonsTab) {

      /**
       * tab
       *
       * @type {object}
       */
      $scope.tab = NetCommonsTab.new();

      /**
       * Initialize
       *
       * @return {void}
       */
      $scope.initialize = function(videoFrameSetting) {
        $scope.videoFrameSetting = angular.copy(videoFrameSetting);
      };

    });


/**
 * VideoBlockSettings コンテンツ Javascript
 *
 * @param {string} Controller name
 * @param {function($scope, NetCommonsTab)} Controller
 */
NetCommonsApp.controller('VideoBlockSettingsEdit',
    function($scope, NetCommonsTab) {

      /**
       * tab
       *
       * @type {object}
       */
      $scope.tab = NetCommonsTab.new();

      /**
       * Initialize
       *
       * @return {void}
       */
      $scope.initialize = function(videoBlockSetting, block) {
        $scope.videoBlockSetting = angular.copy(videoBlockSetting);
        $scope.block = angular.copy(block);
      };

    });

