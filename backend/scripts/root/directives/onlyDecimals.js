(function() {
  'use strict';
  App.directive('onlyDecimals', ['$filter', '$locale', function ($filter, $locale) {
    return {
      require: '?ngModel',
      scope: {
        'fraction': '=?'
      },
      link: function(scope, element, attrs, ngModel) {
        var fraction = 2;

        if (scope.fraction) {
          fraction = scope.fraction
        }

        if (!ngModel) {
          return;
        }

        // ngModel.$parsers.push(function(val) {
        //   if (angular.isUndefined(val)) {
        //     var val = '';
        //   }
        //   var clean = val.replace(/[^-0-9\.]/g, '');
        //   var negativeCheck = clean.split('-');
        //   var decimalCheck = clean.split('.');
        //   if (!angular.isUndefined(negativeCheck[1])) {
        //     negativeCheck[1] = negativeCheck[1].slice(0, negativeCheck[1].length);
        //     clean = negativeCheck[0] + '-' + negativeCheck[1];
        //     if (negativeCheck[0].length > 0) {
        //       clean = negativeCheck[0];
        //     }

        //   }

        //   if (!angular.isUndefined(decimalCheck[1])) {
        //     decimalCheck[1] = decimalCheck[1].slice(0, fraction);
        //     clean = decimalCheck[0] + '.' + decimalCheck[1];
        //   }

        //   if (val !== clean) {
        //     ngModel.$setViewValue(clean);
        //     ngModel.$render();
        //   }
        //   return String(clean);
        // });

        function clearValue(value) {
          value = String(value);
          var dSeparator = $locale.NUMBER_FORMATS.DECIMAL_SEP;
          var cleared = null;

          if (value.indexOf($locale.NUMBER_FORMATS.DECIMAL_SEP) == -1 &&
            value.indexOf('.') != -1 &&
            scope.fraction) {
            dSeparator = '.';
          }

          // Replace negative pattern to minus sign (-)
          var neg_dummy = $filter('currency')("-1", getCurrencySymbol(), scope.fraction);
          var neg_regexp = RegExp("[0-9." + $locale.NUMBER_FORMATS.DECIMAL_SEP + $locale.NUMBER_FORMATS.GROUP_SEP + "]+");
          var neg_dummy_txt = neg_dummy.replace(neg_regexp.exec(neg_dummy), "");
          var value_dummy_txt = value.replace(neg_regexp.exec(value), "");

          // If is negative
          if (neg_dummy_txt == value_dummy_txt) {
            value = '-' + neg_regexp.exec(value);
          }

          if (RegExp("^-[\\s]*$", 'g').test(value)) {
            value = "-0";
          }

          if (decimalRex(dSeparator).test(value)) {
            cleared = value.match(decimalRex(dSeparator))
              .join("").match(clearRex(dSeparator));
            cleared = cleared ? cleared[0].replace(dSeparator, ".") : null;
          }

          return cleared;
        }

        ngModel.$parsers.push(function(viewValue) {
          var cVal = clearValue(viewValue);
          if (cVal == "." || cVal == "-.") {
            cVal = ".0";
          }

          return cVal;
        });

        element.bind('keypress', function(event) {
          if (event.keyCode === 32) {
            event.preventDefault();
          }
        });

        function reverse(input) {
          var result = "";
          input = input || "";
          for (var i = 0; i < input.length; i++) {
            result = input.charAt(i) + result;
          }
          return result;
        }

        function reformatViewValue() {
          var formatters = ngModel.$formatters,
            idx = formatters.length;

          var viewValue = ngModel.$$rawModelValue;
          while (idx--) {
            viewValue = formatters[idx](viewValue);
          }

          ngModel.$setViewValue(viewValue);
          ngModel.$render();
        }

        element.on("blur", function() {
          ngModel.$commitViewValue();
          reformatViewValue();
        });

        ngModel.$formatters.unshift(function(value) {
          if (!value) {
            return
          }

          // handle large number
          var strVal = value.toString();

          strVal = strVal.replace(/[^0-9.]+|\s+/gmi, "");

          var splited = strVal.split(".");

          var result = "";
          if (splited[0]) {
            angular.forEach(reverse(splited[0]), function(val, key) {
              if (key > 0 && ((key % 3) == 0)) {
                result = "," + result;
              }
              result = val + result;
            });
          }
          else {
            result = 0
          }

          var decimal;
          if (splited[1]) {
            decimal = "0." + splited[1];

            var decimalSplit = $filter('currency')(decimal, "", scope.fraction).split(".");

            if (parseInt(decimalSplit) > 0) {
              decimal = "99"
            }
            else {
              decimal = decimalSplit[1];
            }

          }
          else {
            decimal = "00";
          }

          return result + '.' + decimal;
        });
      }
    };
  }])
})();