(function() {
    'use strict';
    App.service('HttpService', ['$rootScope', '$http', '$q', 'Notification', '$location',
        function($rootScope, $http, $q, Notification, $location) {
            return {
                get: function(url, data, actionName) {
                    var deferred = $q.defer();
                    $http({
                        url: url,
                        method: "POST",
                        data: data,
                        headers: {
                            "Accept": "application/json"
                        }
                    }).then(function(response) {
                        deferred.resolve(response.data);

                        if (response.data.require_login) {
                            $location.path('/login');
                        } else if (response.data.error_message) {
                            Notification.error({
                                message: response.data.error_message
                            })
                        }
                    }, function(error) {
                        deferred.reject(error);
                        Notification.error({
                            message: "Failed to execute,\n" + (error.status > 0 ? error.status + " " + error.data : ", Cannot Connect To Server")
                        })
                    });

                    return deferred.promise;
                },
                execute: function(url, data, actionName) {
                    var deferred = $q.defer();
                    $http({
                        url: url,
                        method: "POST",
                        data: data,
                        headers: {
                            "Accept": "application/json"
                        }
                    }).then(function(response) {
                        deferred.resolve(response.data);

                        if (response.data.require_login) {
                            $location.path('/login');
                        } else if (response.data.error_message) {
                            console.log(response.data.error_message)
                        }
                    }, function(error) {
                        deferred.reject(error);
                        console.log("Failed to execute,\n" + (error.status > 0 ? error.status + " " + error.data : ", Cannot Connect To Server"));
                    });

                    return deferred.promise;
                }
            };
        }
    ]);
})();