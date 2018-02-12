angular.module('purchasingManagement', ["moment-picker","ngSanitize","daterangepicker","ng-currency","ngTagsInput","infinite-scroll","datatables","ui.select2","ngRoute", "permission", "permission.ng","ngAnimate","angular-loading-bar","ngImageInputWithPreview"])
.config(['$routeProvider', '$controllerProvider', '$locationProvider', '$qProvider',
    function($routeProvider, $controllerProvider, $locationProvider, $qProvider) {
        $routeProvider
            // Route Management Routing
            .when('/purchase-requests', {
                templateUrl: 'app/partials/purchasingManagement/purchase-requests.html',
                title: 'Purchase Requests',
                permission: 'purchase-request-management'
            })
            // PO
            .when('/purchase-order', {
                templateUrl: 'app/partials/purchasingManagement/po/purchase-order.html',
                title: 'Purchase Order',
                permission: 'purchase-order-management'
            })
            .when('/purchase-order/edit/:id', {
                templateUrl: 'app/partials/purchasingManagement/po/edit.html',
                title: 'Edit Purchase Order',
                permission: 'edit-purchase-order'
            })
            .when('/purchase-order/create/:id', {
                templateUrl: 'app/partials/purchasingManagement/po/create.html',
                title: 'Create PO',
                permission: 'add-purchase-order'
            })
            .when('/purchase-order/view/:id', {
                templateUrl: 'app/partials/purchasingManagement/po/view.html',
                title: 'View PO',
                permission: 'purchase-order-management'
            })
            // Supplier
            .when('/suppliers', {
                templateUrl: 'app/partials/purchasingManagement/suppliers/list.html',
                title: 'Suppliers',
                permission: 'supplier-management'
            })
            .when('/suppliers/edit/:id', {
                templateUrl: 'app/partials/purchasingManagement/suppliers/edit.html',
                title: 'Edit Supplier',
                permission: 'edit-supplier'
            })
            .when('/suppliers/create', {
                templateUrl: 'app/partials/purchasingManagement/suppliers/create.html',
                title: 'Create Supplier',
                permission: 'add-supplier'
            })

    }
]);
