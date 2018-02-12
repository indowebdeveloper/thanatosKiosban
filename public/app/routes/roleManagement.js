angular.module('roleManagement', ["ngRoute", "checklist-model", "permission", "permission.ng","ngAnimate","angular-loading-bar"]).config(['$routeProvider', '$controllerProvider', '$locationProvider', '$qProvider',
    function($routeProvider, $controllerProvider, $locationProvider, $qProvider) {
        $routeProvider
            // Route Management Routing
            .when('/roles', {
                templateUrl: 'app/partials/roleManagement/list.html',
                title: 'Roles Management',
                permission: 'user-and-role-administration'
            })
            .when('/roles/edit/:id', {
                templateUrl: 'app/partials/roleManagement/edit.html',
                controller: 'editRole',
                title: 'Edit Role',
                permission: 'user-and-role-administration'
            })
            .when('/roles/create', {
                templateUrl: 'app/partials/roleManagement/create.html',
                controller: 'createRole',
                title: 'Create Role',
                permission: 'user-and-role-administration'
            })

    }
]);
