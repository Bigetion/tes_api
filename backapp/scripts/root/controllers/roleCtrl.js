(function() {
    'use strict';
    App.classy.controller({
        name: 'RoleCtrl',
        inject: ['$rootScope', '$scope', 'RoleService', 'Notif'],
        data: {
            state: {
                isAdd: false,
                isEdit: false
            },
            collection: {
                roleList: {
                    data: []
                }
            },
            var: {
                rowEdit: {},
                idRole: '',
                input: {
                    roleName: '',
                    description: ''
                }
            }
        },
        init: function() {
            this._onInit();
        },
        watch: {},
        methods: {
            _onInit: function() {
                this.onLoad().getRoleList();
            },
            onLoad: function() {
                var _this = this;
                return {
                    getRoleList: function() {
                        _this.RoleService.getData().then(function(response) {
                            _this.collection.roleList.data = response.data;
                        });
                    }
                };
            },
            onClick: function() {
                var _this = this;
                return {
                    add: function(condition) {
                        _this.state.isAdd = condition;
                        if (condition) {
                            _this.var.input = {
                                roleName: '',
                                description: ''
                            }
                        }
                    },
                    edit: function(condition, row) {
                        _this.state.isEdit = condition;
                        if (condition) {
                            _this.var.rowEdit = row;
                            _this.var.idRole = row.id_role;
                            _this.var.input = {
                                roleName: row.role_name,
                                description: row.description
                            }
                        }
                    },
                    delete: function(row, index) {
                        var deleteRow = function() {
                            _this.RoleService.submitDelete(row.id_role).then(function(response) {
                                if (response.success_message) {
                                    _this.collection.roleList.data.splice(index, 1);
                                }
                            });
                        }
                        _this.Notif.confirmation({
                            headerTitle: 'Delete Confirmation',
                            message: 'Do you want delete this row ?',
                            okFunction: deleteRow
                        });
                    }
                };
            },
            onSubmit: function() {
                var _this = this;
                return {
                    add: function(myForm) {
                        if (myForm.$valid) {
                            _this.RoleService.submitAdd(_this.var.input).then(function(response) {
                                if (response.success_message) {
                                    _this.collection.roleList.data.push({
                                        role_name: _this.var.input.roleName,
                                        description: _this.var.input.description
                                    });
                                    _this.state.isAdd = false;
                                    //_this.onLoad().getRoleList();
                                }
                            });
                        }
                    },
                    edit: function(myForm) {
                        if (myForm.$valid) {
                            _this.RoleService.submitEdit(_this.var.idRole, _this.var.input).then(function(response) {
                                if (response.success_message) {
                                    _this.var.rowEdit.role_name = _this.var.input.roleName;
                                    _this.var.rowEdit.description = _this.var.input.description;
                                    _this.state.isEdit = false;
                                    //_this.onLoad().getRoleList();
                                }
                            });
                        }
                    }
                }
            },
            onChange: function() {
                var _this = this;
                return {};
            },
            _get: function() {
                var _this = this;
                return {};
            }
        }
    });
})();