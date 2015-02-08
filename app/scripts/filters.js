'use strict';

/* Filters */

angular.module('nutritionApp.filters', []).
	filter('interpolate', ['version', function(version) {
		return function(text) {
			return String(text).replace(/\%VERSION\%/mg, version);
		}
	}]).
	filter('noZeroDecimal', ['$filter', function($filter) {
		return function(input) {
			if (!isNaN(input) && input != null) {
				input = parseFloat(input);
				if (input % 1 === 0) {
					input = input.toFixed(0);
				}
				return input;
			} else {
				return 0;
			}
		};
	}]);
