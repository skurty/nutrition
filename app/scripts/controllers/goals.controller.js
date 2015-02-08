'use strict';

angular.module('nutritionApp.goalsControllers', []).
  controller('GoalListCtrl', ['$scope', 'Goal', function($scope, Goal) {
    Goal.list(function(data) {
      $scope.goals = data;
    });

    $scope.sort   = 'date';
    $scope.reverse = true;

    $scope.delete = function(event, id) {
      if (event) event.preventDefault();

      Goal.delete({id: id}, function(data) {
        angular.forEach($scope.goals, function(g, k) {
          if (g.id == id) {
            $scope.goals.splice(k, 1);
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
  controller('GoalAddCtrl', ['$scope', '$location', '$filter', 'Goal', function($scope, $location, $filter, Goal) {
    $scope.goal = {};
    $scope.goal.date = $filter('date')(Date.now(), 'yyyy-MM-dd');

    $scope.lockAddGoal = false;

    $scope.create = function() {
      if (!$scope.lockAddGoal) {
        $scope.lockAddGoal = true;

        if (!angular.isUndefined($scope.goal.date) && !angular.isUndefined($scope.goal.calories) && !angular.isUndefined($scope.goal.proteins)
          && !angular.isUndefined($scope.goal.carbohydrates) && !angular.isUndefined($scope.goal.lipids)) {
          Goal.post({goal: $scope.goal}, function(data) {
            $scope.lockAddGoal = false;
            $location.path('/goals');
          });
        }
      }
    }
  }]).
  controller('GoalEditCtrl', ['$scope', '$routeParams', '$location', 'Goal', function($scope, $routeParams, $location, Goal) {
    $scope.lockEditGoal = false;

    Goal.get({id: $routeParams.id}, function(data) {
      $scope.goal = data;
    });

    $scope.update = function() {
      if (!$scope.lockEditGoal) {
        $scope.lockEditGoal = true;

        if (!angular.isUndefined($scope.goal.date) && !angular.isUndefined($scope.goal.calories) && !angular.isUndefined($scope.goal.proteins)
          && !angular.isUndefined($scope.goal.carbohydrates) && !angular.isUndefined($scope.goal.lipids)) {
          Goal.put({id: $routeParams.id, goal: $scope.goal}, function(data) {
            $scope.lockEditGoal = false;
            $location.path('/goals');
          });
        }
      }
    }
  }]);
