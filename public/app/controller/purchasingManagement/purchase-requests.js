app.registerCtrl('purchaseRequestList', function($interval,$scope,$timeout, $routeParams, $http, $location,DTOptionsBuilder, DTColumnBuilder,DTDefaultOptions) {
    // prepare the scopes
    $scope.sterms = '';
    $scope.PR = {};
    $scope.PR.dtInstance = {};
    $scope.status = ["Assigned","Unassigned"];
    $scope.countryFilter = '';
    $scope.provinceFilter = '';
    $scope.cityFilter = '';
    $scope.statusFilter = '';

    // set DOM Datatable
    DTDefaultOptions.setDOM('tipr');
    // configuration of it
    $scope.PR.dtOptions = DTOptionsBuilder.newOptions()
        .withOption('ajax', {
         url: '/api/purchase-requests/datatable',
         type: 'GET',
         data: function(d) {
            d.city = $scope.cityFilter;
            d.province = $scope.provinceFilter;
            d.country = $scope.countryFilter;
            d.status = $scope.statusFilter;
         },
     })
     .withDataProp('data')
     .withOption('processing', true)
     .withOption('serverSide', true)
     .withPaginationType('full_numbers');
    // now the Goddamn columns
    $scope.PR.dtColumns = [
        DTColumnBuilder.newColumn('name').withTitle('Name'),
        DTColumnBuilder.newColumn('order.order_number').withTitle('Order Number'),
        DTColumnBuilder.newColumn('status').withTitle('Status').withOption('searchable', false),
        DTColumnBuilder.newColumn('assignment').withTitle('Assigned To').withOption('searchable', false),
        DTColumnBuilder.newColumn('product_code').withTitle('Product Code'),
        DTColumnBuilder.newColumn('sender_name').withTitle('Sender Name'),
        DTColumnBuilder.newColumn('action').withTitle('Action').withOption('searchable', false),
        DTColumnBuilder.newColumn('image').withTitle('Image').withClass('none'),
        DTColumnBuilder.newColumn('geo_city.name').withTitle('City').withClass('none'),
        DTColumnBuilder.newColumn('geo_province.name').withTitle('Province').withClass('none'),
        DTColumnBuilder.newColumn('geo_country.name').withTitle('Country').withClass('none'),
        DTColumnBuilder.newColumn('order.websiteData.name').withTitle('Website').withClass('none'),
        DTColumnBuilder.newColumn('receiver_name').withTitle('Receiver Name').withClass('none'),
        DTColumnBuilder.newColumn('order.customerData.name').withTitle('Customer Name').withClass('none'),
        DTColumnBuilder.newColumn('order.customerData.phone').withTitle('Customer Phone').withClass('none'),
        DTColumnBuilder.newColumn('order.customerData.email').withTitle('Customer Email').withClass('none'),
        DTColumnBuilder.newColumn('receiver_name').withTitle('Receiver Name').withClass('none'),
        DTColumnBuilder.newColumn('receiver_phone').withTitle('Receiver Phone').withClass('none'),
        DTColumnBuilder.newColumn('greetings').withTitle('Greetings').withClass('none'),
        DTColumnBuilder.newColumn('notes').withTitle('Notes').withClass('none'),
        DTColumnBuilder.newColumn('shipping_address').withTitle('Address').withClass('none'),
    ];

    // the searching bullshit
    /**
     * Get all categories JSON
     */
    $http.get('/jsons/speciality.json').then(function(response){
        $scope.speciality = response.data;
    });
   /** Country, Province, and Cities */
    $http.get('/api/geo/countries').then(function(response){
        $scope.countries = response.data;
    });
    // Instantiate these variables outside the watch
    var tempFilterText = '',
        filterTextTimeout;
    // watcher
    $scope.$watch('searchText', function (val) {
        if (filterTextTimeout) $timeout.cancel(filterTextTimeout);
        tempFilterText = val;
        filterTextTimeout = $timeout(function() {
            $scope.sterms = tempFilterText;
        }, 250); // delay 250 ms
    });
    $scope.$watch('sterms',function(val){
        if(angular.isFunction($scope.PR.dtInstance.reloadData)){
            $scope.PR.dtInstance.reloadData();
        } 
    });
    $scope.$watch('statusFilter',function(val){
        if(angular.isFunction($scope.PR.dtInstance.reloadData)){
            $scope.PR.dtInstance.reloadData();
        } 
    });
    $scope.$watch('countryFilter',function(val){
        if(angular.isFunction($scope.PR.dtInstance.reloadData)){
            $scope.PR.dtInstance.reloadData();
        } 
    });
    $scope.$watch('provinceFilter',function(val){
        if(angular.isFunction($scope.PR.dtInstance.reloadData)){
            $scope.PR.dtInstance.reloadData();
        } 
    });
    $scope.$watch('cityFilter',function(val){
        if(angular.isFunction($scope.PR.dtInstance.reloadData)){
            $scope.PR.dtInstance.reloadData();
        } 
    });
    $scope.$watch('countryFilter', function(newVal) {
        if (newVal){
            $scope.cities = false;
            $http.get('/api/geo/children/'+newVal).then(function(response){
                $scope.provinces = response.data;
            });     
        } 
    });
    $scope.$watch('provinceFilter', function(newVal) {
        if (newVal){
            $http.get('/api/geo/children/'+newVal).then(function(response){
                $scope.cities = response.data;
            });     
        } 
    });

    $scope.reloadData = function()
    {
            var resetPaging = false;
            $scope.PR.dtInstance.reloadData(null, resetPaging);
    }
    var promise = $interval( function () {
        $scope.reloadData();
        console.log('jalan');
    }, 10000 );
    $scope.$on('$destroy',function(){
        if(promise)
            $interval.cancel(promise);   
    });

});
