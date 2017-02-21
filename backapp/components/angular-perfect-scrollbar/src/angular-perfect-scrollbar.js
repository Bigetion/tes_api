angular.module('perfect_scrollbar', []).directive('perfectScrollbar', ['$parse', '$window', function($parse, $window) {
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
                tHead.css({ 'width': tBody[0].clientWidth });
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
                    var xElement = angular.element($elem.querySelectorAll('.ps-scrollbar-x-rail'));
                    var yElement = angular.element($elem.querySelectorAll('.ps-scrollbar-y-rail'));
                    var yTop = yElement[0].style.top;
                    var yTopOffset = parseInt(yElement[0].style.top.split('px')[0]) + 200;

                    var yHeight = tElement[0].scrollHeight;

                    tBody = angular.element($elem.querySelectorAll('#tableBody'));
                    tHead.css({ 'top': yTop, 'position': 'relative', 'width': tBody[0].clientWidth });

                    console.log(yHeight + '-' + yTopOffset);
                    if (yTopOffset >= yHeight && !isInfinite) {
                        isInfinite = true;
                        $scope.$apply($parse($attr.infiniteScroll));
                    } else {
                        isInfinite = false;
                    }
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

            $elem.bind('$destroy', function() {
                jqWindow.off('resize', update);
                $elem.perfectScrollbar('destroy');
            });

        }
    };
}]);