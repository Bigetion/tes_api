(function() {
    'use strict';
    App.classy.controller({
        name: 'MainCtrl',
        inject: ['$rootScope', '$scope', 'MainService', 'lodash'],
        data: {
            state: {

            },
            var: {
                moduleList: []
            }
        },
        init: function() {
            this._onInit();
        },
        watch: {},
        methods: {
            _onInit: function() {
                this.onLoad().getModules();
            },
            onLoad: function() {
                var _this = this;
                return {
                    getModules: function() {
                        _this.MainService.getModules().then(function(response) {
                            _this.var.moduleList = [];
                            angular.forEach(response.project, function(item) {
                                var newItem = {
                                    'name': item,
                                    'controller': response[item]
                                }
                                _this.var.moduleList.push(newItem);
                            })
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
            }
        }
    });
})();