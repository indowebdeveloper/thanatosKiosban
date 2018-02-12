$(document).ready(function() {
    $("#categorylst").DataTable({
          responsive: true,
          processing: true,
          serverSide: true,
          dom: 'tipr',
          ajax: {
            url: "/api/categories/datatable",
            type: "GET"
          },
      });
      $('#searchCategory').on('keyup', function(){
            $('#categorylst').DataTable().search($(this).val()).draw() ;
      });
      $("#categorylst").on("click",".rm-category", function() {
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
            $.post("/api/categories/destroy", {id: id}, function(result){
                  var data = $.parseJSON(result);
                  if(data.success){
                    $('#categorylst').DataTable().ajax.reload() ;
                    swal("Product Category has been removed");
                  }else{
                    swal({
                      title:"Ooops, it failed..",
                      text: data.msg,
                      type: "error"
                    })
                  }

            });
          })
      });
});
