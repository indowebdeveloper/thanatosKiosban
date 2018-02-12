app.registerCtrl('poCreate', function($scope, $routeParams, $http, $location, $timeout, $rootScope) {
    // default
    $scope.generalSettings = {
        currency : ''
    };
    // prototype
    $scope.totalBought = 0;
    $scope.filterCountry = "";
    $scope.filterProvince = "";
    $scope.filterCity = "";
    $scope.purchase_orders = [];
    $scope.pr = {};
    $scope.initSelSup = function(){    
        $scope.selectedSupplier = {
            qty: 0,
            supplier: '',
        }
        $scope.reInitSearch();
    }
    $http.get('/api/get-settings/general-settings').then(function(response){
        $scope.generalSettings = response.data.settings;
    });
    // starting scope object
    $scope.searchText = '';
    $scope.ajax = {
        items : [],
        busy : false,
        loadPage : 1,
        terms : '',
        finished : false
    };
    // watcher
    $scope.$watch('searchText', function (val) {
       $scope.reInitSearch();
    });
    $scope.reInitSearch = function(){
        // reset
       $scope.ajax = {
            items : [],
            busy : false,
            loadPage : 1,
            terms : '',
            finished : false
        };
        $scope.ajax.terms = $scope.searchText;
    }
    $scope.$watch('ajax.terms',function(v){
        $scope.loadMore();
    });
    // currency formater
    $scope.currencyFormat = function(angka){
        var rupiah = '';        
        var angkarev = angka.toString().split('').reverse().join('');
        for(var i = 0; i < angkarev.length; i++) if(i%3 == 0) rupiah += angkarev.substr(i,3)+'.';
        return $scope.generalSettings.currency+ ' '+rupiah.split('',rupiah.length-1).reverse().join('');
    }
    // sum the subtotals
    $scope.totalCartCost = function(){
        var total = _.sumBy($scope.inquiry.cart,'subtotal');
        $scope.inquiry.total = total;
    }
    // INfinite Load Scope
    $scope.loadMore = function(){
        if($scope.ajax.busy || $scope.ajax.finished){
            return;
        } 
        $scope.ajax.busy = true;
        // start kick things off
        var url = '/api/suppliers/search?s='+$scope.ajax.terms+'&page='+$scope.ajax.loadPage+'&country='+$scope.filterCountry+'&province='+$scope.filterProvince+'&city='+$scope.filterCity;
        $http.get(url).then(function(response){
            var loadedData = response.data.data;
            angular.forEach(loadedData,function(k,v){
                this.push(k);
            },$scope.ajax.items);
            if($scope.ajax.loadPage<response.data.last_page){
                $scope.ajax.loadPage += 1;
            }else{
                $scope.ajax.finished = true;
            }
            $scope.ajax.busy = false;
        });
    }
    $scope.addPO = function(obj){
        console.log($scope.selectedSupplier);
        var po = {
            supplier_id : obj.id,
            order_id : $scope.pr.order.id,
            pr_id : $scope.pr.id,
            supplier: {
                name: obj.name,
                code: obj.supplier_code,
                country: obj.geo_country.name,
                province: obj.geo_province.name,
                city: obj.geo_city.name,
                address: obj.address
            },
            product_name : $scope.pr.name,
            product_code : $scope.pr.product_code,
            image : $scope.pr.image,
            qty: $scope.selectedSupplier.qty,
            capital_price: $scope.pr.capital_price,
            real_price: $scope.pr.capital_price,
            total: ($scope.selectedSupplier.qty*$scope.pr.capital_price),
            sender_name : $scope.pr.sender_name,
            sender_phone : $scope.pr.order.customerData.phone,
            receiver_name : $scope.pr.receiver_name,
            receiver_phone : $scope.pr.receiver_phone,
            greetings : $scope.pr.greetings,
            shipping_address: $scope.pr.shipping_address,
            city: $scope.pr.city,
            province: $scope.pr.province,
            country: $scope.pr.country,
            date_time: $scope.pr.date_time,
            notes: $scope.pr.notes,
            real_image: '',
            status: 'pending',
            payment_status: 'unpaid',
            shipped_date: '',
            tracking_number: '',
            shipping_expedition: $scope.pr.shipping_expedition
        }
        var found = _.findIndex($scope.purchase_orders,{'supplier_id':obj.id}) // try to lookup the supplier inside the collection
        if(found<0){
            //means not found, and push the cart item into the collection
            $scope.purchase_orders.push(po);
        }else{
            // otherwise, add the quantity
          $scope.purchase_orders[found].qty += $scope.selectedSupplier.qty;
          $scope.purchase_orders[found].total = $scope.purchase_orders[found].real_price*$scope.purchase_orders[found].qty;
        }
        // close modal
        angular.element('#selectSupplier').modal('hide');
        // add totalBought
        $scope.totalBought += $scope.selectedSupplier.qty;
        // reset the selected supplier
        $scope.initSelSup();
        
    }
    // force empty To Zero
    $scope.forceZero = function(index){
        if($scope.purchase_orders[index].real_price==''){
            $scope.purchase_orders[index].real_price = 0;
        }
        if($scope.purchase_orders[index].qty==''){
            $scope.purchase_orders[index].qty = 0;
        }
        // we are also use force zero to repopulate subtotal of it's nested field
        $scope.purchase_orders[index].total = $scope.purchase_orders[index].real_price*$scope.purchase_orders[index].qty;
    }
    // remove cart
    $scope.removePO = function(index){
        // first of all, substitude the totalBought
        $scope.totalBought -= $scope.purchase_orders[index].qty;
        // then remove it from the collection
        $scope.purchase_orders.splice(index, 1);
    }
    
    /** get Current Purchase Request */
    $http.get('/api/purchase-requests/get/' + $routeParams.id).then(function(response) {
        var exist = response.data.success;
        if(!exist){
            toastr.error('The PR you try to access are not exists', "Are you lost dude ?");
            $location.path('/purchase-requests');
        }else{
            if(response.data.pr.bought>response.data.pr.qty){
                toastr.error('The inquiry you try to access has been fully ordered', "Are you lost dude ?");
                $location.path('/purchase-requests'); 
            }else{
                $scope.pr = response.data.pr;
            }
        }
    });
    // Form Scope
    // process the form
    $scope.savePO = function() {
        swal({
            title: "Are you sure ? ",
            text: "You are going to create an order, are you sure want to continue ?",
            type: "warning",
            showCancelButton: !0,
            closeOnConfirm: !1,
            showLoaderOnConfirm: !0
        }, function() {
            $http({
                method: 'POST',
                url: '/api/purchase-orders/create/'+$routeParams.id,
                data: $.param({
                    data:angular.toJson($scope.purchase_orders)
                }), // pass the data as JSON
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
                  $location.path('/purchase-requests');
                }
            });
        })
        
    };

    $http.get('/api/geo/countries').then(function(response){
        // only if country are changed
        $scope.countries = response.data;
    });
     // country watcher
     $scope.$watch('filterCountry',function(v){
        if(v){
            $scope.cities = false;
            $http.get('/api/geo/children/'+v).then(function(response){
                $scope.provinces = response.data;
            });
        }else{
            $scope.cities = false;
            $scope.provinces = false;
            
        }
        $scope.reInitSearch();
    });
    // province watcher
    $scope.$watch('filterProvince',function(v){
        if(v){
            $http.get('/api/geo/children/'+v).then(function(response){
                $scope.cities = response.data;
            });
        }else{
            $scope.cities = false;
        }
        $scope.reInitSearch();
    });
    // city watcher
    $scope.$watch('filterCity',function(v){
        $scope.reInitSearch();
    });
});
