'use strict';
App.factory('Notif', ['$uibModal', function($uibModal) {
    return {
        confirmation: function(config) {
            var modalInstance = $uibModal.open({
                animation: true,
                ariaLabelledBy: 'modal-title',
                ariaDescribedBy: 'modal-body',
                templateUrl: 'scripts/root/factories/notif/templates/confirmationModal.html',
                controller: 'ConfirmationModalCtrl',
                size: 'sm',
                appendTo: angular.element(document.body),
                resolve: {
                    config: config
                }
            });

            modalInstance.result.then(function(ok) {
                if (ok) {
                    if (config.okFunction) config.okFunction();
                }
            }, function() {
                console.log('Modal dismissed at: ' + new Date());
            });
        }
    }
}])