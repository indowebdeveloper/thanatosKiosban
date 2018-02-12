app.registerCtrl('websiteCreate', function($scope, $routeParams, $http, $location) {
    $scope.website = {};
    // Form Scope
    // process the form
    $scope.saveWebsite = function() {
        $http({
                method: 'POST',
                url: '/api/websites/create',
                data: $.param($scope.website), // pass in data as strings
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
                  $location.path('/websites');
                }
            });
    };
});
