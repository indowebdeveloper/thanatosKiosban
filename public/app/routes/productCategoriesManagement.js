angular.module('productCategoriesManagement', ["ngRoute", "checklist-model", "permission", "permission.ng","ngAnimate","angular-loading-bar"]).config(['$routeProvider', '$controllerProvider', '$locationProvider', '$qProvider',
    function($routeProvider, $controllerProvider, $locationProvider, $qProvider) {
        $routeProvider
            // Route Management Routing
            .when('/product-categories', {
                templateUrl: 'app/partials/productCategoryManagement/categories.html',
                title: 'Product Categories',
                permission: 'manage-product-category'
            })
            .when('/product-categories/edit/:id', {
                templateUrl: 'app/partials/productCategoryManagement/edit.html',
                title: 'Edit Product Category',
                permission: 'manage-product-category'
            })
            .when('/product-categories/create', {
                templateUrl: 'app/partials/productCategoryManagement/create.html',
                title: 'Create Product Category',
                permission: 'manage-product-category'
            })

    }
]);
