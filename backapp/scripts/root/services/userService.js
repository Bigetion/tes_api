(function() {
    'use strict';
    App.service('UserService', ['$http', 'HttpService', 'API_BASE_URL', function($http, HttpService, API_BASE_URL) {
        return {
            getData: function() {
                return HttpService.get(API_BASE_URL + 'app/getUserList', {}, "Get Data");
            }
        }
    }])

})()