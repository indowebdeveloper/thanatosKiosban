app.registerCtrl('ctEdit', function($scope, $routeParams, $http, $location) {
    $scope.cT = {};
    /** get Current Category */
    $http.get('/api/company-types/get/' + $routeParams.id).then(function(response) {
        var exist = response.data.success;
        if(!exist){
            toastr.error('The types you try to access are not exists', "Are you lost dude ?");
            $location.path('/company_types');
        }else{
            $scope.cT = response.data.company_types;
        }
    });
    // Form Scope
    // process the form
    $scope.saveType = function() {
        $http({
                method: 'POST',
                url: '/api/company-types/update',
                data: $.param($scope.cT), // pass in data as strings
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
                  $location.path('/company_types');
                }
            });
    };
});
