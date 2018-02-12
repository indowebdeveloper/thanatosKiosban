angular.module('userManagement', ["ngRoute","checklist-model","permission", "permission.ng","ngAnimate","angular-loading-bar"]).config(['$routeProvider', '$controllerProvider','$locationProvider','$qProvider',
    function($routeProvider, $controllerProvider, $locationProvider,$qProvider) {
        $routeProvider
        // User Management Routing
        .when('/users', {
            templateUrl: 'app/partials/userManagement/list.html',
            title: 'User Management',
            permission:'user-and-role-administration'
        })
        .when('/users/edit/:id', {
            templateUrl: 'app/partials/userManagement/edit.html',
            controller: 'editUser',
            title: 'Edit User',
            permission:'user-and-role-administration'
        })
        .when('/users/create', {
            templateUrl: 'app/partials/userManagement/create.html',
            controller: 'createUser',
            title: 'Create User',
            permission:'user-and-role-administration'
        })

    }
]);
