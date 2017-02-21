'use strict';
var Config = angular.module('newAarApp.config', []);
var App = angular
    .module('newAarApp', [
        'ui.router',
        'ui.bootstrap',
        'ngMaterial',
        'smart-table',
        'ui-notification',
        'ngFileUpload',
        'naif.base64',
        'ngResource',
        'ui.select',
        'ngScrollbars',
        'ngLodash',
        'ngStorage',
        'perfect_scrollbar',
        'classy',
        'newAarApp.config'
    ])
    .config(function() {
        PDFJS.workerSrc = 'root/factories/pdfjs/build/pdf.worker.js';
    }).run(['$rootScope', '$state', function($rootScope, $state) {
        $rootScope.$on('$stateChangeStart', function(event, toState) {
            $rootScope.currentState = $state.current.name;
        });
        $rootScope.$on('$stateChangeSuccess', function(event, toState) {
            $rootScope.currentState = $state.current.name;
        });

    }])