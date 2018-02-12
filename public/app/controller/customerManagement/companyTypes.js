app.registerCtrl('companyTypeList', function($scope,$timeout, $routeParams, $http, $location,DTOptionsBuilder, DTColumnBuilder,DTDefaultOptions) {
    // prepare the scopes
    $scope.sterms = '';
    $scope.CT = {};
    $scope.CT.dtInstance = {};
    // set DOM Datatable
    DTDefaultOptions.setDOM('tipr');
    // configuration of it
    $scope.CT.dtOptions = DTOptionsBuilder.newOptions()
        .withOption('ajax', {
         url: '/api/company-types/datatable',
         type: 'GET',
         data: function(d) {
            d.search["value"] = $scope.sterms;
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
        DTColumnBuilder.newColumn('description').withTitle('Description'),
        DTColumnBuilder.newColumn('action').withTitle('Action').withOption('searchable', false)
    ];

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
});
/** jQuery for binding delete */
$(document).ready(function(){
    $(document).on('click','.rm-type',function(){
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
          $.post("/api/company-types/destroy", {id: id}, function(result){
                var data = $.parseJSON(result);
                if(data.success){
                  $('#companyTypeListDT').DataTable().ajax.reload() ;
                  swal("Company Type has been removed");
                }else{
                  swal({
                    title:"Ooops, it failed..",
                    text: "The Type are being used dude, you cant remove it",
                    type: "error"
                  })
                }

          });
        })
    });
})
