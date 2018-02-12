@extends('layouts.app')

@section('content')
<!-- TOP NAVIGATION -->
<nav class="header-navbar navbar navbar-with-menu navbar-fixed-top navbar-semi-dark navbar-shadow">
  <div class="navbar-wrapper">
    <div class="navbar-header">
      <ul class="nav navbar-nav">
        <li class="nav-item mobile-menu hidden-md-up float-xs-left"><a class="nav-link nav-menu-main menu-toggle hidden-xs"><i class="icon-menu5 font-large-1"></i></a></li>
        <li class="nav-item">
          <a href="/" class="navbar-brand nav-link">
            <img alt="branding logo" id="logoNav" style="width:100%;" src="{{ getSettings('general-settings','logo_dark') }}" data-expand="{{ getSettings('general-settings','logo_dark') }}" data-collapse="{{ getSettings('general-settings','logo_dark_small') }}" class="brand-logo">
          </a>
        </li>
        <li class="nav-item hidden-md-up float-xs-right"><a data-toggle="collapse" data-target="#navbar-mobile" class="nav-link open-navbar-container"><i class="icon-ellipsis pe-2x icon-icon-rotate-right-right"></i></a></li>
      </ul>
    </div>
    <div class="navbar-container content container-fluid">
      <div id="navbar-mobile" class="collapse navbar-toggleable-sm">
        <ul class="nav navbar-nav">
          <li class="nav-item hidden-sm-down"><a class="nav-link nav-menu-main menu-toggle hidden-xs"><i class="icon-menu5"></i></a></li>
          <li class="nav-item hidden-sm-down"><a href="#" class="nav-link nav-link-expand"><i class="ficon icon-expand2"></i></a></li>
        </ul>
        <ul class="nav navbar-nav float-xs-right">
          <li class="dropdown dropdown-notification nav-item"><a href="#" data-toggle="dropdown" class="nav-link nav-link-label"><i class="ficon icon-bell4"></i><span class="tag tag-pill tag-default tag-danger tag-default tag-up">5</span></a>
            <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
              <li class="dropdown-menu-header">
                <h6 class="dropdown-header m-0"><span class="grey darken-2">Notifications</span><span class="notification-tag tag tag-default tag-danger float-xs-right m-0">5 New</span></h6>
              </li>
              <li class="list-group scrollable-container"><a href="javascript:void(0)" class="list-group-item">
                  <div class="media">
                    <div class="media-left valign-middle"><i class="icon-cart3 icon-bg-circle bg-cyan"></i></div>
                    <div class="media-body">
                      <h6 class="media-heading">You have new order!</h6>
                      <p class="notification-text font-small-3 text-muted">Lorem ipsum dolor sit amet, consectetuer elit.</p><small>
                        <time datetime="2015-06-11T18:29:20+08:00" class="media-meta text-muted">30 minutes ago</time></small>
                    </div>
                  </div></a><a href="javascript:void(0)" class="list-group-item">
                  <div class="media">
                    <div class="media-left valign-middle"><i class="icon-monitor3 icon-bg-circle bg-red bg-darken-1"></i></div>
                    <div class="media-body">
                      <h6 class="media-heading red darken-1">99% Server load</h6>
                      <p class="notification-text font-small-3 text-muted">Aliquam tincidunt mauris eu risus.</p><small>
                        <time datetime="2015-06-11T18:29:20+08:00" class="media-meta text-muted">Five hour ago</time></small>
                    </div>
                  </div></a><a href="javascript:void(0)" class="list-group-item">
                  <div class="media">
                    <div class="media-left valign-middle"><i class="icon-server2 icon-bg-circle bg-yellow bg-darken-3"></i></div>
                    <div class="media-body">
                      <h6 class="media-heading yellow darken-3">Warning notifixation</h6>
                      <p class="notification-text font-small-3 text-muted">Vestibulum auctor dapibus neque.</p><small>
                        <time datetime="2015-06-11T18:29:20+08:00" class="media-meta text-muted">Today</time></small>
                    </div>
                  </div></a><a href="javascript:void(0)" class="list-group-item">
                  <div class="media">
                    <div class="media-left valign-middle"><i class="icon-check2 icon-bg-circle bg-green bg-accent-3"></i></div>
                    <div class="media-body">
                      <h6 class="media-heading">Complete the task</h6><small>
                        <time datetime="2015-06-11T18:29:20+08:00" class="media-meta text-muted">Last week</time></small>
                    </div>
                  </div></a><a href="javascript:void(0)" class="list-group-item">
                  <div class="media">
                    <div class="media-left valign-middle"><i class="icon-bar-graph-2 icon-bg-circle bg-teal"></i></div>
                    <div class="media-body">
                      <h6 class="media-heading">Generate monthly report</h6><small>
                        <time datetime="2015-06-11T18:29:20+08:00" class="media-meta text-muted">Last month</time></small>
                    </div>
                  </div></a></li>
              <li class="dropdown-menu-footer"><a href="javascript:void(0)" class="dropdown-item text-muted text-xs-center">Read all notifications</a></li>
            </ul>
          </li>
          <li class="dropdown dropdown-notification nav-item"><a href="#" data-toggle="dropdown" class="nav-link nav-link-label"><i class="ficon icon-mail6"></i><span class="tag tag-pill tag-default tag-info tag-default tag-up">8</span></a>
            <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
              <li class="dropdown-menu-header">
                <h6 class="dropdown-header m-0"><span class="grey darken-2">Messages</span><span class="notification-tag tag tag-default tag-info float-xs-right m-0">4 New</span></h6>
              </li>
              <li class="list-group scrollable-container"><a href="javascript:void(0)" class="list-group-item">
                  <div class="media">
                    <div class="media-left"><span class="avatar avatar-sm avatar-online rounded-circle"><img src="robust-assets/images/portrait/small/avatar-s-1.png" alt="avatar"><i></i></span></div>
                    <div class="media-body">
                      <h6 class="media-heading">Margaret Govan</h6>
                      <p class="notification-text font-small-3 text-muted">I like your portfolio, let's start the project.</p><small>
                        <time datetime="2015-06-11T18:29:20+08:00" class="media-meta text-muted">Today</time></small>
                    </div>
                  </div></a><a href="javascript:void(0)" class="list-group-item">
                  <div class="media">
                    <div class="media-left"><span class="avatar avatar-sm avatar-busy rounded-circle"><img src="robust-assets/images/portrait/small/avatar-s-2.png" alt="avatar"><i></i></span></div>
                    <div class="media-body">
                      <h6 class="media-heading">Bret Lezama</h6>
                      <p class="notification-text font-small-3 text-muted">I have seen your work, there is</p><small>
                        <time datetime="2015-06-11T18:29:20+08:00" class="media-meta text-muted">Tuesday</time></small>
                    </div>
                  </div></a><a href="javascript:void(0)" class="list-group-item">
                  <div class="media">
                    <div class="media-left"><span class="avatar avatar-sm avatar-online rounded-circle"><img src="robust-assets/images/portrait/small/avatar-s-3.png" alt="avatar"><i></i></span></div>
                    <div class="media-body">
                      <h6 class="media-heading">Carie Berra</h6>
                      <p class="notification-text font-small-3 text-muted">Can we have call in this week ?</p><small>
                        <time datetime="2015-06-11T18:29:20+08:00" class="media-meta text-muted">Friday</time></small>
                    </div>
                  </div></a><a href="javascript:void(0)" class="list-group-item">
                  <div class="media">
                    <div class="media-left"><span class="avatar avatar-sm avatar-away rounded-circle"><img src="robust-assets/images/portrait/small/avatar-s-6.png" alt="avatar"><i></i></span></div>
                    <div class="media-body">
                      <h6 class="media-heading">Eric Alsobrook</h6>
                      <p class="notification-text font-small-3 text-muted">We have project party this saturday night.</p><small>
                        <time datetime="2015-06-11T18:29:20+08:00" class="media-meta text-muted">last month</time></small>
                    </div>
                  </div></a></li>
              <li class="dropdown-menu-footer"><a href="javascript:void(0)" class="dropdown-item text-muted text-xs-center">Read all messages</a></li>
            </ul>
          </li>
          <li class="dropdown dropdown-user nav-item"><a href="#" data-toggle="dropdown" class="dropdown-toggle nav-link dropdown-user-link"><span class="avatar avatar-online"><img src="/robust-assets/images/portrait/small/avatar-s-1.png" alt="avatar"><i></i></span><span class="user-name">{{ Auth::user()->name }}</span></a>
            <div class="dropdown-menu dropdown-menu-right"><a href="#" class="dropdown-item"><i class="icon-head"></i> Edit Profile</a><a href="#" class="dropdown-item"><i class="icon-mail6"></i> My Inbox</a><a href="#" class="dropdown-item"><i class="icon-clipboard2"></i> Task</a><a href="#" class="dropdown-item"><i class="icon-calendar5"></i> Calender</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="{{ url('/logout') }}"
                  onclick="event.preventDefault();
                           document.getElementById('logout-form').submit();">
                  <i class="icon-power3"></i>Logout
              </a>
              <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                  {{ csrf_field() }}
              </form>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>
