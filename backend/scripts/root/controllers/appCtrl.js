(function() {
    'use strict';
    App.classy.controller({
        name: 'AppCtrl',
        inject: ['$rootScope', '$scope'],
        data: {
            state: {

            },
            var: {

            }
        },
        init: function() {
            this._onInit();
        },
        watch: {},
        methods: {
            _onInit: function() {
                _this.onLoad().getCurrenctState();
            },
            onLoad: function() {
                var _this = this;
                return {
                    getStateName: function() {

                    }
                };
            }
        }
    });
})();