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
 * VideoFrameSettings 権限設定 Javascript
 *
 * @param {string} Controller name
 * @param {function($scope, NetCommonsTab)} Controller
 */
NetCommonsApp.controller('VideoFrameSettingsAuthority',
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

        // authorityの定数。。。(;'∀')
        // 動画投稿権限
        if ($scope.videoFrameSetting.authority <= 2) {
          // 編集者、一般=ON
          $scope.videoFrameSetting.authorityEditor = true;
          $scope.videoFrameSetting.authorityGeneral = true;
        } else if ($scope.videoFrameSetting.authority <= 3) {
          // 編集者=ON
          $scope.videoFrameSetting.authorityEditor = true;
        }
      };

      /**
       * 編集者チェックボックスの値変更時
       *
       * @return {void}
       */
      $scope.changeEditor = function() {
        // 編集者=OFF
        if ($scope.videoFrameSetting.authorityEditor == false) {
          // 一般=OFF
          $scope.videoFrameSetting.authorityGeneral = false;
        }
      };

      /**
       * 一般チェックボックスの値変更時
       *
       * @return {void}
       */
      $scope.changeGeneral = function() {
        // 一般=ON
        if ($scope.videoFrameSetting.authorityGeneral == true) {
          // 編集者=ON
          $scope.videoFrameSetting.authorityEditor = true;
        }
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

