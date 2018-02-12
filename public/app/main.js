//var additional = ['ngAnimate','angular-loading-bar'];
//var thanatosNgModule = thanatosNgModule.concat(additional)
var app = angular.module('thanatos',thanatosNgModule);
app.config(['$routeProvider', '$controllerProvider','$locationProvider','$qProvider','cfpLoadingBarProvider','$provide',
    function($routeProvider, $controllerProvider, $locationProvider,$qProvider,cfpLoadingBarProvider,$provide) {
        // remember mentioned function for later use
        app.registerCtrl = $controllerProvider.register;
        // Dashboard Routing
        $routeProvider
            .when('/', {
                templateUrl: 'app/partials/dashboard.html',
                title:'Homepage'
            })

        // Otherwise
        $routeProvider.otherwise({redirectTo: '/'});
        $qProvider.errorOnUnhandledRejections(false);
        // Turn of loading spinner
        cfpLoadingBarProvider.includeSpinner = false;
        // disable or enable angularjs Log
        var IN_DEVELOPMENT = true;
        $provide.decorator('$log', ['$delegate', function ($delegate)
        {
                var originals = {};
                var methods = ['info' , 'debug' , 'warn' , 'error'];

                angular.forEach(methods , function(method)
                {
                    originals[method] = $delegate[method];
                    $delegate[method] = function()
                    {
                        if (IN_DEVELOPMENT) {
                            var args = [].slice.call(arguments);
                            var timestamp = new Date().toString();
                            args[0] = [timestamp.substring(4 , 24), ': ', args[0]].join('');
                            originals[method].apply(null , args);
                        }
                    };
            });

            return $delegate;
        }]);

    }
]);
app.constant('_',window._);
// Dynamic Page Title
app.run(['$rootScope', '$route','$http','PermPermissionStore','$location', function($rootScope, $route,$http,PermPermissionStore,$location) {
    // Get Permissions from server
    $rootScope.permissions = permissions;
    PermPermissionStore.defineManyPermissions($rootScope.permissions,function (permissionName) {
      return _.includes($rootScope.permissions, permissionName);
    });
    $rootScope.appReady = true;
    $rootScope.admin_url = thanatosAdminUrl;
    $rootScope.$on('$routeChangeSuccess', function() {
        // Well, check the route permission first
        if(typeof $route.current.permission !== "undefined"){
          if(! _.includes($rootScope.permissions,$route.current.permission)){
            $location.path('/');
          }
        }
        if(typeof $route.current.title === "undefined"){
          $route.current.title = "Thanatos";
        }else{
          $route.current.title = $route.current.title + ' - Thanatos';
        }
        document.title = $route.current.title;
    });
    $rootScope._ = window._;

}]);

app.directive("required", function() {
    return {
        restrict: 'A', //only want it triggered for attributes
        compile: function(element) {
           //could add a check to make sure it's an input element if need be
           if(element.hasClass('nested')){
             element.parent().parent().after("<span class='red required-mark'>( * Required</span>");
           }else{
            if(element.parent().hasClass('input-group')){
                element.parent().after("<span class='red required-mark'>( * Required</span>");
               }else{
                element.after("<span class='red required-mark'>( * Required</span>");
               }
           }
        }
    }
 });

 app.filter('linebreaks', function() {
    return function(text) {
        return text.replace(/\n/g, "<br>");
    }
});

