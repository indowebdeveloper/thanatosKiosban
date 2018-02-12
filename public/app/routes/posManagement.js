angular.module('posManagement', ["moment-picker","ngSanitize","daterangepicker","ng-currency","ngTagsInput","infinite-scroll","datatables","ui.select2","ngRoute", "permission", "permission.ng","ngAnimate","angular-loading-bar","ngImageInputWithPreview"])
.config(['$routeProvider', '$controllerProvider', '$locationProvider', '$qProvider',
    function($routeProvider, $controllerProvider, $locationProvider, $qProvider) {
        $routeProvider
            // Route Management Routing
            .when('/enquiries', {
                templateUrl: 'app/partials/enquiriesManagement/enquiries.html',
                title: 'Enquiries Management',
                permission: 'inquiry-management'
            })
            .when('/enquiries/edit/:id', {
                templateUrl: 'app/partials/enquiriesManagement/edit.html',
                title: 'Edit Inquiry',
                permission: 'edit-inquiry'
            })
            .when('/enquiries/create', {
                templateUrl: 'app/partials/enquiriesManagement/create.html',
                title: 'Create Inquiry',
                permission: 'add-inquiry'
            })
            .when('/enquiries/view/:id', {
                templateUrl: 'app/partials/enquiriesManagement/view.html',
                title: 'View Inquiry',
                permission: 'inquiry-management'
            })
            .when('/orders', {
                templateUrl: 'app/partials/orderManagement/orders.html',
                title: 'Orders Management',
                permission: 'order-management'
            })
            .when('/orders/edit/:id', {
                templateUrl: 'app/partials/orderManagement/edit.html',
                title: 'Edit Order',
                permission: 'edit-order'
            })
            .when('/orders/create/:id', {
                templateUrl: 'app/partials/orderManagement/create.html',
                title: 'Create Order',
                permission: 'add-order'
            })
            .when('/orders/view/:id', {
                templateUrl: 'app/partials/orderManagement/view.html',
                title: 'View Order',
                permission: 'order-management'
            })
            .when('/orders/add-payment/:id', {
                templateUrl: 'app/partials/orderManagement/addPayment.html',
                title: 'Add Payment',
                permission: 'manage-payment'
            })
            .when('/websites', {
                templateUrl: 'app/partials/websiteManagement/websites.html',
                title: 'Websites Management',
                permission: 'website-management'
            })
            .when('/websites/edit/:id', {
                templateUrl: 'app/partials/websiteManagement/edit.html',
                title: 'Edit Website',
                permission: 'website-management'
            })
            .when('/websites/create', {
                templateUrl: 'app/partials/websiteManagement/create.html',
                title: 'Create Website',
                permission: 'website-management'
            })

    }
]);
