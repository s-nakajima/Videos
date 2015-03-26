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
    $scope.initialize = function(data) {
      $scope.videos = angular.copy(data.videos);
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
    $scope.initialize = function(data) {
      $scope.video_frame_settings = angular.copy(data);
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
    $scope.initialize = function(data) {
      $scope.video_frame_setting = angular.copy(data);

      // authorityの定数。。。(;'∀')
      // 動画投稿権限
      if ($scope.video_frame_setting.authority <= 2) {
        // 編集者、一般=ON
        $scope.video_frame_setting.authority_editor = true;
        $scope.video_frame_setting.authority_general = true;
      } else if ($scope.video_frame_setting.authority <= 3) {
        // 編集者=ON
        $scope.video_frame_setting.authority_editor = true;
      }
    };

    /**
     * 編集者チェックボックスの値変更時
     *
     * @return {void}
     */
    $scope.changeEditor = function() {
      // 編集者=OFF
      if ($scope.video_frame_setting.authority_editor == false) {
        // 一般=OFF
        $scope.video_frame_setting.authority_general = false;
      }
    };

    /**
     * 一般チェックボックスの値変更時
     *
     * @return {void}
     */
    $scope.changeGeneral = function() {
      // 一般=ON
      if ($scope.video_frame_setting.authority_general == true) {
        // 編集者=ON
        $scope.video_frame_setting.authority_editor = true;
      }
    };

  });

/**
 * VideoFrameSettings コンテンツ Javascript
 *
 * @param {string} Controller name
 * @param {function($scope, NetCommonsTab)} Controller
 */
NetCommonsApp.controller('VideoFrameSettingsContent',
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
    $scope.initialize = function(video_frame_setting, block) {
      $scope.video_frame_setting = angular.copy(video_frame_setting);
      $scope.block = angular.copy(block);
    };

  });

