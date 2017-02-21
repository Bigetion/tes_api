(function() {
    'use strict';
    App.directive('accessibleForm', function() {
        return {
            restrict: 'A',
            scope: {
                name: '@',
                onSubmit: '&'
            },
            link: function(scope, elem) {
                // set up event handler on the form element
                elem.on('submit', function() {
                    var x = elem.querySelectorAll(".ng-invalid");
                    var i;
                    for (i = 0; i < x.length; i++) {
                        var myEl = angular.element(x[i]);
                        myEl.removeClass('ng-untouched');
                        myEl.addClass('ng-touched');
                    }

                    // find the first invalid element
                    var firstInvalid = elem[0].querySelector('.ng-invalid');
                    var myEl = angular.element(firstInvalid)

                    // if we find one, set focus
                    if (firstInvalid) {
                        if (scope.name && scope.$parent[scope.name]) {
                            scope.$parent[scope.name].$setValidity("", false);
                        }

                        firstInvalid.focus();
                        myEl.removeClass('ng-untouched');
                        myEl.addClass('ng-touched');
                    } else {
                        if (scope.name && scope.$parent[scope.name]) {
                            scope.$parent[scope.name].$setValidity("", true);
                            if (scope.onSubmit) scope.onSubmit();
                        }
                    }

                });
            }
        };
    });

})();