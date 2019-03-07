<!DOCTYPE html>
<html lang="en">
<head>
    <!-- META SECTION -->
    <title>Qareeb - Dashboard</title>
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
                <a href="/admin/dashboard">Qareeb - Super Admin Dashboard</a>
                <a href="#" class="x-navigation-control"></a>
            </li>

            <li class="xn-profile">
                <div class="profile">
                    <div class="profile-image">
                        <img src="/qareeb_admins/{{admin()->image}}" alt="Qareeb" style="width: 110px; height: 110px;"/>
                    </div>
                    <div class="profile-controls">
                        <a href="/admin/profile" class="profile-control-left" title="View Porfile"><span class="fa fa-user"></span></a>
                    </div>
                </div>
            </li>

            <li @if(Request::is('admin/dashboard')) class="active" @endif>
                <a href="/admin/dashboard"><span class="fa fa-dashboard"></span><span class="xn-text">Dashboard</span></a>
            </li>

            @if(admin()->hasPermissionTo('categories_operate'))
                <li class="xn-openable @if(Request::is('admin/admins/*') xor Request::is('admin/admin/*')) active @endif" >
                    <a href="#"><span class="fa fa-user-secret"></span><span class="xn-text">Admins</span></a>
                    <ul>
                        <li @if(Request::is('admin/admins/owners/index')) class="active" @endif>
                            <a href="/admin/admins/owners/index"><span class="fa fa-user-circle-o"></span><span class="xn-text">Owners</span></a>
                        </li>
                        <li @if(Request::is('admin/admins/system_admins/index')) class="active" @endif>
                            <a href="/admin/admins/system_admins/index"><span class="fa fa-user-secret"></span><span class="xn-text">System Admins</span></a>
                        </li>
                        <li @if(Request::is('admin/admins/app_managers/index')) class="active" @endif>
                            <a href="/admin/admins/app_managers/index"><span class="fa fa-mobile"></span><span class="xn-text">App Managers</span></a>
                        </li>
                        <li @if(Request::is('admin/admins/users_managers/index')) class="active" @endif>
                            <a href="/admin/admins/users_managers/index"><span class="fa fa-binoculars"></span><span class="xn-text">Users Managers</span></a>
                        </li>
                        <li @if(Request::is('admin/admins/service_desks/index')) class="active" @endif>
                            <a href="/admin/admins/service_desks/index"><span class="fa fa-desktop"></span><span class="xn-text">Service Desks</span></a>
                        </li>
                        <li @if(Request::is('admin/admins/users/index')) class="active" @endif>
                            <a href="/admin/admins/users/index"><span class="fa fa-users"></span><span class="xn-text">Users</span></a>
                        </li>
                    </ul>
                </li>
            @endif

            <li @if(Request::is('admin/addresses/*')xor Request::is('admin/address/*')) class="active" @endif>
                <a href="/admin/addresses/all"><span class="fa fa-flag"></span><span class="xn-text">Addresses</span></a>
            </li>

            <li @if(Request::is('admin/categories/*') xor Request::is('admin/category/*')) class="active" @endif>
                <a href="/admin/categories/all"><span class="fa fa-cubes"></span><span class="xn-text">Categories</span></a>
            </li>

            <li class="xn-openable @if(Request::is('admin/providers/*') xor Request::is('admin/provider/*')) active @endif" >
                <a href="#"><span class="fa fa-industry"></span><span class="xn-text">Providers</span></a>
                <ul>
                    <li @if(Request::is('admin/providers/active')) class="active" @endif>
                        <a href="/admin/providers/active"><span class="fa fa-check-square"></span><span class="xn-text">Active</span></a>
                    </li>
                    <li @if(Request::is('admin/providers/suspended')) class="active" @endif>
                        <a href="/admin/providers/suspended"><span class="fa fa-minus-square"></span><span class="xn-text">Suspended</span></a>
                    </li>
                </ul>
            </li>

            {{--<li class="xn-openable @if(Request::is('admin/individuals/*') xor Request::is('admin/individual/*')) active @endif" >--}}
                {{--<a href="#"><span class="fa fa-handshake-o"></span><span class="xn-text">Individuals</span></a>--}}
                {{--<ul>--}}
                    {{--<li @if(Request::is('admin/individuals/active')) class="active" @endif>--}}
                        {{--<a href="/admin/individuals/active"><span class="fa fa-check-square"></span><span class="xn-text">Active</span></a>--}}
                    {{--</li>--}}
                    {{--<li @if(Request::is('admin/individuals/suspended')) class="active" @endif>--}}
                        {{--<a href="/admin/individuals/suspended"><span class="fa fa-minus-square"></span><span class="xn-text">Suspended</span></a>--}}
                    {{--</li>--}}
                {{--</ul>--}}
            {{--</li>--}}

            <li class="xn-openable @if(Request::is('admin/companies/*') xor Request::is('admin/company/*')) active @endif" >
                <a href="#"><span class="fa fa-building"></span><span class="xn-text">Companies</span></a>
                <ul>
                    <li @if(Request::is('admin/companies/active')) class="active" @endif>
                        <a href="/admin/companies/active"><span class="fa fa-check-square"></span><span class="xn-text">Active</span></a>
                    </li>
                    <li @if(Request::is('admin/companies/suspended')) class="active" @endif>
                        <a href="/admin/companies/suspended"><span class="fa fa-minus-square"></span><span class="xn-text">Suspended</span></a>
                    </li>
                </ul>
            </li>

            {{--<li class="xn-openable @if(Request::is('admin/users/*') xor Request::is('admin/user/*')) active @endif" >--}}
                {{--<a href="#"><span class="fa fa-users"></span><span class="xn-text">Users</span></a>--}}
                {{--<ul>--}}
                    {{--<li @if(Request::is('admin/users/active')) class="active" @endif>--}}
                        {{--<a href="/admin/users/active"><span class="fa fa-check-square"></span><span class="xn-text">Active</span></a>--}}
                    {{--</li>--}}
                    {{--<li @if(Request::is('admin/users/suspended')) class="active" @endif>--}}
                        {{--<a href="/admin/users/suspended"><span class="fa fa-minus-square"></span><span class="xn-text">Suspended</span></a>--}}
                    {{--</li>--}}
                {{--</ul>--}}
            {{--</li>--}}

            <li @if(Request::is('admin/collaborations') xor Request::is('admin/collaboration/*')) class="active" @endif>
                <a href="/admin/collaborations"><span class="fa fa-handshake-o"></span><span class="xn-text">Collaborations</span></a>
            </li>

            <li class="xn-openable @if(Request::is('settings/*')) active @endif">
                <a href="#"><span class="xn-text"><span class="fa fa-cogs"></span> Application Settings</span></a>
                <ul>
                    <li @if(Request::is('admin/settings/about')) class="active" @endif>
                        <a href="/admin/settings/about"><span class="xn-text"><span class="fa fa-info-circle"></span>About Us</span></a>
                    </li>

                    {{--<li @if(Request::is('admin/settings/notifications')) class="active" @endif>--}}
                        {{--<a href="/admin/settings/notifications"><span class="xn-text">الإشعارات العامة</span><span class="fa fa-newspaper-o"></span></a>--}}
                    {{--</li>--}}
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
                            <a href="/admin/logout" class="btn btn-success btn-lg">Yes</a>
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






