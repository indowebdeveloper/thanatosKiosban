app.registerCtrl('addPayment', function($scope, $routeParams, $http, $location, $timeout, $rootScope) {
    $scope.initPaymentModel = function(){
        $scope.payment = {
            transaction_id:"",
            transaction_date:"",
            amount:0,
            notes:"",
            payment_method:"",
            order_number:$routeParams.id
        };    
    }
    $scope.initPaymentModel();

    $http.get('/api/get-settings/general-settings').then(function(response){
        $scope.generalSettings = response.data.settings;
    });
    $scope.currencyFormat = function(angka){
        var rupiah = '';        
        var angkarev = angka.toString().split('').reverse().join('');
        for(var i = 0; i < angkarev.length; i++) if(i%3 == 0) rupiah += angkarev.substr(i,3)+'.';
        return $scope.generalSettings.currency+ ' '+rupiah.split('',rupiah.length-1).reverse().join('');
    }
    $http.get('/jsons/payment_methods.json').then(function(response) {
        $scope.payment_method = response.data;
    })
    /** get Current Order */
    $scope.loadOrder = function(){
        $http.get('/api/orders/get/' + $routeParams.id).then(function(response) {
            var exist = response.data.success;
            if(!exist){
                toastr.error('The order you try to access are not exists', "Are you lost dude ?");
                $location.path('/orders');
            }else{
                if(response.data.order.payment_status!='unpaid'){
                    toastr.error('The order you try to access has been closed / expired', "Are you lost dude ?");
                    $location.path('/orders'); 
                }else{
                    $scope.order = response.data.order;
                    if($scope.order.payments.length==0){
                        $scope.totalPaid = 0;
                    }else{
                        $scope.totalPaid = _.sumBy($scope.order.payments, 'amount');
                    }
                }
            }
        });
    }
    $scope.loadOrder();
    
    // Form Scope
    // process the form
    $scope.savePayment = function() {
        swal({
            title: "Are you sure ? ",
            text: "You are going to add a payment, are you sure want to continue ?",
            type: "warning",
            showCancelButton: !0,
            closeOnConfirm: !1,
            showLoaderOnConfirm: !0
        }, function() {
            $http({
                method: 'POST',
                url: '/api/payments/add-payment/'+$routeParams.id,
                data: $.param($scope.payment), // pass the data as JSON
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
                  swal.close();
                  // reload Data Order
                  $scope.loadOrder();
                  // reload Form Model
                  $scope.initPaymentModel();
                }
            });
        })
        
    };

     
});
