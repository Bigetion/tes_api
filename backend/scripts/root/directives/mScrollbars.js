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

    function ScrollBarsDirective(ScrollBars, $window) {
        return {
            scope: {
                ngScrollbarsConfig: '=?',
                ngScrollbarsUpdate: '=?',
                element: '=?',
                onScrollEnd: '&?'
            },
            link: function(scope, elem, attrs) {
                scope.elem = elem;
                var element = elem[0];
                // angular.element(element).css('width', element.clientWidth + 'px');

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
                            var top = this.mcs.top * -1;
                            angular.element(elem.querySelectorAll('#tableHead')).css('top', top);
                            var body = angular.element(elem.querySelectorAll('#tableBody'))[0];

                            if ((this.mcs.topPct >= 90) && !infinite) {
                                console.log(this.mcs);
                                console.log(body.clientHeight);
                                infinite = true;
                                if (scope.onScrollEnd) scope.onScrollEnd();
                                this.mcs.top -= 350;
                            }
                            if (this.mcs.topPct < 90) {
                                infinite = false;
                            }
                        }
                    }
                }

                render(defaults, configuredDefaults, elem, scope);

                angular.element($window).bind('resize', function() {
                    console.log($window.innerWidth);
                    scope.$digest();
                });
            }
        };
    }

    ScrollBarsProvider.$inject = [];
    ScrollBarsDirective.$inject = ['ScrollBars', '$window'];

    angular.module('ngScrollbars', [])
        .provider('ScrollBars', ScrollBarsProvider)
        .directive('mScrollbars', ScrollBarsDirective)
        .directive('tableScrollbar', ['$parse', '$window', '$interval', '$timeout', function($parse, $window, $interval, $timeout) {
            var psOptions = [
                'wheelSpeed', 'wheelPropagation', 'minScrollbarLength', 'useBothWheelAxes',
                'useKeyboard', 'suppressScrollX', 'suppressScrollY', 'scrollXMarginOffset',
                'scrollYMarginOffset', 'includePadding' //, 'onScroll', 'scrollDown'
            ];

            return {
                restrict: 'EA',
                transclude: true,
                template: '<div><div ng-transclude></div></div>',
                replace: true,
                link: function($scope, $elem, $attr) {
                    var jqWindow = angular.element($window);
                    var options = {};
                    var isInfinite = false;

                    for (var i = 0, l = psOptions.length; i < l; i++) {
                        var opt = psOptions[i];
                        if ($attr[opt] !== undefined) {
                            options[opt] = $parse($attr[opt])();
                        }
                    }

                    var tBody = angular.element($elem.querySelectorAll('#tableBody'));
                    var tHead = angular.element($elem.querySelectorAll('#tableHead'));
                    var tElement = angular.element($elem);

                    var getTHeadWidth = function() {
                        tHead.css({ 'width': tBody[0].clientWidth + 1 });

                        var tElementHeight = tElement[0].clientHeight;
                        var tHeadHeight = tHead[0].clientHeight;
                        tBody.css({ 'max-height': tElementHeight - tHeadHeight + 'px' });

                        var tHeadTr = angular.element(tHead.querySelectorAll('tr'));

                        var getTBodyTr = function() {
                            var tBodyTr = angular.element(tBody.querySelectorAll('tr'));
                            if (tBodyTr.length > 0) {
                                angular.forEach(tBodyTr[0].cells, function(cell, i) {
                                    cell.width = tHeadTr[0].cells[i].clientWidth;
                                });
                            } else {
                                $timeout(getTBodyTr, 1);
                            }
                        }
                        var tBodyTr = getTBodyTr();
                    }

                    var getInfinite = function() {
                        var xElement = angular.element($elem.querySelectorAll('.ps-scrollbar-x-rail'));
                        var yElement = angular.element($elem.querySelectorAll('.ps-scrollbar-y-rail'));
                        var yTop = yElement[0].style.top;
                        var yTopOffset = parseInt(yElement[0].style.top.split('px')[0]) + 300;

                        var yHeight = tElement[0].scrollHeight;

                        tBody = angular.element($elem.querySelectorAll('#tableBody'));
                        tHead.css({ 'top': yTop, 'position': 'relative', 'width': tBody[0].clientWidth + 1 });

                        if (yTopOffset >= yHeight && !isInfinite) {
                            isInfinite = true;
                            $scope.$apply($parse($attr.infiniteScroll));
                        } else {
                            isInfinite = false;
                        }
                    }

                    getTHeadWidth();

                    $scope.$evalAsync(function() {
                        $elem.perfectScrollbar(options);
                        var onScrollHandler = $parse($attr.onScroll)
                        $elem.scroll(function() {
                            var scrollTop = $elem.scrollTop()
                            var scrollHeight = $elem.prop('scrollHeight') - $elem.height()
                            $scope.$apply(function() {
                                onScrollHandler($scope, {
                                    scrollTop: scrollTop,
                                    scrollHeight: scrollHeight
                                })
                            });
                            getInfinite();
                            getTHeadWidth();
                        });
                    });

                    function update(event) {
                        $scope.$evalAsync(function() {
                            if ($attr.scrollDown == 'true' && event != 'mouseenter') {
                                setTimeout(function() {
                                    $($elem).scrollTop($($elem).prop("scrollHeight"));
                                }, 100);
                            }
                            $elem.perfectScrollbar('update');
                        });
                    }

                    // This is necessary when you don't watch anything with the scrollbar
                    $elem.bind('mouseenter', update('mouseenter'));

                    // Possible future improvement - check the type here and use the appropriate watch for non-arrays
                    if ($attr.refreshOnChange) {
                        $scope.$watchCollection($attr.refreshOnChange, function() {
                            update();
                        });
                    }

                    // this is from a pull request - I am not totally sure what the original issue is but seems harmless
                    if ($attr.refreshOnResize) {
                        jqWindow.on('resize', update);
                    }

                    angular.element($window).bind('resize', function() {
                        getInfinite();
                        getTHeadWidth();
                        $scope.$digest();
                    });

                    $elem.bind('$destroy', function() {
                        jqWindow.off('resize', update);
                        $elem.perfectScrollbar('destroy');
                    });

                }
            };
        }]);

})();