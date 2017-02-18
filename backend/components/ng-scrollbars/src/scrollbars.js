/**
 * ng-scrollbars 0.0.9
 */
(function() {
    'use strict';

    function ScrollBarsProvider() {
        this.defaults = {
            scrollButtons: {
                enable: true //enable scrolling buttons by default
            },
            axis: 'yx' //enable 2 axis scrollbars by default
        };

        // TODO: can we do this without jquery?
        $.mCustomScrollbar.defaults.scrollButtons = this.defaults.scrollButtons;
        $.mCustomScrollbar.defaults.axis = this.defaults.axis;

        this.$get = function ScrollBarsProvider() {
            return {
                defaults: this.defaults
            }
        }
    }

    function render(defaults, configuredDefaults, elem, scope) {
        elem.mCustomScrollbar('destroy');

        var config = {};
        if (scope.ngScrollbarsConfig) {
            config = scope.ngScrollbarsConfig;
        }

        // apply configured provider defaults only if the scope's config isn't defined (it has priority in that case)
        for (var setting in defaults) {
            if (defaults.hasOwnProperty(setting)) {

                switch (setting) {

                    case 'scrollButtons':
                        if (!config.hasOwnProperty(setting)) {
                            configuredDefaults.scrollButtons = defaults[setting];
                        }
                        break;

                    case 'axis':
                        if (!config.hasOwnProperty(setting)) {
                            configuredDefaults.axis = defaults[setting];
                        }
                        break;

                    default:
                        if (!config.hasOwnProperty(setting)) {
                            config[setting] = defaults[setting];
                        }
                        break;

                }
            }
        }

        elem.mCustomScrollbar(config);
    }

    function ScrollBarsDirective(ScrollBars) {
        return {
            scope: {
                ngScrollbarsConfig: '=?',
                ngScrollbarsUpdate: '=?',
                element: '=?',
                onBottom: '&?'
            },
            link: function(scope, elem, attrs) {
                scope.elem = elem;

                var infinite = false;
                var defaults = ScrollBars.defaults;
                var configuredDefaults = $.mCustomScrollbar.defaults;

                scope.ngScrollbarsUpdate = function() {
                    elem.mCustomScrollbar.apply(elem, arguments);
                };

                scope.$watch('ngScrollbarsConfig', function(newVal, oldVal) {
                    if (newVal !== undefined) {
                        render(defaults, configuredDefaults, elem, scope);
                    }
                });

                if (configuredDefaults) {
                    configuredDefaults.callbacks = {
                        whileScrolling: function() {
                            if (this.mcs.topPct >= 95 && !infinite) {
                                infinite = true;
                                scope.onBottom();
                                this.mcs.topPct = 101;
                            }
                            if (this.mcs.topPct < 95) {
                                infinite = false;
                            }
                        }
                    }
                }

                render(defaults, configuredDefaults, elem, scope);
            }
        };
    }

    angular.module('ngScrollbars', [])
        .provider('ScrollBars', ScrollBarsProvider)
        .directive('ngScrollbars', ScrollBarsDirective);

    ScrollBarsProvider.$inject = [];
    ScrollBarsDirective.$inject = ['ScrollBars'];

})();