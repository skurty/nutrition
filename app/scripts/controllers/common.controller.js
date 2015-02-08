'use strict';

angular.module('nutritionApp.commonControllers', []).
  controller('NavCtrl', ['$scope', '$location', function($scope, $location) {
    $scope.navClass = function(page) {
      var currentRoute = $location.path().substring(1) || 'diaries';
      var routeArray = currentRoute.split('/');
      return (page === routeArray[0] || page === routeArray[routeArray.length - 1]) ? 'active' : '';
    };
  }]);
