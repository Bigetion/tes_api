(function() {
    'use strict';
    App.classy.controller({
        name: 'AuthCtrl',
        inject: ['$rootScope', '$scope', 'AuthService', '$location'],
        data: {
            state: {

            },
            var: {
                username: '',
                password: ''
            }
        },
        init: function() {
            this._onInit();
        },
        watch: {},
        methods: {
            _onInit: function() {},
            onLoad: function() {
                var _this = this;
                return {

                };
            },
            onClick: function() {
                var _this = this;
                return {

                };
            },
            submitLogin(myForm) {
                var _this = this;
                if (myForm.$valid) {
                    _this.AuthService.submitLogin(_this.var.username, _this.var.password).then(function(response) {
                        if (response.success_message) {
                            console.log(response.session);
                            _this.$location.path('/');
                        }
                    });
                }
            },
            logout() {
                var _this = this;
                _this.AuthService.logout().then(function(response) {
                    _this.$location.path('/');
                });
            }
        }
    });
})();