'use strict';

angular.module('nutritionApp.recipesServices', ['ngResource']).
  factory('Recipe', ['$resource', 'WS', function($resource, WS) {
    return $resource(WS.url + '/recipe/:id.json', {}, {
      get: {method:'GET', params:{id: '@id'}},
      post: {method:'POST'},
      put: {method:'PUT', params:{id: '@id'}},
      delete: {method:'DELETE', params:{id: '@id'}},
      list: {method:'GET', url:WS.url + '/recipes.json', isArray: true},
      listAll: {method:'GET', url:WS.url + '/recipes/list.json'}
    });
  }]).
  factory('FoodRecipe', ['$resource', 'WS', function($resource, WS) {
    return $resource(WS.url + '/food_recipe/:id.json', {}, {
      post: {method:'POST'},
      delete: {method:'DELETE', params:{id: '@id'}}
    });
  }]);
