'use strict';

angular.module('nutritionApp.statisticsServices', ['ngResource']).
  factory('Statistics', ['$resource', 'WS', function($resource, WS) {
    return $resource(WS.url + '/statistics.json', {}, {
      get: {method:'GET'}
    });
  }]);
