app.registerCtrl('orderList', function($scope,$timeout, $routeParams, $http, $location,DTOptionsBuilder, DTColumnBuilder,DTDefaultOptions) {
    // prepare the scopes
    $scope.sterms = '';
    $scope.realDateFilter = {from:moment().format('YYYY-MM-DD'),to:moment().format('YYYY-MM-DD')};
    $scope.dateFilter = {date: {startDate: moment(), endDate: moment()}};
    $scope.statusFilter = ''
    $scope.userFilter = '';
    $scope.paymentStatusFilter = '';
    $scope.OD = {};
    $scope.OD.dtInstance = {};
    // get all datas for filter
    $http.get('/api/all-users').then(function(response) {
        $scope.users = response.data.users;
    });
    $scope.payment_status = [{
        id: 'unpaid',
        name : 'Unpaid'
    },{
        id: 'paid',
        name: 'Paid'
    }];
    $scope.status = [{
        id: 'unapproved',
        name : 'Unapproved'
    },{
        id: 'approved',
        name: 'Approved'
    },{
        id: 'cancelled',
        name: 'Cancelled'
    }];
    // set DOM Datatable
    DTDefaultOptions.setDOM('tipr');
    // configuration of it
    $scope.OD.dtOptions = DTOptionsBuilder.newOptions()
        .withOption('ajax', {
         url: '/api/orders/datatable',
         type: 'GET',
         data: function(d) {
            d.search["value"] = $scope.sterms;
            d.date_from = $scope.realDateFilter.from;
            d.date_to = $scope.realDateFilter.to;
            d.status = $scope.statusFilter;
            d.payment_status = $scope.paymentStatusFilter;
            d.user = $scope.userFilter;
         },
     })
     .withDataProp('data')
     .withOption('processing', true)
     .withOption('serverSide', true)
     .withPaginationType('full_numbers');
    // now the Goddamn columns
    $scope.OD.dtColumns = [
        DTColumnBuilder.newColumn('created_at').withTitle('Created At').withOption('sWidth', '180px'),
        DTColumnBuilder.newColumn('order_number').withTitle('ID'),
        DTColumnBuilder.newColumn('status').withTitle('Status'),
        DTColumnBuilder.newColumn('payment_status').withTitle('Payment Status'),
        DTColumnBuilder.newColumn('total').withTitle('Total'),
        DTColumnBuilder.newColumn('customerData.name').withTitle('Customer Name'),
        DTColumnBuilder.newColumn('action').withTitle('Action').withOption('searchable', false).withOption('sWidth', '100px'),
        DTColumnBuilder.newColumn('customerData.email').withTitle('Customer Email').withClass('none'),
        DTColumnBuilder.newColumn('owner').withTitle('Owner / Creator').withClass('none'),
        DTColumnBuilder.newColumn('customerData.phone').withTitle('Customer Phone').withClass('none'),
        DTColumnBuilder.newColumn('real_invoice').withTitle('Real Inv Req').withClass('none'),
        DTColumnBuilder.newColumn('updated_at').withTitle('Last Update').withClass('none'),
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
        if(angular.isFunction($scope.OD.dtInstance.reloadData)){
            $scope.OD.dtInstance.reloadData();
        } 
    });
    $scope.$watch('statusFilter',function(val){
        if(angular.isFunction($scope.OD.dtInstance.reloadData)){
            $scope.OD.dtInstance.reloadData();
        } 
    });
    $scope.$watch('paymentStatusFilter',function(val){
        if(angular.isFunction($scope.OD.dtInstance.reloadData)){
            $scope.OD.dtInstance.reloadData();
        } 
    });
    $scope.$watch('userFilter',function(val){
        if(angular.isFunction($scope.OD.dtInstance.reloadData)){
            $scope.OD.dtInstance.reloadData();
        } 
    });
    // the date picker
    $scope.dateOptions = {
        locale: {
          applyLabel: "Apply",
          fromLabel: "From",
          format: "YYYY-MM-DD", //will give you 2017-01-06
          toLabel: "To",
          cancelLabel: 'Cancel',
          customRangeLabel: 'Custom range'
        },
        eventHandlers: {
            'apply.daterangepicker': function(ev, picker) { 
                    $scope.realDateFilter.from = $scope.dateFilter.date.startDate.format('YYYY-MM-DD');;
                    $scope.realDateFilter.to = $scope.dateFilter.date.endDate.format('YYYY-MM-DD');;
                    console.log($scope.realDateFilter);
                    $scope.OD.dtInstance.reloadData();
            }
        },
        ranges: {
            'Today': [moment(), moment()],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
          'Last 30 Days': [moment().subtract(29, 'days'), moment()]
          
        }
      }
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
