app.registerCtrl('orderView', function($scope, $routeParams, $http, $location, $timeout, $rootScope) {
    // default
    $scope.generalSettings = {
        currency : ''
    };
    $http.get('/api/get-settings/general-settings').then(function(response){
        $scope.generalSettings = response.data.settings;
    });
    /** get Current Inquiry */
    $http.get('/api/orders/get/' + $routeParams.id).then(function(response) {
        var exist = response.data.success;
        if(!exist){
            toastr.error('The order you try to access are not exists', "Are you lost dude ?");
            $location.path('/orders');
        }else{
            $scope.order = response.data.order;
            $scope.getUserName($scope.order.owner);
            if($scope.order.customerData.type==1){
                $scope.getCompanyType($scope.order.customerData.company_type);
            }
            
        }
    });
    // get user name
    $scope.getUserName = function(id){
        $http.get('/api/enquiries/get-user/' + id).then(function(response) {
            var exist = response.data.user;
            if(exist.length<1){
                $scope.order.owner =  'Sales Not Found';
            }else{
                $scope.order.owner =  exist.name;
            }
        });
    }
    // get company type
    $scope.getCompanyType = function(id){
        $http.get('/api/enquiries/get-company-type/' + id).then(function(response) {
            var exist = response.data.ct;
            if(exist.length<1){
                $scope.order.customerData.company_type =  'Unknown Type';
            }else{
                $scope.order.customerData.company_type =  exist.name;
            }
        });
    }
   
    // currency formater
    $scope.currencyFormat = function(angka){
        if($scope.generalSettings.currency){
            var rupiah = '';        
            var angkarev = angka.toString().split('').reverse().join('');
            for(var i = 0; i < angkarev.length; i++) if(i%3 == 0) rupiah += angkarev.substr(i,3)+'.';
            return $scope.generalSettings.currency+ '.'+rupiah.split('',rupiah.length-1).reverse().join('');
        }
        
    }
    
    // fungsi approval
    $scope.approveInvoice = function(){
        swal({
            title: "Are you sure ? ",
            text: "You are going to approve this invoice, once you approve you cant undo it, are you sure want to continue ?",
            type: "warning",
            showCancelButton: !0,
            closeOnConfirm: !1,
            showLoaderOnConfirm: !0
        }, function() {  
            $http({
                method: 'POST',
                url: '/api/orders/approve',
                data: $.param({
                    id:$routeParams.id
                }), // pass the data as JSON
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                } // set the headers so angular passing info as form data (not request payload)
            })
            .then(function(response) {
                toastr.success(response.data.msg, "Well Done");
                swal.close();
                // reload order
                $http.get('/api/orders/get/' + $routeParams.id).then(function(response) {
                        $scope.order = response.data.order;
                        $scope.getUserName($scope.order.owner);
                        if($scope.order.customerData.type==1){
                            $scope.getCompanyType($scope.order.customerData.company_type);
                        }
                });
            });
        });
    }
});