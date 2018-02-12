app.registerCtrl('supplierCreate', function($scope, $routeParams, $http, $location) {
    $scope.supplierData = {};
     /** Country, Province, and Cities */
     $http.get('/api/geo/countries').then(function(response){
        $scope.countries = response.data;
    });
    $http.get('/jsons/speciality.json').then(function(response){
        $scope.speciality = response.data;
    });
    /** Wattch out Watttch out XD lol */
    $scope.$watch('supplierData.country', function(newVal) {
        if (newVal){
            $scope.cities = false;
            $http.get('/api/geo/children/'+newVal).then(function(response){
                $scope.provinces = response.data;
            });     
        } 
    });
    $scope.$watch('supplierData.province', function(newVal) {
        if (newVal){
            $http.get('/api/geo/children/'+newVal).then(function(response){
                $scope.cities = response.data;
            });     
        } 
    });
    // Form Scope
    // process the form
    $scope.saveSupplier = function() {
        $http({
                method: 'POST',
                url: '/api/suppliers/create',
                data: $.param($scope.supplierData), // pass in data as strings
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
                  $location.path('/suppliers');
                }
            });
    };
});
