app.registerCtrl('websiteEdit', function($scope, $routeParams, $http, $location) {
    $scope.website = {};
    $http.get('/api/websites/get/'+$routeParams.id).then(function(response){
        var exist = response.data.success;
        if(!exist){
            toastr.error('The website you try to access are not exists', "Are you lost dude ?");
            $location.path('/websites');
        }else{
            $scope.website = response.data.websites;
            $scope.website.mail_inboxes = $.parseJSON($scope.website.mail_inboxes);
            $scope.website.sales_inboxes = $.parseJSON($scope.website.sales_inboxes);
            $scope.website.finance_inboxes = $.parseJSON($scope.website.finance_inboxes);
            $scope.website.purchasing_inboxes = $.parseJSON($scope.website.purchasing_inboxes);
        }
    });
    // Form Scope
    // process the form
    $scope.saveWebsite = function() {
        $http({
                method: 'POST',
                url: '/api/websites/update',
                data: $.param($scope.website), // pass in data as strings
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                } // set the headers so angular passing info as form data (not request payload)
            })
            .then(function(response) {
                if (!response.data.success) {
                    var msg = response.data.msg;
                    for(var i = 0, len = msg.length; i < len; i++){
                        toastr.error(msg[i], "Whoops");
                    }
                }  else {
                    // if successful, bind success message to message
                  toastr.success(response.data.msg, "Well Done");
                  $location.path('/websites');
                }
            });
    };
});
