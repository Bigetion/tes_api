(function() {
    'use strict';
    App.classy.controller({
        name: 'UserCtrl',
        inject: ['$rootScope', '$scope', 'UserService'],
        data: {
            state: {

            },
            collection: {
                userList: {
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
                this.onLoad().getUserList();
            },
            onLoad: function() {
                var _this = this;
                return {
                    getUserList: function() {
                        _this.UserService.getData().then(function(response) {
                            _this.collection.userList.data = response.data;
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