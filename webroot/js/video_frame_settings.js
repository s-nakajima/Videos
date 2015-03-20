/**
 * @fileoverview VideoFrameSettings Javascript
 * @author mutaguchi@opensource-workshop.jp (Mitsuru Mutaguchi)
 */


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
      $scope.videos = angular.copy(data.videos);
    };
  });
