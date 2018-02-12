app.registerCtrl('inquiryView', function($scope, $routeParams, $http, $location, $timeout, $rootScope) {
    // default
    $scope.generalSettings = {
        currency : ''
    };
    $http.get('/api/get-settings/general-settings').then(function(response){
        $scope.generalSettings = response.data.settings;
    });
    /** get Current Inquiry */
    $http.get('/api/enquiries/get/' + $routeParams.id + '?trans=true').then(function(response) {
        var exist = response.data.success;
        if(!exist){
            toastr.error('The inquiry you try to access are not exists', "Are you lost dude ?");
            $location.path('/enquiries');
        }else{
            $scope.inquiry = response.data.inquiry;
            $scope.getUserName($scope.inquiry.owner);
            if($scope.inquiry.customerData.type==1){
                $scope.getCompanyType($scope.inquiry.customerData.company_type);
            }
            $scope.getWebsiteName($scope.inquiry.website);
        }
    });
    // get user name
    $scope.getUserName = function(id){
        $http.get('/api/enquiries/get-user/' + id).then(function(response) {
            var exist = response.data.user;
            if(exist.length<1){
                $scope.inquiry.owner =  'Sales Not Found';
            }else{
                $scope.inquiry.owner =  exist.name;
            }
        });
    }
    // get company type
    $scope.getCompanyType = function(id){
        $http.get('/api/enquiries/get-company-type/' + id).then(function(response) {
            var exist = response.data.ct;
            if(exist.length<1){
                $scope.inquiry.customerData.company_type =  'Unknown Type';
            }else{
                $scope.inquiry.customerData.company_type =  exist.name;
            }
        });
    }
    // get Website
    $scope.getWebsiteName = function(id){
        $http.get('/api/websites/get-website/' + id).then(function(response) {
            var exist = response.data.websites;
            if(exist.length<1){
                $scope.inquiry.website =  'Unknown Website';
            }else{
                $scope.inquiry.website =  exist.name;
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
    
});