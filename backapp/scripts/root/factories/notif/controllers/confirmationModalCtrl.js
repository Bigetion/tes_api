'use strict';
App.controller('ConfirmationModalCtrl', ['$scope', '$uibModalInstance', 'config', function($scope, $uibModalInstance, config) {

    $scope.headerTitle = 'Confirmation';
    $scope.message = '';

    if (config.headerTitle) $scope.headerTitle = config.headerTitle;
    if (config.message) $scope.message = config.message;

    $scope.ok = function() {
        $uibModalInstance.close(true);
    };

    $scope.cancel = function() {
        $uibModalInstance.dismiss('cancel');
    };
}]);