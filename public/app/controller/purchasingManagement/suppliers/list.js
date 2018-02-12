app.registerCtrl('supplierList', function($scope,$timeout, $routeParams, $http, $location,DTOptionsBuilder, DTColumnBuilder,DTDefaultOptions) {
    // prepare the scopes
    $scope.sterms = '';
    $scope.SL = {};
    $scope.SL.dtInstance = {};
    $scope.allCategories = {};
    $scope.countryFilter = '';
    $scope.provinceFilter = '';
    $scope.cityFilter = '';
    $scope.specialityFilter = '';

    // set DOM Datatable
    DTDefaultOptions.setDOM('tipr');
    // configuration of it
    $scope.SL.dtOptions = DTOptionsBuilder.newOptions()
        .withOption('ajax', {
         url: '/api/suppliers/datatable',
         type: 'GET',
         data: function(d) {
            d.search["value"] = $scope.sterms;
            d.city = $scope.cityFilter;
            d.province = $scope.provinceFilter;
            d.country = $scope.countryFilter;
            d.speciality = $scope.specialityFilter;
         },
     })
     .withDataProp('data')
     .withOption('processing', true)
     .withOption('serverSide', true)
     .withPaginationType('full_numbers');
    // now the Goddamn columns
    $scope.SL.dtColumns = [
        DTColumnBuilder.newColumn('name').withTitle('Name'),
        DTColumnBuilder.newColumn('phone').withTitle('Phone'),
        DTColumnBuilder.newColumn('geo_city.name').withTitle('City'),
        DTColumnBuilder.newColumn('geo_province.name').withTitle('Province'),
        DTColumnBuilder.newColumn('geo_country.name').withTitle('Country'),
        DTColumnBuilder.newColumn('operational_hour').withTitle('Operational Hour'),
        DTColumnBuilder.newColumn('speciality').withTitle('Speciality'),
        DTColumnBuilder.newColumn('action').withTitle('Action').withOption('searchable', false),
        DTColumnBuilder.newColumn('address').withTitle('Address'),
        DTColumnBuilder.newColumn('supplier_code').withTitle('ID'),
        DTColumnBuilder.newColumn('payment_terms').withTitle('Payment Terms').withClass('none'),
        DTColumnBuilder.newColumn('contact_person').withTitle('Contact Person').withClass('none'),
        
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
        if(angular.isFunction($scope.SL.dtInstance.reloadData)){
            $scope.SL.dtInstance.reloadData();
        } 
    });
    $scope.$watch('specialityFilter',function(val){
        if(angular.isFunction($scope.SL.dtInstance.reloadData)){
            $scope.SL.dtInstance.reloadData();
        } 
    });
    $scope.$watch('countryFilter',function(val){
        if(angular.isFunction($scope.SL.dtInstance.reloadData)){
            $scope.SL.dtInstance.reloadData();
        } 
    });
    $scope.$watch('provinceFilter',function(val){
        if(angular.isFunction($scope.SL.dtInstance.reloadData)){
            $scope.SL.dtInstance.reloadData();
        } 
    });
    $scope.$watch('cityFilter',function(val){
        if(angular.isFunction($scope.SL.dtInstance.reloadData)){
            $scope.SL.dtInstance.reloadData();
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
});
/** jQuery for binding delete */
$(document).ready(function(){
    $(document).on('click','.rm-supplier',function(){
        var id = $(this).data('id');
        swal({
            title: "Are you sure ? ",
            text: "you are going to remove "+$(this).data('name'),
            type: "warning",
            showCancelButton: !0,
            closeOnConfirm: !1,
            showLoaderOnConfirm: !0
        }, function() {
          reCsrf();
          $.post("/api/suppliers/destroy", {id: id}, function(result){
                var data = $.parseJSON(result);
                if(data.success){
                  $('#supplierListDT').DataTable().ajax.reload() ;
                  swal("Supplier has been removed");
                }else{
                  swal({
                    title:"Ooops, it failed..",
                    text: "It seems that something wrong happened",
                    type: "error"
                  })
                }

          });
        })
    });
})
