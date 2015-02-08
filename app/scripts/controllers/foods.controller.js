'use strict';

angular.module('nutritionApp.foodsControllers', []).
  controller('FoodListCtrl', ['$scope', 'Food', function($scope, Food) {
    Food.list(function(data) {
      $scope.foods = data;
    });

    $scope.sort   = 'name';
    $scope.reverse = false;

    $scope.delete = function(id, event) {
      if (event) event.preventDefault();

      Food.delete({id: id}, function(data) {
        if (data[0] == 1) {
          angular.forEach($scope.foods, function(f, k) {
            if (f.id == id) {
              $scope.foods.splice(k, 1);
            }
          });
        }
      });

      return false;
    }

    $scope.changeSorting = function(column) {
      if ($scope.sort == column) {
        $scope.reverse = !$scope.reverse;
      } else {
        $scope.sort    = column;
        $scope.reverse = false;
      }
    };
  }]).
  controller('FoodAddCtrl', ['$scope', '$location', 'Brand', 'Food', 'String', function($scope, $location, Brand, Food, String) {
    $scope.lockAddFood = false;

    $scope.foods = null;

    $scope.showLoading = false;

    Brand.list(function(data) {
      $scope.brands = data;
    });

    $scope.create = function() {
      if (!$scope.lockAddFood) {
        $scope.lockAddFood = true;

        if (!angular.isUndefined($scope.food) && !angular.isUndefined($scope.food.name) &&
          !angular.isUndefined($scope.food.quantity) && !angular.isUndefined($scope.food.unit) &&
          !angular.isUndefined($scope.food.calories) && !angular.isUndefined($scope.food.proteins) &&
          !angular.isUndefined($scope.food.carbohydrates) && !angular.isUndefined($scope.food.lipids)) {
          Food.post({food: $scope.food}, function(data) {
            // if (data == 1) {
              $scope.lockAddFood = false;
              $location.path('/foods');
            // }
          });
        }
      }
    };

    $scope.search = function() {
      $scope.emptyFoodsResults = false;

      if (!angular.isUndefined($scope.searchName)) {
        $scope.showLoading = true;

        Food.search({search: String.removeAccents($scope.searchName)}, function(data) {
          $scope.showLoading = false;

          if (data.foods.length > 0) {
            $scope.foods = data.foods;
          } else {
            $scope.emptyFoodsResults = true;
          }
        });
      }
    }

    $scope.select = function(key) {
      $scope.food = $scope.foods[key];
      $scope.foods = null;
    };

    $scope.hideFoodsResults = function(event) {
      if (event) event.preventDefault();

      $scope.foods = null;

      return false;
    };
  }]).

  controller('FoodEditCtrl', ['$scope', '$routeParams', '$location', 'Brand', 'Food', function($scope, $routeParams, $location, Brand, Food) {
    $scope.lockEditFood = false;

    Brand.list(function(data) {
      $scope.brands = data;
    });

    Food.get({id: $routeParams.id}, function(data) {
      $scope.food = data;
    });

    $scope.edit = function() {
      if (!$scope.lockEditFood) {

        $scope.lockEditFood = true;
        if (!angular.isUndefined($scope.food) && !angular.isUndefined($scope.food.name) &&
          !angular.isUndefined($scope.food.quantity) && !angular.isUndefined($scope.food.unit) &&
          !angular.isUndefined($scope.food.calories) && !angular.isUndefined($scope.food.proteins) &&
          !angular.isUndefined($scope.food.carbohydrates) && !angular.isUndefined($scope.food.lipids) &&
          !angular.isUndefined($scope.food.brand_id)) {

          Food.put({id: $routeParams.id, food: $scope.food}, function(data) {
            // if (data == 1) {
              $scope.lockEditFood = false;
              $location.path('/foods');
            // }
          });
        }
      }
    }
  }]);
