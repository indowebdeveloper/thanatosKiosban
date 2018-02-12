app.registerCtrl('orderCreate', function($scope, $routeParams, $http, $location, $timeout, $rootScope) {
    // default
    $scope.generalSettings = {
        currency : ''
    };
    $http.get('/api/websites/all').then(function(response) {
        $scope.websites = response.data.websites;
        if($scope.websites.length<1){
            toastr.error('You have to create at least one website in order to able create a new inquiry', "Wait a minute!");
            $location.path('/websites');
        }
    });
    $http.get('/jsons/sales_from.json').then(function(response) {
        $scope.sales_from = response.data;
    })
    // prototype
    $scope.inquiry = {
        customerData : {},
        cart : [],
        total: 0
    };
    $http.get('/api/get-settings/general-settings').then(function(response){
        $scope.generalSettings = response.data.settings;
    });
    // starting scope object
    $scope.searchText = '';
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
    
    $scope.ajax = {
        items : [],
        busy : false,
        loadPage : 1,
        terms : '',
        finished : false
    };
    // default type
    $scope.types = [{
        id : 0,
        name : 'Personal'
    },{
        id : 1,
        name : 'Corporation'
    }];
    
    $scope.$watch('inquiry.customerData.email', function (val) {
        // pre-populate form if email are exist inside database
        if(val){
           $scope.searchByEmailOrPhone(); 
        }
     });
     $scope.$watch('inquiry.customerData.phone', function (val) {
        // pre-populate form if email are exist inside database
        if(val){
           $scope.searchByEmailOrPhone(); 
        }
     });
     $scope.searchByEmailOrPhone = function(){
        var e = $scope.inquiry.customerData.email;
        var p = $scope.inquiry.customerData.phone;
        $http.get('/api/customers/searchByEmailOrPhone?e='+e+'&p='+p).then(function(response) {
            var cust = response.data.customer;
            if(!_.isEmpty(cust)){
                $scope.inquiry.customerData = cust;
            }
        })
     }
    // watcher
    $scope.$watch('searchText', function (val) {
       // reset
       $scope.ajax = {
        items : [],
        busy : false,
        loadPage : 1,
        terms : '',
        finished : false
        };
        $scope.ajax.terms = $scope.searchText;
    });
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
        var url = '/api/products/search?s='+$scope.ajax.terms+'&page='+$scope.ajax.loadPage;
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
    $scope.addToCart = function(obj){
        var cartItem = {
            id: obj.id,
            name: obj.name,
            price: obj.price,
            qty: 1,
            capital_price: obj.capital_price,
            image: obj.image,
            product_code: obj.product_code,
            subtotal: (obj.price),
            shipping_cost: 0,
            shipping_expedition: '',
            shipping_address : '',
            sender_name: '',
            greetings: ''
        }
        console.log(cartItem);
        //var found = _.findIndex($scope.inquiry.cart,{'id':obj.id}) // try to lookup the product inside the collection
        //if(found<0){
            // means not found, and push the cart item into the collection
        $scope.inquiry.cart.push(cartItem);
        //}else{
            // otherwise, add the quantity
        //    $scope.inquiry.cart[found].qty += 1;
        //    $scope.inquiry.cart[found].subtotal = ($scope.inquiry.cart[found].price*$scope.inquiry.cart[found].qty);
        //}
        $scope.totalCartCost();
    }
    // force empty To Zero
    $scope.forceZero = function(index){
        if($scope.inquiry.cart[index].price==''){
            $scope.inquiry.cart[index].price = 0;
        }
        if($scope.inquiry.cart[index].qty==''){
            $scope.inquiry.cart[index].qty = 0;
        }
        if($scope.inquiry.cart[index].shipping_cost==''){
            $scope.inquiry.cart[index].shipping_cost = 0;
        }
        // we are also use force zero to repopulate subtotal of it's nested field
        $scope.inquiry.cart[index].subtotal = ($scope.inquiry.cart[index].qty*$scope.inquiry.cart[index].price)+$scope.inquiry.cart[index].shipping_cost;
        $scope.totalCartCost();
    }
    // remove cart
    $scope.removeCart = function(index){
        $scope.delegator = true;
        // array sets delegation for deleted array
        var delegated_set = {
            countries: $scope.countries,
            provinces: $scope.provinces,
            cities: $scope.cities
        };
        var delegated_cart = $scope.inquiry.cart;
        // splice the delegator;
        delegated_set.countries.splice(index,1);
        delegated_set.provinces.splice(index,1);
        delegated_set.cities.splice(index,1);
        delegated_cart.splice(index,1);
        // now apply the delegator to the real scope
        $scope.countries = delegated_set.countries;
        $scope.provinces = delegated_set.provinces;
        $scope.cities = delegated_set.cities;
        $scope.inquiry.cart = delegated_cart;
        //$scope.inquiry.cart.splice(index,1);
        $scope.totalCartCost();
        // reset delegator
        $timeout(function() { $scope.delegator = false;}, 1000);
    }
    $scope.checkCondition = function(){
        if($scope.cartForm.$invalid || $scope.custForm.$invalid){
            return true;
        }else{
            return false;
        }
    }
    /** get Current Inquiry */
    $http.get('/api/enquiries/get/' + $routeParams.id).then(function(response) {
        var exist = response.data.success;
        if(!exist){
            toastr.error('The inquiry you try to access are not exists', "Are you lost dude ?");
            $location.path('/enquiries');
        }else{
            if(response.data.inquiry.status!='pending'){
                toastr.error('The inquiry you try to access has been closed / expired', "Are you lost dude ?");
                $location.path('/enquiries'); 
            }else{
                $scope.inquiry = response.data.inquiry;
            }
        }
    });
    // Form Scope
    // process the form
    $scope.saveOrder = function() {
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
                url: '/api/orders/create',
                data: $.param({
                    data:angular.toJson($scope.inquiry)
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
                  $location.path('/enquiries');
                }
            });
        })
        
    };

     /** Cart Watcher for dynamic nested province city and anything */
     $scope.countries = [];
     $scope.provinces = [];
     $scope.cities = [];
     $scope.delegator = false;
     $scope.temp_cities = [];
     $scope.$watch('inquiry.cart',function(newcart,oldcart){
         // only monitor if its a new value
         if(newcart){
             angular.forEach(newcart,function(item,i){
                 // first init
                 var passed = false;
                 if(!oldcart[i]){
                     passed = true;
                 }
                 if(!passed){
                     if(newcart[i].country!=oldcart[i].country){
                         passed = true;
                     }
                 }
                 if(passed && !$scope.delegator){
                     console.log('delegator tembus, cart watcher');
                     console.log($scope.delegator);
                     $http.get('/api/geo/countries').then(function(response){
                         // only if country are changed
                         $scope.countries[i] = response.data;
                         $scope.createWatcher(i);
                     });
                 }
             });
             
         }
     },true);
     // dynamic watcher
     $scope.createWatcher = function(index){
         // country watcher
         $scope.$watch('inquiry.cart['+index+'].country',function(v,o){
             if(v && !$scope.delegator){
                 console.log('ini oooo');
                 console.log(o);
                 console.log(v);
                 console.log('endd');
                 if(o!=v){
                    // means not first time
                    $scope.cities[index] = false;
                 }
                 $http.get('/api/geo/children/'+v).then(function(response){
                     $scope.provinces[index] = response.data;
                 });
             }
         });
         // province watcher
         $scope.$watch('inquiry.cart['+index+'].province',function(v){
             if(v && !$scope.delegator){
                 console.log('delegator tembus , province watcher, index : '+index);
                 console.log($scope.delegator);
                 $http.get('/api/geo/children/'+v).then(function(response){
                     $scope.cities[index] = response.data;
                 });
             }
         });
     }
});
