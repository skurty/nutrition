'use strict';


// Declare app level module which depends on filters, and services
angular.module('nutritionApp', [
	'ngRoute',
	'ngTouch',
	'nutritionApp.filters',
	'nutritionApp.brandsServices',
	'nutritionApp.commonServices',
	'nutritionApp.diariesServices',
	'nutritionApp.foodsServices',
	'nutritionApp.goalsServices',
	'nutritionApp.recipesServices',
	'nutritionApp.statisticsServices',
	'nutritionApp.weightsServices',
	'nutritionApp.directives',
	'nutritionApp.brandsControllers',
	'nutritionApp.commonControllers',
	'nutritionApp.diariesControllers',
	'nutritionApp.foodsControllers',
	'nutritionApp.goalsControllers',
	'nutritionApp.recipesControllers',
	'nutritionApp.statisticsControllers',
	'nutritionApp.weightsControllers',
	'LocalStorageModule',
	'googlechart',
	'angular-growl',
	// 'ngAnimate',
	'ui.bootstrap',
  'mgcrea.ngStrap'
]).
config(['$routeProvider', 'growlProvider', function($routeProvider, growlProvider) {
	// Detect mobile
	var _isMobile = (function() {
    return /iPhone/.test(navigator.userAgent);
  })();

  // _isMobile = true;

  // Configure growl
  growlProvider.globalTimeToLive(2000);

	$routeProvider.when('/diaries', {templateUrl: 'views/diaries/' + ((_isMobile) ? 'm_': '') + 'index.html', controller: 'DiaryListCtrl'});
	$routeProvider.when('/:date/diaries', {templateUrl: 'views/diaries/index.html', controller: 'DiaryListCtrl'});
	$routeProvider.when('/diaries/:date/:meal/add', {templateUrl: 'views/diaries/add.html', controller: 'DiaryAddCtrl'});
	$routeProvider.when('/foods', {templateUrl: 'views/foods/' + ((_isMobile) ? 'm_': '') + 'index.html', controller: 'FoodListCtrl'});
	$routeProvider.when('/foods/add', {templateUrl: 'views/foods/add.html', controller: 'FoodAddCtrl'});
	$routeProvider.when('/foods/:id/edit', {templateUrl: 'views/foods/edit.html', controller: 'FoodEditCtrl'});
	$routeProvider.when('/brands/add', {templateUrl: 'views/brands/add.html', controller: 'BrandAddCtrl'});
	$routeProvider.when('/recipes', {templateUrl: 'views/recipes/' + ((_isMobile) ? 'm_': '') + 'index.html', controller: 'RecipeListCtrl'});
	$routeProvider.when('/recipes/add', {templateUrl: 'views/recipes/add.html', controller: 'RecipeAddCtrl'});
	$routeProvider.when('/recipes/:id/view', {templateUrl: 'views/recipes/view.html', controller: 'RecipeViewCtrl'});
	$routeProvider.when('/recipes/:id/edit', {templateUrl: 'views/recipes/edit.html', controller: 'RecipeEditCtrl'});
	$routeProvider.when('/goals', {templateUrl: 'views/goals/index.html', controller: 'GoalListCtrl'});
	$routeProvider.when('/goals/add', {templateUrl: 'views/goals/add.html', controller: 'GoalAddCtrl'});
	$routeProvider.when('/goals/:id/edit', {templateUrl: 'views/goals/edit.html', controller: 'GoalEditCtrl'});
	$routeProvider.when('/statistics', {templateUrl: 'views/statistics/index.html', controller: 'StatisticListCtrl'});
	$routeProvider.when('/weights', {templateUrl: 'views/weights/index.html', controller: 'WeightListCtrl'});
	$routeProvider.when('/weights/add', {templateUrl: 'views/weights/add.html', controller: 'WeightAddCtrl'});
	$routeProvider.when('/weights/:id/edit', {templateUrl: 'views/weights/edit.html', controller: 'WeightEditCtrl'});
	$routeProvider.otherwise({redirectTo: '/diaries'});
}]);
