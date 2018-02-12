app.registerCtrl('productList', function($scope,$timeout, $routeParams, $http, $location,DTOptionsBuilder, DTColumnBuilder,DTDefaultOptions) {
    // prepare the scopes
    $scope.sterms = '';
    $scope.PL = {};
    $scope.PL.dtInstance = {};
    $scope.allCategories = {};
    $scope.countryFilter = '';
    $scope.provinceFilter = '';
    $scope.cityFilter = '';
    $scope.categoryFilter = '';
    // set DOM Datatable
    DTDefaultOptions.setDOM('tipr');
    // configuration of it
    $scope.PL.dtOptions = DTOptionsBuilder.newOptions()
        .withOption('ajax', {
         url: '/api/products/datatable',
         type: 'GET',
         data: function(d) {
            d.search["value"] = $scope.sterms;
            d.city = $scope.cityFilter;
            d.province = $scope.provinceFilter;
            d.country = $scope.countryFilter;
            d.category = $scope.categoryFilter;
         },
     })
     .withDataProp('data')
     .withOption('processing', true)
     .withOption('serverSide', true)
     .withPaginationType('full_numbers');
    // now the Goddamn columns
    $scope.PL.dtColumns = [
        DTColumnBuilder.newColumn('id').withTitle('ID'),
        DTColumnBuilder.newColumn('name').withTitle('Name'),
        DTColumnBuilder.newColumn('product_code').withTitle('Product Code'),
        DTColumnBuilder.newColumn('category_id').withTitle('Category'),
        DTColumnBuilder.newColumn('price').withTitle('Price'),
        DTColumnBuilder.newColumn('capital_price').withTitle('Capital Price'),
        DTColumnBuilder.newColumn('sale_price').withTitle('Sale Price'),
        DTColumnBuilder.newColumn('action').withTitle('Action').withOption('searchable', false),
        DTColumnBuilder.newColumn('image').withTitle('Image').withClass('none'),
        DTColumnBuilder.newColumn('description').withTitle('Description').withClass('none'),
        DTColumnBuilder.newColumn('qty').withTitle('Quantity').withClass('none'),
        DTColumnBuilder.newColumn('country').withTitle('Country').withClass('none'),
        DTColumnBuilder.newColumn('province').withTitle('Province').withClass('none'),
        DTColumnBuilder.newColumn('city').withTitle('City').withClass('none'),
        
    ];

    // the searching bullshit
    /**
     * Get all categories JSON
     */
    $http.get('/api/categories/all').then(function(response) {
        $scope.allCategories = response.data.categories;
    })
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
        if(angular.isFunction($scope.PL.dtInstance.reloadData)){
            $scope.PL.dtInstance.reloadData();
        } 
    });
    $scope.$watch('categoryFilter',function(val){
        if(angular.isFunction($scope.PL.dtInstance.reloadData)){
            $scope.PL.dtInstance.reloadData();
        } 
    });
    $scope.$watch('countryFilter',function(val){
        if(angular.isFunction($scope.PL.dtInstance.reloadData)){
            $scope.PL.dtInstance.reloadData();
        } 
    });
    $scope.$watch('provinceFilter',function(val){
        if(angular.isFunction($scope.PL.dtInstance.reloadData)){
            $scope.PL.dtInstance.reloadData();
        } 
    });
    $scope.$watch('cityFilter',function(val){
        if(angular.isFunction($scope.PL.dtInstance.reloadData)){
            $scope.PL.dtInstance.reloadData();
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
    $(document).on('click','.rm-product',function(){
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
          $.post("/api/products/destroy", {id: id}, function(result){
                var data = $.parseJSON(result);
                if(data.success){
                  $('#productListDT').DataTable().ajax.reload() ;
                  swal("Product has been removed");
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
