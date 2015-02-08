'use strict';

angular.module('nutritionApp.brandsControllers', []).
  controller('BrandAddCtrl', ['$scope', '$location', 'Brand', function($scope, $location, Brand) {
    $scope.lockAddBrand = false;

    $scope.create = function() {
      if (!$scope.lockAddBrand) {
        $scope.lockAddBrand = true;
        if (!angular.isUndefined($scope.brand) && !angular.isUndefined($scope.brand.name)) {
          Brand.post({name: $scope.brand.name}, function(data) {
            // if (data == 1) {
              $scope.lockAddBrand = false;
              history.back()
              // $location.path('/foods');
            // }
          });
        }
      }
    }
  }]);
