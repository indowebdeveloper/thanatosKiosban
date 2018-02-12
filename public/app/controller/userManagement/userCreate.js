app.registerCtrl('createUser', function($scope, $routeParams, $http, $location) {
    $scope.userData = {};
    /**
     * Get all roles JSON and then set the selected
     */
    $http.get('/api/all-roles').then(function(response) {
        $scope.roles = response.data.roles;
        $scope.userData.role = {
            id: $scope.userData.role
        }
    })
    // Form Scope
    // process the form
    $scope.saveUser = function() {
        $scope.userData.roleID = $scope.userData.role.id
        $http({
                method: 'POST',
                url: '/api/saveUser',
                data: $.param($scope.userData), // pass in data as strings
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                } // set the headers so angular passing info as form data (not request payload)
            })
            .then(function(response) {
                console.log(response.data);
                if (!response.data.success) {
                    // if not successful, bind errors to error variables
                  toastr.error("Something went wrong..", "Whoops");
                } else {
                    // if successful, bind success message to message
                  toastr.success("Your perform has been successful", "Well Done");
                  $location.path('/users');
                }
            });
    };
});
