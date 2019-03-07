<!DOCTYPE html>
<html lang="en">
<head>
    <!-- META SECTION -->
    <title>Qareeb - Provider Dashboard</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link rel="icon" href="{{asset('admin/assets/images/users/avatar.jpg')}}" type="image/x-icon" />
    <!-- END META SECTION -->

    <!-- CSS INCLUDE -->
    <link rel="stylesheet" type="text/css" id="theme" href="{{asset('admin/css/theme-default.css')}}"/>
    <script type="text/javascript" src="{{asset('admin/js/plugins/jquery/jquery.min.js')}}"></script>
    <!-- START PLUGINS -->
    <script type="text/javascript" src="{{asset('admin/js/plugins/jquery/jquery-ui.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('admin/js/plugins/bootstrap/bootstrap.min.js')}}"></script>
    <!-- END PLUGINS -->
    <!-- EOF CSS INCLUDE -->

    <style>
        .image_radius
        {
            height: 50px;
            width: 50px;
            border: 1px solid #29B2E1;
            border-radius: 100px;
            box-shadow: 2px 2px 2px darkcyan;
        }

         .input-group-addon {
             border-color: #33414e00 !important;
             background-color: #33414e00 !important;
             font-size: 13px;
             padding: 0px 0px 0px 3px;
             line-height: 26px;
             color: #FFF;
             text-align: center;
             min-width: 36px;
         }
        .link a:hover
        {
            text-decoration: none;
        }
    </style>
