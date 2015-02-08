'use strict';

angular.module('nutritionApp.diariesServices', ['ngResource']).
  factory('Diary', ['$resource', 'WS', function($resource, WS) {
    return $resource(WS.url + '/diary/:id.json', {}, {
      post: {method:'POST'},
      put: {method:'PUT', params:{id: '@id'}},
      list: {method:'GET', params:{date: '@date', last: '@last'}, url: WS.url + '/:date/:last/diaries.json'},
      copy: {method:'POST', params:{id: '@id'}, url: WS.url + '/diary/:id/copy.json'},
      move: {method:'PUT', params:{id: '@id'}, url: WS.url + '/diary/:id/move.json'},
      copyMeal: {method:'POST', url: WS.url + '/diary/copy-meal.json'},
      copyDay: {method:'POST', url: WS.url + '/diary/copy-day.json'},
    });
  }]);
