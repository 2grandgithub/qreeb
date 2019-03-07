<!DOCTYPE html>
<html lang="en">
<head>
    <!-- META SECTION -->
    <title>Qareeb - Company Dashboard</title>
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
                <a href="/company/dashboard">Qareeb - Company Dashboard</a>
                <a href="#" class="x-navigation-control"></a>
            </li>

            <li class="xn-profile">
                <div class="profile">
                    <div class="profile-image">
                        <img src="/companies/admins/{{company()->image}}" alt="{{company()->en_name}}" style="width: 110px; height: 110px;"/>
                    </div>
                    <div class="profile-controls">
                        <a href="/company/profile" class="profile-control-left" title="View Profile"><span class="fa fa-user"></span></a>
                    </div>
                </div>
            </li>

            <li @if(Request::is('company/dashboard')) class="active" @endif>
                <a href="/company/dashboard"><span class="fa fa-dashboard"></span><span class="xn-text">Dashboard</span></a>
            </li>

            <li @if(Request::is('company/my_company')) class="active" @endif>
                <a href="/company/my_company"><span class="fa fa-info-circle"></span><span class="xn-text">My Company</span></a>
            </li>

            @if(company()->hasPermissionTo('companies_operate'))
                <li @if(Request::is('company/info')) class="active" @endif>
                    <a href="/company/info"><span class="fa fa-info-circle"></span><span class="xn-text">Company Info</span></a>
                </li>
            @endif

            @if(company()->hasPermissionTo('admins'))
                <li class="xn-openable @if(Request::is('company/admins/*') xor Request::is('company/admin/*')) active @endif" >
                    <a href="#"><span class="fa fa-user-secret"></span><span class="xn-text">Admins</span></a>
                    <ul>
                        <li @if(Request::is('company/admins/owners/index')) class="active" @endif>
                            <a href="/company/admins/owners/index"><span class="fa fa-user-circle-o"></span><span class="xn-text">Owners</span></a>
                        </li>
                        <li @if(Request::is('company/admins/system_admins/index')) class="active" @endif>
                            <a href="/company/admins/system_admins/index"><span class="fa fa-user-secret"></span><span class="xn-text">System Admins</span></a>
                        </li>
                        <li @if(Request::is('company/admins/app_managers/index')) class="active" @endif>
                            <a href="/company/admins/app_managers/index"><span class="fa fa-mobile"></span><span class="xn-text">App Managers</span></a>
                        </li>
                        <li @if(Request::is('company/admins/users_managers/index')) class="active" @endif>
                            <a href="/company/admins/users_managers/index"><span class="fa fa-binoculars"></span><span class="xn-text">Users Managers</span></a>
                        </li>
                        <li @if(Request::is('company/admins/service_desks/index')) class="active" @endif>
                            <a href="/company/admins/service_desks/index"><span class="fa fa-desktop"></span><span class="xn-text">Service Desks</span></a>
                        </li>
                        <li @if(Request::is('company/admins/users/index')) class="active" @endif>
                            <a href="/company/admins/users/index"><span class="fa fa-users"></span><span class="xn-text">Users</span></a>
                        </li>
                    </ul>
                </li>
            @endif

            <li class="xn-openable @if(Request::is('company/sub_companies/*') xor Request::is('company/sub_company/*')) active @endif" >
                <a href="#"><span class="fa fa-building"></span><span class="xn-text">Sub Companies</span></a>
                <ul>
                    <li @if(Request::is('company/sub_companies/active')) class="active" @endif>
                        <a href="/company/sub_companies/active"><span class="fa fa-check-square"></span><span class="xn-text">Active</span></a>
                    </li>
                    <li @if(Request::is('company/sub_companies/suspended')) class="active" @endif>
                        <a href="/company/sub_companies/suspended"><span class="fa fa-minus-square"></span><span class="xn-text">Suspended</span></a>
                    </li>
                </ul>
            </li>

            <li @if(Request::is('company/collaborations') xor Request::is('company/collaboration/*')) class="active" @endif>
                <a href="/company/collaborations"><span class="fa fa-handshake-o"></span><span class="xn-text">Collaborations</span></a>
            </li>

            <li class="xn-openable @if(Request::is('company/users/*') xor Request::is('company/user/*')) active @endif" >
                <a href="#"><span class="fa fa-user"></span><span class="xn-text">Users</span></a>
                <ul>
                    <li @if(Request::is('company/users/active')) class="active" @endif>
                        <a href="/company/users/active"><span class="fa fa-check-square"></span><span class="xn-text">Active</span></a>
                    </li>
                    <li @if(Request::is('company/users/suspended')) class="active" @endif>
                        <a href="/company/users/suspended"><span class="fa fa-minus-square"></span><span class="xn-text">Suspended</span></a>
                    </li>
                </ul>
            </li>

            <li class="xn-openable @if(Request::is('company/orders/*') xor Request::is('company/order/*')) active @endif">
                <a href="#"><span class="fa fa-truck"></span><span class="xn-text">Orders</span></a>
                <ul>
                    <li @if(Request::is('company/orders/urgent')) class="active" @endif>
                        <a href="/company/orders/urgent"><span class="fa fa-check-square"></span><span class="xn-text">Urgent</span></a>
                    </li>
                    <li @if(Request::is('company/orders/scheduled')) class="active" @endif>
                        <a href="/company/orders/scheduled"><span class="fa fa-clock-o"></span><span class="xn-text">Scheduled</span></a>
                    </li>
                    <li @if(Request::is('company/orders/re_scheduled')) class="active" @endif>
                        <a href="/company/orders/re_scheduled"><span class="fa fa-minus-circle"></span><span class="xn-text">Re-Scheduled</span></a>
                    </li>
                    <li @if(Request::is('company/orders/canceled')) class="active" @endif>
                        <a href="/company/orders/canceled"><span class="fa fa-times-circle"></span><span class="xn-text">Canceled</span></a>
                    </li>
                </ul>
            </li>


            <li class="xn-openable @if(Request::is('company/item_requests/*') xor Request::is('company/item_request/*')) active @endif">
                <a href="#"><span class="fa fa-question-circle-o"></span><span class="xn-text">Item Requests</span></a>
                <ul>
                    <li @if(Request::is('company/item_requests/awaiting')) class="active" @endif>
                        <a href="/company/item_requests/awaiting"><span class="fa fa-check-square"></span><span class="xn-text">Awaiting</span></a>
                    </li>
                    <li @if(Request::is('company/item_requests/confirmed')) class="active" @endif>
                        <a href="/company/item_requests/confirmed"><span class="fa fa-clock-o"></span><span class="xn-text">Confirmed</span></a>
                    </li>
                    <li @if(Request::is('company/item_requests/declined')) class="active" @endif>
                        <a href="/company/item_requests/declined"><span class="fa fa-minus-circle"></span><span class="xn-text">Declined</span></a>
                    </li>
                </ul>
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
                            <a href="/company/logout" class="btn btn-success btn-lg">Yes</a>
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




<script type='text/javascript' src='{{asset('admin/js/plugins/icheck/icheck.min.js')}}'></script>
<script type="text/javascript" src="{{asset('admin/js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js')}}"></script>

<script type="text/javascript" src="{{asset('admin/js/plugins/datatables/jquery.dataTables.min.js')}}"></script>

<script type="text/javascript" src="{{asset('admin/js/plugins/owl/owl.carousel.min.js')}}"></script>

<script type='text/javascript' src="{{asset('admin/js/plugins/icheck/icheck.min.js')}}"></script>
<script type="text/javascript" src="{{asset('admin/js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js')}}"></script>

<script type="text/javascript" src="{{asset('admin/js/plugins/blueimp/jquery.blueimp-gallery.min.js')}}"></script>
<script type="text/javascript" src="{{asset('admin/js/plugins/dropzone/dropzone.min.js')}}"></script>
<script type="text/javascript" src="{{asset('admin/js/plugins/icheck/icheck.min.js')}}"></script>

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