</head>
<body>
<!-- START PAGE CONTAINER -->
<div class="page-container page-mode-ltr page-content-ltr">
    <!-- START PAGE SIDEBAR -->
    <div class="page-sidebar page-sidebar-fixed scroll">
        <!-- START X-NAVIGATION -->
        <ul class="x-navigation">
            <li class="xn-logo">
                <a href="/provider/dashboard">Qareeb - Provider Dashboard</a>
                <a href="#" class="x-navigation-control"></a>
            </li>

            <li class="xn-profile">
                <div class="profile">
                    <div class="profile-image">
                        <img src="/providers/admins/{{provider()->image}}" alt="Qareeb"/>
                    </div>
                    <div class="profile-controls">
                        <a href="/provider/profile" class="profile-control-left" title="View Profile"><span class="fa fa-user"></span></a>
                    </div>
                </div>
            </li>

            <li @if(Request::is('provider/dashboard')) class="active" @endif>
                <a href="/provider/dashboard"><span class="fa fa-dashboard"></span><span class="xn-text">Dashboard</span></a>
            </li>

            <li @if(Request::is('provider/my_provider')) class="active" @endif>
                <a href="/provider/my_provider"><span class="fa fa-info-circle"></span><span class="xn-text">My Provider</span></a>
            </li>

            @if(provider()->hasPermissionTo('providers_operate'))
                <li @if(Request::is('provider/info')) class="active" @endif>
                    <a href="/provider/info"><span class="fa fa-info-circle"></span><span class="xn-text">Provider Info</span></a>
                </li>
            @endif

            @if(provider()->hasPermissionTo('admins'))
                <li class="xn-openable @if(Request::is('provider/admins/*') xor Request::is('provider/admin/*')) active @endif" >
                    <a href="#"><span class="fa fa-user-secret"></span><span class="xn-text">Admins</span></a>
                    <ul>
                        <li @if(Request::is('provider/admins/owners/index')) class="active" @endif>
                            <a href="/provider/admins/owners/index"><span class="fa fa-user-circle-o"></span><span class="xn-text">Owners</span></a>
                        </li>
                        <li @if(Request::is('provider/admins/system_admins/index')) class="active" @endif>
                            <a href="/provider/admins/system_admins/index"><span class="fa fa-user-secret"></span><span class="xn-text">System Admins</span></a>
                        </li>
                        <li @if(Request::is('provider/admins/app_managers/index')) class="active" @endif>
                            <a href="/provider/admins/app_managers/index"><span class="fa fa-mobile"></span><span class="xn-text">App Managers</span></a>
                        </li>
                        <li @if(Request::is('provider/admins/technicians_managers/index')) class="active" @endif>
                            <a href="/provider/admins/technicians_managers/index"><span class="fa fa-binoculars"></span><span class="xn-text">Technicians Managers</span></a>
                        </li>
                        <li @if(Request::is('provider/admins/service_desks/index')) class="active" @endif>
                            <a href="/provider/admins/service_desks/index"><span class="fa fa-desktop"></span><span class="xn-text">Service Desks</span></a>
                        </li>
                        <li @if(Request::is('provider/admins/warehouse_desks/index')) class="active" @endif>
                            <a href="/provider/admins/warehouse_desks/index"><span class="fa fa-cubes"></span><span class="xn-text">Warehouse Desks</span></a>
                        </li>
                        <li @if(Request::is('provider/admins/users/index')) class="active" @endif>
                            <a href="/provider/admins/users/index"><span class="fa fa-users"></span><span class="xn-text">Users</span></a>
                        </li>
                    </ul>
                </li>
            @endif

            @if(provider()->hasPermissionTo('services_fees'))
                <li @if(Request::is('provider/services/fees/view')) class="active" @endif>
                    <a href="/provider/services/fees/view"><span class="fa fa-money"></span><span class="xn-text">Services Fees</span></a>
                </li>
            @endif

            <li @if(Request::is('provider/collaborations') xor Request::is('provider/collaboration/*')) class="active" @endif>
                <a href="/provider/collaborations"><span class="fa fa-handshake-o"></span><span class="xn-text">Collaborations</span></a>
            </li>


            <li class="xn-openable @if(Request::is('provider/orders/*') xor Request::is('provider/order/*')) active @endif">
                <a href="#"><span class="fa fa-truck"></span><span class="xn-text">Orders</span></a>
                <ul>
                    <li @if(Request::is('provider/orders/urgent')) class="active" @endif>
                        <a href="/provider/orders/urgent"><span class="fa fa-check-square"></span><span class="xn-text">Urgent</span></a>
                    </li>
                    <li @if(Request::is('provider/orders/scheduled')) class="active" @endif>
                        <a href="/provider/orders/scheduled"><span class="fa fa-clock-o"></span><span class="xn-text">Scheduled</span></a>
                    </li>
                    <li @if(Request::is('provider/orders/re_scheduled')) class="active" @endif>
                        <a href="/provider/orders/re_scheduled"><span class="fa fa-minus-circle"></span><span class="xn-text">Re-Scheduled</span></a>
                    </li>
                    <li @if(Request::is('provider/orders/canceled')) class="active" @endif>
                        <a href="/provider/orders/canceled"><span class="fa fa-times-circle"></span><span class="xn-text">Canceled</span></a>
                    </li>
                </ul>
            </li>


            {{--<li class="xn-openable @if(Request::is('provider/admins/*') xor Request::is('provider/admins/*')) active @endif" >--}}
                {{--<a href="#"><span class="fa fa-user-secret"></span><span class="xn-text">Admins</span></a>--}}
                {{--<ul>--}}
                    {{--<li @if(Request::is('provider/admins/active')) class="active" @endif>--}}
                        {{--<a href="/provider/admins/active"><span class="fa fa-check-square"></span><span class="xn-text">Active</span></a>--}}
                    {{--</li>--}}
                    {{--<li @if(Request::is('provider/admins/suspended')) class="active" @endif>--}}
                        {{--<a href="/provider/admins/suspended"><span class="fa fa-minus-square"></span><span class="xn-text">Suspended</span></a>--}}
                    {{--</li>--}}
                {{--</ul>--}}
            {{--</li>--}}

            <li @if(Request::is('provider/warehouse/*')) class="active" @endif>
                <a href="/provider/warehouse/all"><span class="fa fa-cubes"></span><span class="xn-text">Warehouse</span></a>
            </li>

            <li @if(Request::is('provider/warehouse_requests/*')) class="active" @endif>
                <a href="/provider/warehouse_requests"><span class="fa fa-question-circle-o"></span><span class="xn-text">Warehouse Requests</span></a>
            </li>

            <li class="xn-openable @if(Request::is('provider/technicians/*') xor Request::is('provider/technician/*')) active @endif" >
                <a href="#"><span class="fa fa-wrench"></span><span class="xn-text">Technicians</span></a>
                <ul>
                    <li @if(Request::is('provider/technicians/active')) class="active" @endif>
                        <a href="/provider/technicians/active"><span class="fa fa-check-square"></span><span class="xn-text">Active</span></a>
                    </li>
                    <li @if(Request::is('provider/technicians/suspended')) class="active" @endif>
                        <a href="/provider/technicians/suspended"><span class="fa fa-minus-square"></span><span class="xn-text">Suspended</span></a>
                    </li>
                    <li @if(Request::is('provider/technicians/statistics')) class="active" @endif>
                        <a href="/provider/technicians/statistics"><span class="fa fa-area-chart"></span><span class="xn-text">Statistics</span></a>
                    </li>
                </ul>
            </li>


            <li @if(Request::is('provider/rotations/*')) class="active" @endif>
                <a href="/provider/rotations/index"><span class="fa fa-repeat"></span><span class="xn-text">Rotations</span></a>
            </li>

        </ul>
        <!-- END X-NAVIGATION -->
    </div>
    <!-- END PAGE SIDEBAR -->

    <!-- PAGE CONTENT -->
    <div class="page-content">

        <!-- START X-NAVIGATION VERTICAL -->
        <ul class="x-navigation x-navigation-horizontal x-navigation-panel">
            <!-- POWER OFF -->
            <li class="xn-icon-button pull-right last">
                <a href="#" class="mb-control" data-box="#mb-signout" title="Logout"><span class="fa fa-power-off"></span></a>
            </li>
            <!-- END POWER OFF -->
        </ul>
        <!-- END X-NAVIGATION VERTICAL -->

        <!-- MESSAGE BOX-->
        <div class="message-box animated fadeIn" data-sound="alert" id="mb-signout">
            <div class="mb-container">
                <div class="mb-middle">
                    <div class="mb-title"><span class="fa fa-sign-out"></span> Log <strong>Out</strong> ?</div>
                    <div class="mb-content">
                        <p>Are you sure you want to log out?</p>
                        <p>Press No if you want to continue work. Press Yes to logout current user.</p>
                    </div>
                    <div class="mb-footer">
                        <div class="pull-right">
                            <a href="/provider/logout" class="btn btn-success btn-lg">Yes</a>
                            <button class="btn btn-default btn-lg mb-control-close">No</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END MESSAGE BOX-->

     @yield('content')





