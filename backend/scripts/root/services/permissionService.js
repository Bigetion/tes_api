(function() {
    'use strict';
    App.service('PermissionService', ['$http', 'HttpService', 'API_BASE_URL', function($http, HttpService, API_BASE_URL) {
        return {
            getPermissions: function() {
                return HttpService.get(API_BASE_URL + 'app/getPermissions', {}, "Get Permissions");
            },
            updatePermissions: function(permissions) {
                var data = {
                    permissions: permissions
                }
                return HttpService.execute(API_BASE_URL + 'app/updatePermissions', data, "Update Permissions");
            }
        }
    }])

})()