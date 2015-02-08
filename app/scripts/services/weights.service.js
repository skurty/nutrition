'use strict';

angular.module('nutritionApp.weightsServices', ['ngResource']).
  factory('Weight', ['$resource', 'WS', function($resource, WS) {
    return $resource(WS.url + '/weight/:id.json', {}, {
      post: {method:'POST'},
      put: {method:'PUT', params:{id: '@id'}},
      list: {method:'GET', url:WS.url + '/weights.json', isArray: true}
    });
  }]);
