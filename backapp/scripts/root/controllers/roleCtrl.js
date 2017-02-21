(function() {
    'use strict';
    App.classy.controller({
        name: 'RoleCtrl',
        inject: ['$rootScope', '$scope', 'RoleService'],
        data: {
            state: {

            },
            collection: {
                roleList: {
                    data: []
                }
            },
            var: {}
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

                };
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