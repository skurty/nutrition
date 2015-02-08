'use strict';

angular.module('nutritionApp.foodsServices', ['ngResource']).
  factory('Food', ['$resource', 'WS', function($resource, WS) {
    return $resource(WS.url + '/food/:id.json', {}, {
      get: {method:'GET', params:{id: '@id'}},
      post: {method:'POST'},
      put: {method:'PUT', params:{id: '@id'}},
      list: {method:'GET', url:WS.url + '/foods.json', isArray: true},
      listAll: {method:'GET', url:WS.url + '/foods/list.json'},
      search: {method:'GET', url:WS.url + '/foods/search.json'}
    });
  }]);
