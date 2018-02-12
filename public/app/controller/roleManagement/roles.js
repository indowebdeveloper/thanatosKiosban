$(document).ready(function() {
    $("#rolelst").DataTable({
          responsive: true,
          processing: true,
          serverSide: true,
          dom: 'tipr',
          ajax: {
            url: "/api/listRoles",
            type: "GET"
          },
      });
      $('#searchRole').on('keyup', function(){
            $('#rolelst').DataTable().search($(this).val()).draw() ;
      });
      $("#rolelst").on("click",".rm-role", function() {
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
            $.post("/api/removeRole", {id: id}, function(result){
                  var data = $.parseJSON(result);
                  if(data.success){
                    $('#rolelst').DataTable().ajax.reload() ;
                    swal("Role has been removed");
                  }else{
                    swal({
                      title:"Ooops, it failed..",
                      text: "It seems that this role still assigned on user",
                      type: "error"
                    })
                  }

            });
          })
      });
});