</nav>
<!-- END TOP NAVIGATION -->
<!-- MAIN NAVIGATION -->
<!-- main menu-->
<div id="main-menu" data-scroll-to-active="true" class="main-menu menu-dark menu-fixed menu-shadow menu-accordion">
  <!-- main menu header-->
  <div class="main-menu-header">
    <input type="text" placeholder="Search" class="menu-search form-control round"/>
  </div>
  <!-- / main menu header-->
  <!-- main menu content-->
  <div class="main-menu-content">
    <ul id="main-menu-navigation" data-menu="menu-navigation" class="navigation navigation-main">
      @include('partial.menu', ['items' => $menu_main->roots()])
    </ul>
    <div class="main-menu-footer footer-close"></div>
  </div>
  <!-- /main menu content-->
</div>
<!-- / main menu-->
<!-- END MAIN NAVIGATION -->
<!-- THE CONTENT -->
<div class="robust-content content container-fluid">
  <div class="content-wrapper">
    <div class="content-header row">
    </div>
    <div class="content-body">
      <div ng-view ng-if="appReady"></div>
    </div>
  </div>
</div>
<!-- END CONTENT -->
@endsection

@section('csses')
<!-- 
  <link rel="stylesheet" type="text/css" href="/robust-assets/css/plugins/forms/selects/select2.min.css">

