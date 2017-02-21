(function() {
    'use strict';
    App.config(['$stateProvider', '$urlRouterProvider', function($stateProvider, $urlRouterProvider) {
        $urlRouterProvider.otherwise("/");
        $stateProvider
            .state('main', {
                url: "/",
                views: {
                    "content": {
                        controller: 'MainCtrl',
                        templateUrl: "scripts/root/views/main.html"
                    }
                }
            })
            .state('roles', {
                url: "/roles",
                views: {
                    "content": {
                        controller: 'RoleCtrl',
                        templateUrl: "scripts/root/views/role.html"
                    }
                }
            })
            .state('users', {
                url: "/users",
                views: {
                    "content": {
                        controller: 'UserCtrl',
                        templateUrl: "scripts/root/views/user.html"
                    }
                }
            })
            .state('permissions', {
                url: "/permissions",
                views: {
                    "content": {
                        controller: 'PermissionCtrl',
                        templateUrl: "scripts/root/views/permission.html"
                    }
                }
            })
            .state('login', {
                url: "/login",
                views: {
                    "login": {
                        controller: 'AuthCtrl',
                        templateUrl: "scripts/root/views/login.html"
                    }
                }
            })
    }]);
})();