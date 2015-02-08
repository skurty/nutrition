'use strict';

angular.module('nutritionApp.brandsServices', ['ngResource']).
  factory('Brand', ['$resource', 'WS', function($resource, WS) {
    return $resource(WS.url + '/brand/:id.json', {}, {
      list: {method:'GET', url: WS.url + '/brands.json', isArray: true},
      post: {method:'POST'}
    });
  }]);
