'use strict';

angular.module('nutritionApp.statisticsControllers', []).
  controller('StatisticListCtrl', ['$scope', 'localStorageService', 'Statistics', function($scope, localStorageService, Statistics) {
    if (localStorageService.get('statisticsDate') != null && localStorageService.get('statisticsCalories') != null &&
      localStorageService.get('statisticsMacronutrients') != null) {
      $scope.calories       = localStorageService.get('statisticsCalories');
      $scope.macronutrients = localStorageService.get('statisticsMacronutrients');
    } else {
      Statistics.get(function(data) {
        var caloriesRows = [];
        angular.forEach(data.calories, function(v, k) {
          caloriesRows.push({'c': [{'v': k}, {'v': v}]});
        });

        // console.log(caloriesRows);
        var macronutrientsRows = [];
        angular.forEach(data.macronutrients, function(v, k) {
          macronutrientsRows.push({
            "c": [{
              "v": k
            }, {
              "v": v.lipids
            }, {
              "v": v.proteins
            }, {
              "v": v.carbohydrates
            }]
                  }
                );
        });

        var weightsRows = [];
        angular.forEach(data.weights, function(v, k) {
          weightsRows.push({'c': [{'v': k}, {'v': v}]});
        });

        $scope.calories = {
          'type': 'LineChart',
          // 'displayed': true,
          'cssStyle': 'height:600px; width:100%;',
          'data': {
            'cols': [{
              'id': 'date',
              'label': 'Dates',
              'type': 'string',
              'p': {}
            }, {
              'id': 'calories',
              'label': 'Calories',
              'type': 'number',
              'p': {}
            }],
            'rows': caloriesRows
          },
          'options': {
            // 'title': 'Calories',
            // 'isStacked': 'true',
            // 'fill': 20,
            'displayExactValues': true,
            'vAxis': {
              'title': 'Calories (kcal)',
              'gridlines': {
                'count': 10
              }
            },
            'legend': {position: 'none'}
          }
        };

        $scope.macronutrients = {
          'type': 'ColumnChart',
          // 'displayed': true,
          'cssStyle': 'height:600px; width:100%;',
          'data': {
            'cols': [{
              'id': 'date',
              'label': 'Dates',
              'type': 'string',
              'p': {}
            }, {
              'id': 'lipids',
              'label': 'Lipides',
              'type': 'number',
              'p': {}
            }, {
              'id': 'proteins',
              'label': 'Protéines',
              'type': 'number',
              'p': {}
            }, {
              'id': 'carbohydrates',
              'label': 'Glucides',
              'type': 'number',
              'p': {}
            }],
            'rows': macronutrientsRows
          },
          'options': {
            'title': 'Macro-nutriments',
            'isStacked': 'true',
            'fill': 20,
            'displayExactValues': true,
            'vAxis': {
              'title': 'Quantités (g)',
              'gridlines': {
                'count': 10
              }
            },
            'legend': {position: 'bottom'}
          }
        };

        $scope.weights = {
          'type': 'LineChart',
          // 'displayed': true,
          'cssStyle': 'height:600px; width:100%;',
          'data': {
            'cols': [{
              'id': 'date',
              'label': 'Dates',
              'type': 'string',
              'p': {}
            }, {
              'id': 'weights',
              'label': 'Poids',
              'type': 'number',
              'p': {}
            }],
            'rows': weightsRows
          },
          'options': {
            // 'title': 'Calories',
            // 'isStacked': 'true',
            // 'fill': 20,
            'displayExactValues': true,
            'vAxis': {
              'title': 'Poids (kcal)',
              'gridlines': {
                'count': 10
              }
            },
            'legend': {position: 'none'}
          }
        };

        localStorageService.add('statisticsCalories', $scope.calories);
        localStorageService.add('statisticsMacronutrients', $scope.macronutrients);
        localStorageService.add('statisticsWeights', $scope.weights);
      })
    }
  }]);
