(function() {
    'use strict';
    App.classy.controller({
        name: 'PermissionCtrl',
        inject: ['$rootScope', '$scope', 'PermissionService', 'lodash', 'AuthService'],
        data: {
            state: {

            },
            var: {
                roleList: [],
                permissionList: [],
                moduleList: [],
                functionModel: {}
            }
        },
        init: function() {
            this._onInit();
        },
        watch: {},
        methods: {
            _onInit: function() {
                this.onLoad().getPermissions();
            },
            onLoad: function() {
                var _this = this;
                return {
                    getPermissions: function() {
                        _this.PermissionService.getPermissions().then(function(response) {
                            var moduleList = [];
                            angular.forEach(response.project, function(module) {
                                angular.forEach(response.controller[module], function(controller) {
                                    var newModule = {
                                        moduleName: module,
                                        controllerName: controller,
                                        functionList: response.function[controller]
                                    }
                                    moduleList.push(newModule);
                                });
                            });
                            _this.var.moduleList = moduleList;

                            var roleList = [];
                            angular.forEach(response.data, function(item) {
                                item.permission = item.permission.split('---');

                                var roleItem = {
                                    id_role: item.id_role,
                                    role_name: item.role_name
                                }
                                roleList.push(roleItem);

                                _this.var.functionModel[item.id_role] = {}

                                angular.forEach(_this.var.moduleList, function(moduleListItem) {
                                    angular.forEach(moduleListItem.functionList, function(functionListItem) {
                                        _this.var.functionModel[item.id_role][moduleListItem.moduleName + '.' + moduleListItem.controllerName + '.' + functionListItem] = _this.lodash.findIndex(item.permission, function(o) {
                                            return item.id_role == 1 || o == (moduleListItem.moduleName + '.' + moduleListItem.controllerName + '.' + functionListItem)
                                        }) > -1;
                                    });
                                });
                            });
                            _this.var.permissionList = response;
                            _this.var.roleList = roleList;
                        });
                    },
                    checkExist: function(idRole, path) {
                        var permissionListByRole = _this.lodash.filter(_this.var.permissionList.data, { id_role: idRole });
                        console.log(permissionListByRole);
                    }
                };
            },
            onClick: function() {
                var _this = this;
                return {
                    checkBox: function(cKey, cKey2) {
                        _this.var.functionModel[cKey][cKey2] = !_this.var.functionModel[cKey][cKey2];

                        var permissions = {};
                        angular.forEach(_this.var.functionModel, function(item, key) {
                            permissions[key] = [];
                            angular.forEach(_this.var.functionModel[key], function(item2, key2) {
                                if (item2) {
                                    permissions[key].push(key2);
                                }
                            });
                            permissions[key] = permissions[key].join('---');
                        });
                        _this.PermissionService.updatePermissions(permissions).then(function(response) {
                            console.log(response);
                        });
                    }
                };
            },
            onChange: function() {
                var _this = this;
                return {};
            }
        }
    });
})();