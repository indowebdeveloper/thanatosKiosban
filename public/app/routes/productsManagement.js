angular.module('productsManagement', ["datatables","ui.select2","ngRoute", "permission", "permission.ng","ngAnimate","angular-loading-bar","ngImageInputWithPreview"])
.config(['$routeProvider', '$controllerProvider', '$locationProvider', '$qProvider',
    function($routeProvider, $controllerProvider, $locationProvider, $qProvider) {
        $routeProvider
            // Route Management Routing
            .when('/products', {
                templateUrl: 'app/partials/productManagement/products.html',
                title: 'Product Management',
                permission: 'product-management'
            })
            .when('/products/edit/:id', {
                templateUrl: 'app/partials/productManagement/edit.html',
                title: 'Edit Product',
                permission: 'edit-product'
            })
            .when('/products/create', {
                templateUrl: 'app/partials/productManagement/create.html',
                title: 'Create Product',
                permission: 'add-product'
            })

    }
]);
