angular.module('customerManagement', ["datatables","ui.select2","ngRoute", "permission", "permission.ng","ngAnimate","angular-loading-bar","ngImageInputWithPreview"])
.config(['$routeProvider', '$controllerProvider', '$locationProvider', '$qProvider',
    function($routeProvider, $controllerProvider, $locationProvider, $qProvider) {
        $routeProvider
            // Route Management Routing
            .when('/customers', {
                templateUrl: 'app/partials/customerManagement/customers.html',
                title: 'Customer Management',
                permission: 'customer-management'
            })
            .when('/customers/edit/:id', {
                templateUrl: 'app/partials/customerManagement/edit.html',
                title: 'Edit Customer',
                permission: 'edit-customer'
            })
            .when('/customers/create', {
                templateUrl: 'app/partials/customerManagement/create.html',
                title: 'Create Customer',
                permission: 'add-customer'
            })
            .when('/company_types', {
                templateUrl: 'app/partials/customerManagement/company_types.html',
                title: 'Company Type Management',
                permission: 'company-type-management'
            })
            .when('/company_types/edit/:id', {
                templateUrl: 'app/partials/customerManagement/company_types_edit.html',
                title: 'Edit Company Type',
                permission: 'edit-company-type'
            })
            .when('/company_types/create', {
                templateUrl: 'app/partials/customerManagement/company_types_create.html',
                title: 'Create Company Type',
                permission: 'add-company-type'
            })

    }
]);
