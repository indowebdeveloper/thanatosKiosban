angular.module('settingsManagement', ["ngRoute", "checklist-model", "permission", "permission.ng","ngAnimate","angular-loading-bar","ngImageInputWithPreview"]).config(['$routeProvider', '$controllerProvider', '$locationProvider', '$qProvider',
    function($routeProvider, $controllerProvider, $locationProvider, $qProvider) {
        $routeProvider
            // Settings Management
            .when('/settings', {
                templateUrl: 'app/partials/settingsManagement/general.html',
                controller: 'generalSettings',
                title: 'General Settings',
                permission: 'manage-general-settings'
            });

    }
]);
