'use strict';

App.value("Rates", {})
  .value("Currencies", [])
  .factory('Currency', ['CurrencyService', '$q', 'Rates', 'Currencies', 'AccountOverviewService', '$filter','poCurrencyFilter',
    function (CurrencyService, $q, Rates, Currencies, AccountOverviewService, $filter, poCurrencyFilter) {
      var _cache = [];
      return {
        format: function(curr){
          return poCurrencyFilter(curr);
        },
        getRate: function (from, to) {
          var deferred = $q.defer();

          from = from || "USD";
          to = to || "IDR";

          var currKey = from + '-' + to;

          if (from == to) {
            deferred.resolve(1);

            return deferred.promise;
          }
          else {
            if (!_cache[currKey]) {

              if (!Rates[currKey]) {
                AccountOverviewService.getCalculatedEquivalentAmount(from, to, 1).then(function (response) {
                  Rates[currKey] = response.amountEquivalent;
                  deferred.resolve(Rates[currKey])
                });
              } else {
                deferred.resolve(Rates[currKey]);
              }

              _cache[currKey] = deferred.promise
            }

            return _cache[currKey];
          }
        },

        /*
         * Convert currency
         */
        convert: function (data, from, to) {
          var deferred = $q.defer();

          this.getRate(from, to).then(function (rates) {
            deferred.resolve(rates * data || 0)
          });

          return deferred.promise;
        },

        /*
         * Get all currencies
         */
        getCurrencyList: function () {
          var deferred = $q.defer();

          if (Currencies == undefined || Currencies.length <= 0) {
            CurrencyService.getCurrencyList().then(function (response) {
              Currencies = response;

              deferred.resolve(Currencies);
            })
          } else {
            deferred.resolve(Currencies);
          }

          return deferred.promise;
        },

        /*
         * Get all currencies
         */
        getCurrencyGenericList: function () {
          var deferred = $q.defer();

          if (Currencies == undefined || Currencies.length <= 0) {
            CurrencyService.getCurrencyGenericList().then(function (response) {
              Currencies = response.data;

              deferred.resolve(Currencies);
            })
          } else {
            deferred.resolve(Currencies);
          }

          return deferred.promise;
        },

        clearChache: function () {
          _cache = [];
        }
      }
    }]);
