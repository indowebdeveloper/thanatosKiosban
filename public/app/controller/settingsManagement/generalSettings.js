app.registerCtrl('generalSettings', function($scope, $routeParams, $http, $location) {
    $scope.generalSettings = {};
    $scope.currency = {};

    /** Define all variable for select options **/
    $scope.dateFormat = ['mm-dd-yyyy','mm/dd/yyyy','mm.dd.yyyy','dd-mm-yyyy','dd/mm/yyyy','dd.mm.yyyy'];
    $scope.productBarcode = ["QRCODE","PDF417","DATAMATRIX","C39","C39+","C39E","C39E+","C93","S25","S25+",
    "I25","I25+","C128","C128A","C128B","C128C","EAN2","EAN5","EAN8","EAN13","UPCA","UPCE","MSI","MSI+","POSTNET",
    "PLANET","RMS4CC","KIX","IMB","CODABAR","CODE11","PHARMA","PHARMA2T"];
    /**
     * Get settings data
     */
   $http.get('/api/get-settings/general-settings').then(function(response) {
       $scope.generalSettings = response.data.settings;
   });
    /**
     * Get all currencies
     */

     
     $http.get('/app/currency.json').then(function(response) {
        $scope.currency = response.data;
        // Create Label
        angular.forEach($scope.currency, function(obj){
          obj.label =  obj.name + ' (' + obj.symbol + ') ';
        });
     });
     /**
      * Get all timezone
      */
      $http.get('/app/timezone.json').then(function(response) {
         $scope.timezone = response.data;
      });

    


    // Form Scope
    // process the form
    $scope.saveSetting = function() {
        $http({
                method: 'POST',
                url: '/api/save-settings/general-settings',
                data: $.param($scope.generalSettings), // pass in data as strings
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                } // set the headers so angular passing info as form data (not request payload)
            })
            .then(function(response) {
              if(response.data.success){
                toastr.success("Your perform has been successful", "Well Done");
                // Update the logo
                var logo = angular.element( document.querySelector( '#logoNav' ) );
                var uq =  new Date().getTime();
                logo.attr('src','/assets/images/system/logo_dark.png?'+uq);
                logo.attr('data-expand','/assets/images/system/logo_dark.png?'+uq);
                logo.attr('data-collapse','/assets/images/system/logo_dark_small.png?'+uq)
              }else{
                toastr.error(response.data.msg, "Something Wrong");
              }
            });
    };
});
