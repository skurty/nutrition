'use strict';

/* Directives */


angular.module('nutritionApp.directives', []).
	directive('ngEnter', function() {
        return function(scope, element, attrs) {
            element.bind("keydown keypress", function(event) {
                if(event.which === 13) {
                    scope.$apply(function(){
                        scope.$eval(attrs.ngEnter, {'event': event});
                    });

                    event.preventDefault();
                }
            });
        };
    }).
    directive('showFocus', function($timeout) {
        return function(scope, element, attrs) {
            scope.$watch(attrs.showFocus,
                function (newValue) {
                    $timeout(function() {
                        newValue && element.focus();
                    });
                },true);
        };
    });