'use strict';

angular.module('nutritionApp.goalsServices', ['ngResource']).
  factory('Goal', ['$resource', 'WS', function($resource, WS) {
    return $resource(WS.url + '/goal/:id.json', {}, {
      post: {method:'POST'},
      put: {method:'PUT', params:{id: '@id'}},
      list: {method:'GET', url:WS.url + '/goals.json', isArray: true}
    });
  }]);
