app.registerCtrl('customerCreate', function($scope, $routeParams, $http, $location) {
    $scope.allCompanyType = {};
    $scope.customerData = {};
    // default type
    $scope.customerData.type = 0;
    $scope.types = [{
        id : 0,
        name : 'Personal'
    },{
        id : 1,
        name : 'Corporation'
    }];
    /**
     * Get all type JSON and then set the selected
     */
    $http.get('/api/company-types/all').then(function(response) {
        $scope.allCompanyType = response.data.company_types;
        if($scope.allCompanyType.length<1){
            toastr.error('You have to create at least one company type in order to able create a new customer', "Wait a minute!");
            $location.path('/company_types');
        }
    })
    
    // Form Scope
    // process the form
    $scope.saveCustomer = function() {
        $http({
                method: 'POST',
                url: '/api/customers/create',
                data: $.param($scope.customerData), // pass in data as strings
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
                  $location.path('/customers');
                }
            });
    };
});