-->
<link rel="stylesheet" href="/bower_components/select2/select2.css">
<link rel="stylesheet" type="text/css" href="/robust-assets/css/plugins/extensions/toastr.css">
<link rel="stylesheet" type="text/css" href="/robust-assets/js/plugins/angular-loading-bar/loading-bar.css">
<link rel="stylesheet" type="text/css" href="/robust-assets/js/plugins/angular-images/ng-image-input-with-preview.min.css">
<link rel="stylesheet" type="text/css" href="/bower_components/angular-datatables/dist/css/angular-datatables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.1.0/css/responsive.dataTables.min.css">
<link rel="stylesheet" href="/bower_components/bootstrap-daterangepicker/daterangepicker.css"/>
<link rel="stylesheet" href="/bower_components/ng-tags-input/ng-tags-input.min.css">
<link rel="stylesheet" href="/bower_components/moment-picker/dist/angular-moment-picker.min.css">
<link rel="stylesheet" href="/bower_components/moment-picker/dist/themes/material-ui.min.css">
<style>
[ng\:cloak], [ng-cloak], [data-ng-cloak], [x-ng-cloak], .ng-cloak, .x-ng-cloak, .hideTillLoaded {
  display: none !important;
}
</style>
@endsection

@section('jses')
<script type="text/javascript" src="/bower_components/select2/select2.js"></script>
<script type="text/javascript" src="/bower_components/angular-ui-select2/src/select2.js"></script>
<script src="/robust-assets/js/plugins/extensions/toastr.min.js" type="text/javascript"></script>
<script>
function setCsrf(){
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });
}
function reCsrf(){
    $.ajax({
       url: "/api/refreshCsrf",
       dataType:'json',
       async:false
     })
     .done(function(data) {
       $('meta[name="csrf-token"]').attr( 'content',data.token);
       setCsrf();
     });
}
</script>
<!-- Include the socket.io -->
<script src="/robust-assets/js/plugins/socket.io/socket.io.min.js"></script>
<script>
  var socket = io.connect('http://{{ getSettings('general-settings','socketioHost') }}:{{ getSettings('general-settings','socketioPort') }}',{
    query: 'uid=<?php echo Auth::user()->id; ?>'
  });
  // notification listener
  socket.on('notification', function (data) {
    var data = $.parseJSON(data);
    var permitted = false;
    data.permissions.forEach(function(permissionName){
      if(_.contains(permissions,permissionName)){
        permitted = true;
      }
    })
    if(permitted && data.sender_id != {{Auth::user()->id}}){
      toastr.info(data.message);
      toastr.options.timeout = 100
      toastr.options.onclick = function(){
        if(typeof(data.url) !== undefined && data.url != ""){
          $('body').injector().get('$location').path(data.url);
          $('body').scope().$apply();
        }
        //console.log(data.url);
      };
    }
  });
  // chat listener
  socket.on('chat:<?php echo Auth::user()->id; ?>',function(data){

  });
</script>

@endsection
