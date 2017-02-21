(function() {
    'use strict';
    App.service('MainService', ['$http', 'HttpService', 'API_BASE_URL', function($http, HttpService, API_BASE_URL) {
        return {
            getModules: function() {
                return HttpService.get(API_BASE_URL + 'app/getModules', {}, "Get Data");
            }
        }
    }])

})()