'use strict';

angular.module('nutritionApp.weightsControllers', []).
  controller('WeightListCtrl', ['$scope', 'Weight', function($scope, Weight) {
    Weight.list(function(data) {
      $scope.weights = data;
    });

    $scope.sort   = 'date';
    $scope.reverse = true;

    $scope.delete = function(event, id) {
      if (event) event.preventDefault();

      Weight.delete({id: id}, function(data) {
        angular.forEach($scope.weights, function(w, k) {
          if (w.id == id) {
            $scope.weights.splice(k, 1);
          }
        });
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
  controller('WeightAddCtrl', ['$scope', '$location', '$filter', 'Weight', function($scope, $location, $filter, Weight) {
    $scope.weight = {};
    $scope.weight.date = $filter('date')(Date.now(), 'yyyy-MM-dd');

    $scope.lockAddWeight = false;

    $scope.create = function() {
      if (!$scope.lockAddWeight) {
        $scope.lockAddWeight = true;

        if (!angular.isUndefined($scope.weight.date) && !angular.isUndefined($scope.weight.weight)) {
          Weight.post({weight: $scope.weight}, function(data) {
            $scope.lockAddWeight = false;
            $location.path('/weights');
          });
        }
      }
    }
  }]).
  controller('WeightEditCtrl', ['$scope', '$routeParams', '$location', 'Weight', function($scope, $routeParams, $location, Weight) {
    $scope.lockEditWeight = false;

    Weight.get({id: $routeParams.id}, function(data) {
      $scope.weight = data;
    });

    $scope.update = function() {
      if (!$scope.lockEditWeight) {
        $scope.lockEditWeight = true;

        if (!angular.isUndefined($scope.weight.date) && !angular.isUndefined($scope.weight.weight)) {
          Weight.put({id: $routeParams.id, weight: $scope.weight}, function(data) {
            $scope.lockEditWeight = false;
            $location.path('/weights');
          });
        }
      }
    }
  }]);