<!-- START PRELOADS -->
<audio id="audio-alert" src="{{asset('admin/audio/alert.mp3')}}" preload="auto"></audio>
<audio id="audio-fail" src="{{asset('admin/audio/fail.mp3')}}" preload="auto"></audio>
<!-- END PRELOADS -->


<!-- THIS PAGE PLUGINS -->
<script type='text/javascript' src="{{asset('admin/js/plugins/icheck/icheck.min.js')}}"></script>
<script type="text/javascript" src="{{asset('admin/js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js')}}"></script>

<script type='text/javascript' src='{{asset('admin/js/plugins/icheck/icheck.min.js')}}'></script>
<script type="text/javascript" src="{{asset('admin/js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js')}}"></script>

<script type="text/javascript" src="{{asset('admin/js/plugins/datatables/jquery.dataTables.min.js')}}"></script>

<script type="text/javascript" src="{{asset('admin/js/plugins/owl/owl.carousel.min.js')}}"></script>
<!-- END PAGE PLUGINS -->

<!-- START TEMPLATE -->
<script type="text/javascript" src="{{asset('admin/js/plugins.js')}}"></script>
<script type="text/javascript" src="{{asset('admin/js/actions.js')}}"></script>


<script type="text/javascript" src="{{asset('admin/js/plugins/bootstrap/bootstrap-datepicker.js')}}"></script>
<script type="text/javascript" src="{{asset('admin/js/plugins/bootstrap/bootstrap-file-input.js')}}"></script>
<script type="text/javascript" src="{{asset('admin/js/plugins/bootstrap/bootstrap-select.js')}}"></script>
<script type="text/javascript" src="{{asset('admin/js/plugins/tagsinput/jquery.tagsinput.min.js')}}"></script>
<!-- END THIS PAGE PLUGINS -->
<!-- END SCRIPTS -->
</body>
</html>






