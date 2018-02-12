app.registerCtrl('editRole', function($scope, $routeParams, $http, $location) {
    /**
     ** Get the role by ID from server
     ** then fill the form with it
     **/
    $scope.roleData = {};
    $http.get('/api/getRole/' + $routeParams.id).then(function(response) {
        $scope.roleData = response.data.roleData;
        //console.log($scope.roleData);
        // Show form after data ready
        $('.hideTillLoaded').removeClass('hideTillLoaded');
    });
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
