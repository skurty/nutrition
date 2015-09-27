'use strict';

angular.module('nutritionApp.diariesControllers', []).
  controller('DiaryListCtrl', ['$scope', '$routeParams', '$filter', 'localStorageService', 'Diary', 'Food', 'Recipe', 'String', function($scope, $routeParams, $filter, localStorageService, Diary, Food, Recipe, String) {
    $scope.meals = {1: 'Petit déjeuner', 2: 'Déjeuner', 3: 'Dîner', 4: 'Collation 1', 5: 'Collation 2', 6: 'Collation 3'};
    $scope.mealsMobile = {1: 'Petit déj\'', 2: 'Déjeuner', 3: 'Dîner', 4: 'Coll 1', 5: 'Coll 2', 6: 'Coll 3'};

    $scope.formAddFood = {1: 'food', 2: 'food', 3: 'food', 4: 'food', 5: 'food', 6: 'food'};
    $scope.showAllFoods = {1: false, 2: false, 3: false, 4: false, 5: false, 6: false};
    $scope.food = {1: null, 2: null, 3: null, 4: null, 5: null, 6: null};
    $scope.recipe = {1: null, 2: null, 3: null, 4: null, 5: null, 6: null};
    $scope.quantity = {1: null, 2: null, 3: null, 4: null, 5: null, 6: null};
    $scope.editQuantity = {};
    $scope.deleteMobileButtons = {};

    $scope.manualDiary = {1: {}, 2: {}, 3: {}, 4: {}, 5: {}, 6: {}};

    $scope.Math = window.Math;

    $scope.lockCopyMeal = false;
    $scope.lockAddManual = false;

    if (angular.isUndefined($routeParams.date)) {
      $scope.date = new Date();
    } else {
      $scope.date = new Date($routeParams.date);
    }

    var dateSimple = $filter('date')($scope.date, 'yyyy-MM-dd');

    var diariesParams = {date: dateSimple};

    Diary.list(diariesParams, function(data) {
      $scope.diaries    = data.diaries;
      $scope.totalMeals = data.totalMeals;
      $scope.total      = data.total;
      $scope.goal       = data.goal;
    });

    Food.listAll(function(data) {
      $scope.foodsList = data.foods;
    });

    Recipe.listAll(function(data) {
      $scope.recipes = data.recipes;
    });

    // Previous and next dates
    $scope.previousDate = new Date($scope.date.getTime() - 24 * 60 * 60 * 1000);

    $scope.nextDate = new Date($scope.date.getTime() + 24 * 60 * 60 * 1000);

    // 7 previous dates
    $scope.lastDates = [];
    for (var i = 1; i <= 7; i++) {
      var tmpDate = new Date($scope.date.getTime() - i * 24 * 60 * 60 * 1000);
      $scope.lastDates.push(tmpDate);
    }

    // Functions
    $scope.showEdit = function(event, diary, meal) {
      if (event) event.preventDefault();

      $scope.editQuantity[diary] = true;

      return false;
    }

    $scope.saveEdit = function(event, diary, meal) {
      if (event) event.preventDefault();

      angular.forEach($scope.diaries[meal], function(d, k) {
        if (d.id == diary) {
          d.quantity = d.quantity.replace(',', '.');
          Diary.put({id: d.id, quantity: d.quantity}, function(data) {
            $scope.diaries[meal][k].quantity      = data.diary.quantity;
            $scope.diaries[meal][k].calories      = data.diary.calories;
            $scope.diaries[meal][k].proteins      = data.diary.proteins;
            $scope.diaries[meal][k].carbohydrates = data.diary.carbohydrates;
            $scope.diaries[meal][k].lipids        = data.diary.lipids;

            $scope.totalMeals[meal].calories      = data.totalMeal.calories;
            $scope.totalMeals[meal].proteins      = data.totalMeal.proteins;
            $scope.totalMeals[meal].carbohydrates = data.totalMeal.carbohydrates;
            $scope.totalMeals[meal].lipids        = data.totalMeal.lipids;

            $scope.total.calories      = data.total.calories;
            $scope.total.proteins      = data.total.proteins;
            $scope.total.carbohydrates = data.total.carbohydrates;
            $scope.total.lipids        = data.total.lipids;

            $scope.editQuantity[diary] = false;
          });
        }
      });

      return false;
    };

    $scope.delete = function(event, diary, meal) {
      if (event) event.preventDefault();

      Diary.delete({id: diary}, function(data) {
        if (data) {
          angular.forEach($scope.diaries[meal], function(d, k) {
            if (d.id == diary) {
              $scope.diaries[meal].splice(k, 1);

              $scope.totalMeals[meal] = data.totalMeal;
              $scope.total            = data.total;
            }
          });
        } else {
          //growl.addErrorMessage('Error lors de la suppression');
        }
      });

      return false;
    };

    $scope.copyMeal = function(event, meal, date) {
      if (event) event.preventDefault();

      if (!$scope.lockCopyMeal) {
        $scope.lockCopyMeal = true;
        Diary.copyMeal({meal: meal, from: $filter('date')(date, 'yyyy-MM-dd'), to: $filter('date')($scope.date, 'yyyy-MM-dd')}, function(data) {
          $scope.diaries[meal] = data.diaries;
          // TODO merge both arrays

          $scope.totalMeals[meal] = data.totalMeal;
          $scope.total            = data.total;

          $scope.lockCopyMeal = false;
        });
      }

      return false;
    };

    $scope.copyDay = function(event, date) {
      if (event) event.preventDefault();

      Diary.copyDay({from: $filter('date')(date, 'yyyy-MM-dd'), to: $filter('date')($scope.date, 'yyyy-MM-dd')}, function(data) {
        $scope.diaries    = data.diaries;
        $scope.totalMeals = data.totalMeals;
        $scope.total      = data.total;
      });

      return false;
    };

    $scope.changeForm = function(type, mealId) {
      $scope.formAddFood[mealId] = type;
      $scope.focus = type + mealId;
      $scope.quantity[mealId] = null;

      $scope.food[mealId] = null;
      $scope.recipe[mealId] = null;
      $scope.manualDiary[mealId] = null;
    };

    $scope.onSelectFood = function(meal) {
      $scope.focus = 'foodQuantity' + meal;
    };

    $scope.onSelectRecipe = function(meal) {
      $scope.focus = 'recipeQuantity' + meal;
    };

    $scope.autocompleteComparator = function(actual, expected) {
      actual = String.removeAccents(actual).toLowerCase();
      expected = String.removeAccents(expected).toLowerCase();
      return actual.indexOf(expected) > -1;
    };

    $scope.addFood = function(event, meal) {
      if (event) event.preventDefault();

      if (!$scope.lockAddFood && $scope.food[meal] != null && $scope.quantity[meal] != null) {
        $scope.lockAddFood = true;

        Diary.post({date: $filter('date')($scope.date, 'yyyy-MM-dd'), meal: meal, food: $scope.food[meal].id, quantity: $scope.quantity[meal]}, function(data) {
          $scope.diaries[meal].push(data.diary);

          $scope.totalMeals[meal] = data.totalMeal;
          $scope.total = data.total;

          $scope.food[meal]     = '';
          $scope.quantity[meal] = '';
          $scope.lockAddFood = false;
          $scope.focus = 'food' + meal;
        });
      }

      return false;
    };

    $scope.addRecipe = function(event, meal) {
      if (event) event.preventDefault();

      if ($scope.recipe[meal].id != null && $scope.quantity[meal] != null) {
        Diary.post({date: $filter('date')($scope.date, 'yyyy-MM-dd'), meal: meal, recipe: $scope.recipe[meal].id, quantity: $scope.quantity[meal]}, function(data) {
          $scope.diaries[meal].push(data.diary);

          $scope.totalMeals[meal] = data.totalMeal;
          $scope.total = data.total;

          $scope.recipe[meal]   = '';
          $scope.quantity[meal] = '';

          $scope.formAddFood[meal] = 'food';
          $scope.focus = 'food' + meal;
        });
      }

      return false;
    };

    $scope.addManual = function(event, meal) {
      if (event) event.preventDefault();

      if (!$scope.lockAddManual) {
        $scope.lockAddManual = true;

        if (!angular.isUndefined($scope.manualDiary[meal]) && !angular.isUndefined($scope.manualDiary[meal].name) &&
          !angular.isUndefined($scope.manualDiary[meal].quantity) && !angular.isUndefined($scope.manualDiary[meal].calories) &&
          !angular.isUndefined($scope.manualDiary[meal].proteins) && !angular.isUndefined($scope.manualDiary[meal].carbohydrates) &&
          !angular.isUndefined($scope.manualDiary[meal].lipids)) {

          Diary.post({date: $filter('date')($scope.date, 'yyyy-MM-dd'), meal: meal, manual: $scope.manualDiary[meal]}, function(data) {
            $scope.diaries[meal].push(data.diary);

            $scope.totalMeals[meal] = data.totalMeal;
            $scope.total = data.total;

            $scope.manualDiary[meal] = {};

            $scope.lockAddManual = false;
          });
        }
      }

      return false;
    };

    $scope.copy = function(event, diary, from, to) {
      if (event) event.preventDefault();

      Diary.copy({id: diary, meal: to}, function(data) {
        angular.forEach($scope.diaries[from], function(d, k) {
          if (d.id == diary) {
            $scope.diaries[to].push($scope.diaries[from][k]);

            var data = $scope.diaries[from][k];

            $scope.updateTotalMeals(to, data);

            $scope.updateTotal(data);
          }
        });
      });

      return false;
    };

    $scope.move = function(event, diary, from, to) {
      if (event) event.preventDefault();

      Diary.move({id: diary, meal: to}, function(data) {
        angular.forEach($scope.diaries[from], function(d, k) {
          if (d.id == diary) {
            $scope.diaries[to].push($scope.diaries[from][k]);

            var data = $scope.diaries[from][k];

            $scope.updateTotalMeals(from, data, false);

            $scope.diaries[from].splice(k, 1);

            $scope.updateTotalMeals(to, data);
          }
        });
      });

      return false;
    };

    $scope.updateTotalMeals = function(meal, data, addition) {
      if (addition === undefined) addition = true;

      if (addition) {
        $scope.totalMeals[meal].calories      = Math.round(parseFloat($scope.totalMeals[meal].calories) + parseFloat(data.calories));
        $scope.totalMeals[meal].proteins      = Math.round(parseFloat($scope.totalMeals[meal].proteins) + parseFloat(data.proteins));
        $scope.totalMeals[meal].carbohydrates = Math.round(parseFloat($scope.totalMeals[meal].carbohydrates) + parseFloat(data.carbohydrates));
        $scope.totalMeals[meal].lipids        = Math.round(parseFloat($scope.totalMeals[meal].lipids) + parseFloat(data.lipids));
      } else {
        $scope.totalMeals[meal].calories      = Math.round(parseFloat($scope.totalMeals[meal].calories) - parseFloat(data.calories));
        $scope.totalMeals[meal].proteins      = Math.round(parseFloat($scope.totalMeals[meal].proteins) - parseFloat(data.proteins));
        $scope.totalMeals[meal].carbohydrates = Math.round(parseFloat($scope.totalMeals[meal].carbohydrates) - parseFloat(data.carbohydrates));
        $scope.totalMeals[meal].lipids        = Math.round(parseFloat($scope.totalMeals[meal].lipids) - parseFloat(data.lipids));
      }
    };

    $scope.updateTotal = function(data, addition) {
      if (addition === undefined) addition = true;

      if (addition) {
        $scope.total.calories      = Math.round(parseFloat($scope.total.calories) + parseFloat(data.calories));
        $scope.total.proteins      = Math.round(parseFloat($scope.total.proteins) + parseFloat(data.proteins));
        $scope.total.carbohydrates = Math.round(parseFloat($scope.total.carbohydrates) + parseFloat(data.carbohydrates));
        $scope.total.lipids        = Math.round(parseFloat($scope.total.lipids) + parseFloat(data.lipids));
      } else {
        $scope.total.calories      = Math.round(parseFloat($scope.total.calories) - parseFloat(data.calories));
        $scope.total.proteins      = Math.round(parseFloat($scope.total.proteins) - parseFloat(data.proteins));
        $scope.total.carbohydrates = Math.round(parseFloat($scope.total.carbohydrates) - parseFloat(data.carbohydrates));
        $scope.total.lipids        = Math.round(parseFloat($scope.total.lipids) - parseFloat(data.lipids));
      }
    };

    // Datepickers
    $scope.openDTCopyDay = false;

    $scope.showDTCopyDay = function(event) {
      if (event) {
        event.preventDefault();
        event.stopPropagation();
      }

      if (!$scope.openDTCopyDay) {
        $scope.openDTCopyDay = true;
      } else {
        $scope.openDTCopyDay = false;
      }

      return false;
    };

    $scope.$watch('dtCopyDay', function() {
      if ($scope.dtCopyDay != undefined) {
        $('#btn-copy-day').removeClass('open');
        $scope.copyDay(event, $filter('date')($scope.dtCopyDay, 'yyyy-MM-dd'));
      }
    });
  }]);
