app.registerCtrl('productEdit', function($scope, $routeParams, $http, $location) {
    $scope.allCategories = {};
    $scope.productData = {};
    $scope.generalSettings = {};
    $scope.firstLoaded = 0;
     /** get Current Product */
     $http.get('/api/products/get/' + $routeParams.id).then(function(response) {
        var exist = response.data.success;
        if(!exist){
            toastr.error('The product you try to access are not exists', "Are you lost dude ?");
            $location.path('/products');
        }else{
            $scope.productData = response.data.product;
        }
    });
    /**
     * Get all categories JSON and then set the selected
     */
    $http.get('/api/categories/all').then(function(response) {
        $scope.allCategories = response.data.categories;
        if($scope.allCategories.length<1){
            toastr.error('You have to create a category first before you can create a product', "Wait a minute!");
            $location.path('/product-categories');
        }
    })
    /** Get Settings we need */
    $http.get('/api/get-settings/general-settings').then(function(response){
        $scope.generalSettings = response.data.settings;
    });
    /** Country, Province, and Cities */
    $http.get('/api/geo/countries').then(function(response){
        $scope.countries = response.data;
    });
    /** Wattch out Watttch out XD lol */
    $scope.$watch('productData.country', function(newVal) {
        if (newVal){
            // firstLoaded used for make difference between firstLoad and trigerred event
            if($scope.firstLoaded>1){
                $scope.cities = false;
            }else{
                $scope.firstLoaded += 1;
            }
            $http.get('/api/geo/children/'+newVal).then(function(response){
                $scope.provinces = response.data;
            });     
        } 
    });
    $scope.$watch('productData.province', function(newVal) {
        if (newVal){
            $http.get('/api/geo/children/'+newVal).then(function(response){
                $scope.cities = response.data;
            });     
        } 
    });
    // Form Scope
    // process the form
    $scope.saveProduct = function() {
        $http({
                method: 'POST',
                url: '/api/products/update',
                data: $.param($scope.productData), // pass in data as strings
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                } // set the headers so angular passing info as form data (not request payload)
            })
            .then(function(response) {
                if (!response.data.success) {
                    var msg = response.data.msg;
                    for(var i = 0, len = msg.length; i < len; i++){
                        toastr.error(msg[i], "Whoops");
                    }
                }  else {
                    // if successful, bind success message to message
                  toastr.success(response.data.msg, "Well Done");
                  $location.path('/products');
                }
            });
    };
});
