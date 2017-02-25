(function() {
    'use strict';
    App.service('RoleService', ['$http', 'HttpService', 'API_BASE_URL', function($http, HttpService, API_BASE_URL) {
        return {
            getData: function() {
                return HttpService.get(API_BASE_URL + 'app/getRoleList', {}, "Get Data");
            },
            submitAdd: function(inputData) {
                var data = {
                    roleName: inputData.roleName,
                    description: inputData.description
                }
                return HttpService.execute(API_BASE_URL + 'app/submitAddRole', data, "Add Data");
            },
            submitEdit: function(idRole, inputData) {
                var data = {
                    idRole: idRole,
                    roleName: inputData.roleName,
                    description: inputData.description
                }
                return HttpService.execute(API_BASE_URL + 'app/submitEditRole', data, "Update Data");
            },
            submitDelete: function(idRole) {
                var data = {
                    idRole: idRole,
                }
                return HttpService.execute(API_BASE_URL + 'app/submitDeleteRole', data, "Delete Data");
            }
        }
    }])

})()