app.registerCtrl('supplierEdit', function($scope, $routeParams, $http, $location) {
    $scope.supplierData = {};
    $scope.firstLoaded = 0;
    // get current supplier
    $http.get('/api/suppliers/get/' + $routeParams.id).then(function(response) {
        var exist = response.data.success;
        if(!exist){
            toastr.error('The supplier you try to access are not exists', "Are you lost dude ?");
            $location.path('/supplier');
        }else{
            $scope.supplierData = response.data.supplier;
        }
    });
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
                url: '/api/suppliers/update',
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
