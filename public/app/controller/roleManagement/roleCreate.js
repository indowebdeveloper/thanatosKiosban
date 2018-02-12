app.registerCtrl('createRole', function($scope, $routeParams, $http, $location) {
    $scope.roleData = {};
    /**
     * Get all permission JSON and then set the selected
     */
    $http.get('/api/all-permissions').then(function(response) {
        $scope.permissions = response.data.permissions;
        //console.log($scope.permissions);
    })

    // Form Scope
    // process the form
    $scope.saveRole = function() {
        $http({
                method: 'POST',
                url: '/api/saveRole',
                data: $.param($scope.roleData), // pass in data as strings
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                } // set the headers so angular passing info as form data (not request payload)
            })
            .then(function(response) {
                if (!response.data.success) {
                    // if not successful, bind errors to error variables
                  toastr.error("Something went wrong..", "Whoops");
                } else {
                    // if successful, bind success message to message
                  toastr.success("Your perform has been successful", "Well Done");
                  $location.path('/roles');
                }
            });
    };
});
