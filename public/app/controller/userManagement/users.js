$(document).ready(function() {
    $("#usrlst").DataTable({
          responsive: true,
          processing: true,
          serverSide: true,
          dom: 'tipr',
          ajax: {
            url: "/api/listUsers",
            data: function(d) {
              d.role_id = $('#roleFilter').val()
            },
            type: "GET"
          },
      });
      $('#searchUser').on('keyup', function(){
            $('#usrlst').DataTable().search($(this).val()).draw() ;
      });
      $("#usrlst").on("click",".rm-user", function() {
          var uid = $(this).data('uid');
          swal({
              title: "Are you sure ? ",
              text: "you are going to remove "+$(this).data('uname'),
              type: "warning",
              showCancelButton: !0,
              closeOnConfirm: !1,
              showLoaderOnConfirm: !0
          }, function() {
            reCsrf();
            $.post("/api/removeUser", {uid: uid}, function(result){
                  $('#usrlst').DataTable().ajax.reload() ;
                  swal("User has been removed");
            });
          })
      });
});
