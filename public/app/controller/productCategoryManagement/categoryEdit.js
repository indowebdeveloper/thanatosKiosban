app.registerCtrl('categoryEdit', function($scope, $routeParams, $http, $location) {
    $scope.allCategories = [];
    $scope.categoryData = {};
    /**
     * Get all categories JSON and then set the selected
     */
    $http.get('/api/categories/all').then(function(response) {
        $scope.allCategories = response.data.categories;
    });
    /** get Current Category */
    $http.get('/api/categories/get/' + $routeParams.id).then(function(response) {
        var exist = response.data.success;
        if(!exist){
            toastr.error('The category you try to access are not exists', "Are you lost dude ?");
            $location.path('/product-categories');
        }else{
            $scope.categoryData = response.data.category;
            $scope.categoryData.old_code = $scope.categoryData.code;
        }
    });
    // Form Scope
    // process the form
    $scope.saveCategory = function() {
        $http({
                method: 'POST',
                url: '/api/categories/update',
                data: $.param($scope.categoryData), // pass in data as strings
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
                  $location.path('/product-categories');
                }
            });
    };
});
