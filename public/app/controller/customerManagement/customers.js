app.registerCtrl('customerList', function($scope,$timeout, $routeParams, $http, $location,DTOptionsBuilder, DTColumnBuilder,DTDefaultOptions) {
    // prepare the scopes
    $scope.sterms = '';
    $scope.CT = {};
    $scope.CT.dtInstance = {};
    $scope.allCompanyTypes = {};
    $scope.types = [{
        id : 0,
        name : 'Personal'
    },{
        id : 1,
        name : 'Corporation'
    }];
    $scope.companyTypeFilter = '';
    $scope.typeFilter = '';
    
    // set DOM Datatable
    DTDefaultOptions.setDOM('tipr');
    // configuration of it
    $scope.CT.dtOptions = DTOptionsBuilder.newOptions()
        .withOption('ajax', {
         url: '/api/customers/datatable',
         type: 'GET',
         data: function(d) {
            d.search["value"] = $scope.sterms;
            d.company_type = $scope.companyTypeFilter;
            d.type = $scope.typeFilter;
         },
     })
     .withDataProp('data')
     .withOption('processing', true)
     .withOption('serverSide', true)
     .withPaginationType('full_numbers');
    // now the Goddamn columns
    $scope.CT.dtColumns = [
        DTColumnBuilder.newColumn('id').withTitle('ID'),
        DTColumnBuilder.newColumn('name').withTitle('Name'),
        DTColumnBuilder.newColumn('email').withTitle('Email'),
        DTColumnBuilder.newColumn('phone').withTitle('Phone'),
        DTColumnBuilder.newColumn('type').withTitle('Type'),
        DTColumnBuilder.newColumn('points').withTitle('Points'),
        DTColumnBuilder.newColumn('action').withTitle('Action').withOption('searchable', false),
        DTColumnBuilder.newColumn('company_type').withTitle('Company Type').withClass('none'),
        DTColumnBuilder.newColumn('company_name').withTitle('Company Name').withClass('none'),
        DTColumnBuilder.newColumn('company_email').withTitle('Company Email').withClass('none'),
        DTColumnBuilder.newColumn('company_phone').withTitle('Company Phone').withClass('none'),
        DTColumnBuilder.newColumn('company_address').withTitle('Company Address').withClass('none'),
        
    ];

    // the searching bullshit
    /**
     * Get all company type
     */
    $http.get('/api/company-types/all').then(function(response) {
        $scope.allCompanyTypes = response.data.company_types;
    })
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
        if(angular.isFunction($scope.CT.dtInstance.reloadData)){
            $scope.CT.dtInstance.reloadData();
        } 
    });
    $scope.$watch('companyTypeFilter',function(val){
        if(angular.isFunction($scope.CT.dtInstance.reloadData)){
            $scope.CT.dtInstance.reloadData();
        } 
    });
    $scope.$watch('typeFilter',function(val){
        if(angular.isFunction($scope.CT.dtInstance.reloadData)){
            $scope.CT.dtInstance.reloadData();
        } 
    });
});
/** jQuery for binding delete */
$(document).ready(function(){
    $(document).on('click','.rm-customer',function(){
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
          $.post("/api/customers/destroy", {id: id}, function(result){
                var data = $.parseJSON(result);
                if(data.success){
                  $('#customerListDT').DataTable().ajax.reload() ;
                  swal("Customer has been removed");
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
