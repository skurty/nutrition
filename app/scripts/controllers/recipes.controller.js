'use strict';

angular.module('nutritionApp.recipesControllers', []).
  controller('RecipeListCtrl', ['$scope', 'Recipe', function($scope, Recipe) {
    Recipe.list(function(data) {
      $scope.recipes = data;
    });

    $scope.Math = window.Math;

    $scope.sort   = 'name';
    $scope.reverse = false;

    $scope.delete = function(event, id) {
      if (event) event.preventDefault();

      Recipe.delete({id: id}, function(data) {
        angular.forEach($scope.recipes, function(r, k) {
          if (r.id == id) {
            $scope.recipes.splice(k, 1);
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
  controller('RecipeAddCtrl', ['$scope', '$location', 'Recipe', 'Food', 'FoodRecipe', function($scope, $location, Recipe, Food, FoodRecipe) {
    $scope.nameSaved = false;
    $scope.recipe = {name: null, foods: []};

    $scope.saveName = function() {
      if (!angular.isUndefined($scope.recipe.name)) {
        if (angular.isUndefined($scope.recipe.id)) {
          Recipe.post({name: $scope.recipe.name}, function(data) {
            $scope.recipe = data;
            $scope.nameSaved = true;

            Food.listAll(function(data) {
              $scope.foodsList = data.foods;
            });
          });
        } else {
          Recipe.put({id: $scope.recipe.id, name: $scope.recipe.name}, function(data) {

          });
        }
      }
    }

    $scope.addEntry = function(event, food, quantity) {
      if (event) event.preventDefault();

      if (food != null && quantity != null) {
        FoodRecipe.post({recipe: $scope.recipe.id, food: food.id, quantity: quantity}, function(data) {
          $scope.recipe.foods.push(data);
          $scope.food     = '';
          $scope.quantity = '';
          $scope.updateTotal(data);
        });
      }

      return false;
    }

    $scope.delete = function(event, id) {
      if (event) event.preventDefault();

      FoodRecipe.delete({id: id}, function(data) {
        if (data) {
          angular.forEach($scope.recipe.foods, function(d, k) {
            if (d.id == id) {
              $scope.recipe.foods.splice(k, 1);
            }
          });
        } else {
          console.log('Delete error food recipe ' + id);
        }
      });

      return false;
    }

    $scope.updateTotal = function(data, addition) {
      if (addition === undefined) addition = true;

      if (addition) {
        $scope.recipe.calories      = Math.round(parseFloat($scope.recipe.calories) + parseFloat(data.calories));
        $scope.recipe.proteins      = Math.round(parseFloat($scope.recipe.proteins) + parseFloat(data.proteins));
        $scope.recipe.carbohydrates = Math.round(parseFloat($scope.recipe.carbohydrates) + parseFloat(data.carbohydrates));
        $scope.recipe.lipids        = Math.round(parseFloat($scope.recipe.lipids) + parseFloat(data.lipids));
      } else {
        $scope.recipe.calories      = Math.round(parseFloat($scope.recipe.calories) - parseFloat(data.calories));
        $scope.recipe.proteins      = Math.round(parseFloat($scope.recipe.proteins) - parseFloat(data.proteins));
        $scope.recipe.carbohydrates = Math.round(parseFloat($scope.recipe.carbohydrates) - parseFloat(data.carbohydrates));
        $scope.recipe.lipids        = Math.round(parseFloat($scope.recipe.lipids) - parseFloat(data.lipids));
      }
    }
  }]).
  controller('RecipeEditCtrl', ['$scope', '$routeParams', '$location', 'Recipe', 'Food', 'FoodRecipe', function($scope, $routeParams, $location, Recipe, Food, FoodRecipe) {
    $scope.editQuantity = {};

    Recipe.get({id: $routeParams.id}, function(data) {
      $scope.recipe = data;
    });

    Food.listAll(function(data) {
      $scope.foodsList = data.foods;
    });

    $scope.addEntry = function(event, food, quantity) {
      if (event) event.preventDefault();

      if (food != null && quantity != null) {
        FoodRecipe.post({recipe: $routeParams.id, food: food.id, quantity: quantity}, function(data) {
          $scope.recipe.foods.push(data);
          $scope.food     = '';
          $scope.quantity = '';
          $scope.updateTotal(data);
        });
      }

      return false;
    }

    $scope.showEdit = function(event, foodRecipeId) {
      if (event) event.preventDefault();

      $scope.editQuantity[foodRecipeId] = true;

      return false;
    }

    $scope.saveEdit = function(event, foodRecipe) {
      if (event) event.preventDefault();

      console.log(foodRecipe)

      foodRecipe.quantity = foodRecipe.quantity.replace(',', '.');

      return false;
    };

    $scope.delete = function(event, id) {
      if (event) event.preventDefault();

      FoodRecipe.delete({id: id}, function(data) {
        if (data) {
          angular.forEach($scope.recipe.foods, function(d, k) {
            if (d.id == id) {
              $scope.recipe.foods.splice(k, 1);
            }
          });
        } else {
          console.log('Delete error food recipe ' + id);
        }
      });

      return false;
    }

    $scope.updateTotal = function(data, addition) {
      if (addition === undefined) addition = true;

      if (addition) {
        $scope.recipe.calories      = Math.round(parseFloat($scope.recipe.calories) + parseFloat(data.calories));
        $scope.recipe.proteins      = Math.round(parseFloat($scope.recipe.proteins) + parseFloat(data.proteins));
        $scope.recipe.carbohydrates = Math.round(parseFloat($scope.recipe.carbohydrates) + parseFloat(data.carbohydrates));
        $scope.recipe.lipids        = Math.round(parseFloat($scope.recipe.lipids) + parseFloat(data.lipids));
      } else {
        $scope.recipe.calories      = Math.round(parseFloat($scope.recipe.calories) - parseFloat(data.calories));
        $scope.recipe.proteins      = Math.round(parseFloat($scope.recipe.proteins) - parseFloat(data.proteins));
        $scope.recipe.carbohydrates = Math.round(parseFloat($scope.recipe.carbohydrates) - parseFloat(data.carbohydrates));
        $scope.recipe.lipids        = Math.round(parseFloat($scope.recipe.lipids) - parseFloat(data.lipids));
      }
    }
  }]).
  controller('RecipeViewCtrl', ['$scope', '$routeParams', '$location', 'Recipe', function($scope, $routeParams, $location, Recipe) {
    Recipe.get({id: $routeParams.id}, function(data) {
      $scope.recipe = data;
    });

    $scope.changeSorting = function(column) {
      if ($scope.sort == column) {
        $scope.reverse = !$scope.reverse;
      } else {
        $scope.sort    = column;
        $scope.reverse = false;
      }
    };
  }]);
