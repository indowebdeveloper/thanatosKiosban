app.registerCtrl('websiteList', function($scope,$timeout, $routeParams, $http, $location,DTOptionsBuilder, DTColumnBuilder,DTDefaultOptions) {
    // prepare the scopes
    $scope.sterms = '';
    $scope.VD = {};
    $scope.VD.dtInstance = {};
    // set DOM Datatable
    DTDefaultOptions.setDOM('tipr');
    // configuration of it
    $scope.VD.dtOptions = DTOptionsBuilder.newOptions()
        .withOption('ajax', {
         url: '/api/websites/datatable',
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
    $scope.VD.dtColumns = [
        DTColumnBuilder.newColumn('id').withTitle('ID'),
        DTColumnBuilder.newColumn('name').withTitle('Name'),
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
        if(angular.isFunction($scope.VD.dtInstance.reloadData)){
            $scope.VD.dtInstance.reloadData();
        } 
    });
});
/** jQuery for binding delete */
$(document).ready(function(){
    $(document).on('click','.rm-website',function(){
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
          $.post("/api/websites/destroy", {id: id}, function(result){
                var data = $.parseJSON(result);
                if(data.success){
                  $('#websiteListDT').DataTable().ajax.reload() ;
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
