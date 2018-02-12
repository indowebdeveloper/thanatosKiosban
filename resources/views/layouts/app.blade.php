<!DOCTYPE html>
<html lang="{{ config('app.locale') }}" data-textdirection="LTR" class="loading">
<head >
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title ng-bind="title">Thanatos - Please Login</title>
    <!-- Styles -->
    <link rel="apple-touch-icon" sizes="60x60" href="/robust-assets/ico/apple-icon-60.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/robust-assets/ico/apple-icon-76.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/robust-assets/ico/apple-icon-120.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/robust-assets/ico/apple-icon-152.png">
    <link rel="shortcut icon" type="image/x-icon" href="/robust-assets/ico/favicon.ico">
    <link rel="shortcut icon" type="image/png" href="/robust-assets/ico/favicon-32.png">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <!-- BEGIN VENDOR CSS-->
    <link rel="stylesheet" href="/robust-assets/css/vendors.min.css"/>
    <!-- BEGIN VENDOR CSS-->
    <!-- BEGIN Font icons-->
    <link rel="stylesheet" type="text/css" href="/robust-assets/fonts/icomoon.css">
    <link rel="stylesheet" type="text/css" href="/robust-assets/fonts/flag-icon-css/css/flag-icon.min.css">
    <!-- END Font icons-->
    <!-- BEGIN Plugins CSS-->
    @yield('csses')
    <!-- END Plugins CSS-->

    <!-- BEGIN Vendor CSS-->
    <!-- END Vendor CSS-->
    <!-- BEGIN ROBUST CSS-->
    <link rel="stylesheet" href="/robust-assets/css/app.min.css"/>
    <!-- END ROBUST CSS-->
    <!-- BEGIN Page Level CSS-->
    <!-- END Page Level CSS-->
    <!-- BEGIN Custom CSS-->
    <link rel="stylesheet" type="text/css" href="/assets/css/style.css">
</head>
@if (Auth::check())
<body id="thanatosBody" data-open="click" data-menu="vertical-menu" data-col="2-column" class="vertical-layout vertical-menu 2-column" ng-app="thanatos">
@else
<body id="thanatosBody" data-open="click" data-menu="vertical-menu" data-col="1-column" class="vertical-layout vertical-menu 1-column  blank-page" ng-app="thanatos">
@endif

  <!-- START PRELOADER-->

  <div id="preloader-wrapper">
    <div id="loader">
      <div class="line-scale-pulse-out-rapid loader-white">
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
      </div>
    </div>
    <div class="loader-section section-top bg-cyan"></div>
    <div class="loader-section section-bottom bg-cyan"></div>
  </div>

  <!-- END PRELOADER-->
    <div id="app">
        @yield('content')
    </div>

    <!-- BEGIN VENDOR JS-->
    <script src="/robust-assets/js/vendors.min.js"></script>

    <!-- BEGIN VENDOR JS-->

    <!-- BEGIN ANGULAR CONTROLLER & ASSETS-->
    @if (Auth::check())
    <script src="/robust-assets/js/angular/underscore.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.4/lodash.min.js"></script>
    <script src="/robust-assets/js/angular/angular.min.js"></script>
    <script src="/robust-assets/js/angular/angular-resource.min.js"></script>
    <script src="/robust-assets/js/angular/angular-route.min.js"></script>
    <script src="/robust-assets/js/angular/angular-animate.min.js"></script>
    <script src="/robust-assets/js/angular/angular-sanitize.min.js"></script>
    <!-- Angular Plugins -->
    <script src="/robust-assets/js/plugins/ng-checklist/main.js" type="text/javascript"></script>
    <script src="/robust-assets/js/plugins/angular-permissions/angular-permission.min.js" type="text/javascript"></script>
    <script src="/robust-assets/js/plugins/angular-permissions/angular-permission-ng.min.js" type="text/javascript"></script>
    <script src="/robust-assets/js/plugins/angular-loading-bar/loading-bar.js" type="text/javascript"></script>
    <script src="/robust-assets/js/plugins/angular-images/ng-image-input-with-preview.min.js" type="text/javascript"></script>
    <script src="/bower_components/ng-currency/dist/ng-currency.js"></script>
    <script src="/bower_components/moment/min/moment.min.js"></script>
    <script src="/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
    <script src="/bower_components/angular-daterangepicker/js/angular-daterangepicker.js"></script>
    <script type="text/javascript" src="/bower_components/ng-tags-input/ng-tags-input.min.js"></script>
    <script src="/bower_components/moment-picker/dist/angular-moment-picker.min.js"></script>
    
    <!-- Datatable Angularjs -->
    <script src="/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="/bower_components/angular-datatables/dist/angular-datatables.min.js"></script>
    <script src="/assets/js/ng-infinite-scroll.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.min.js"></script>
    <script>
    var permissions = <?php echo json_encode(Auth::user()->myPermission()); ?>
    </script>
    <?php
    /**
     * Collect all angular routing and push it on ngModule
     * The Rule is clear, angular module name is the same as the angular file
     */
    $ngModule = [];
    $thanatosJs = "";
    foreach (glob(public_path()."/app/routes/*.js") as $js) {
      $ngModule[] = basename($js,".js");
      /** collect into $js **/
      $thanatosJs .=  '<script src="'.url('/app/routes/'.basename($js)).'" type="text/javascript"></script>';
    }
    ?>
    <!-- Angular Module name Collection -->
    <script>
    /** Store ngModule as array **/
    var thanatosNgModule = <?php echo json_encode($ngModule); ?>;
    var thanatosAdminUrl = "{{config('app.admin_url')}}";
    </script>
    <!-- Modular Routing Angular -->
    <?php echo $thanatosJs; ?>
    <!-- Main Angular -->
    <script src="/app/main.js"></script>

    @endif
    <!-- END ANGULAR CONTROLLER & ASSETS -->
    <!-- BEGIN PAGE VENDOR JS-->
    <script src="/robust-assets/js/plugins/forms/validation/jqBootstrapValidation.js" type="text/javascript"></script>
    <!-- END PAGE VENDOR JS-->

    <!-- BEGIN ROBUST JS-->
    <script src="/robust-assets/js/app.min.js"></script>
    <!-- END ROBUST JS-->

        <!-- BEGIN PAGE LEVEL JS-->
          @yield('jses')
        <!-- END PAGE LEVEL JS-->

</body>
</html>
