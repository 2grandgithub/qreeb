@extends('admin.layouts.app')
@section('content')
    <!-- PAGE CONTENT WRAPPER -->
    <style>
        .compare
        {
            background-color: #c52d0b !important;
        }
        .progress-bar-danger2{

            background-color: #0057af !important;
        }
    </style>

    @if(admin()->hasPermissionTo('statistics_general'))
        <div class="page-content-wrap" style="margin-top: 10px;">
        <!-- START WIDGETS -->
            <div class="row">

            <div class="col-md-3">
                <!-- START WIDGET -->
                <div class="widget widget-default widget-item-icon">
                    <div class="widget-item-left">
                        <span class="fa fa-mail-reply"></span>
                    </div>
                    <div class="widget-data">
                        <div class="widget-int num-count">{{$monthly_orders_count}}</div>
                        <div class="widget-title">Monthly Orders</div>
                        <div class="widget-subtitle">{{$yearly_orders_count}} In this year</div>
                    </div>
                </div>
                <!-- END WIDGET -->
            </div>

            <div class="col-md-3">
                <!-- START WIDGET -->
                <div class="widget widget-default widget-item-icon">
                    <div class="widget-item-left">
                        <span class="fa fa-history"></span>
                    </div>
                    <div class="widget-data">
                        <div class="widget-int num-count">{{$monthly_open}}</div>
                        <div class="widget-title">Monthly Open Orders</div>
                        <div class="widget-subtitle">Out of {{$monthly_orders_count}} In this month</div>
                        <div class="widget-subtitle">Out of {{$yearly_orders_count}} In this year</div>
                    </div>
                </div>
                <!-- END WIDGET -->
            </div>


            <div class="col-md-3">
                <!-- START WIDGET -->
                <div class="widget widget-default widget-item-icon">
                    <div class="widget-item-left">
                        <span class="fa fa-check-circle"></span>
                    </div>
                    <div class="widget-data">
                        <div class="widget-int num-count">{{$monthly_closed}}</div>
                        <div class="widget-title">Monthly Closed Orders</div>
                        <div class="widget-subtitle">Out of {{$monthly_orders_count}} In this month</div>
                        <div class="widget-subtitle">Out of {{$yearly_orders_count}} In this year</div>
                    </div>
                </div>
                <!-- END WIDGET -->
            </div>

            <div class="col-md-3">
                <!-- START WIDGET -->
                <div class="widget widget-default widget-item-icon">
                    <div class="widget-item-left">
                        <span class="fa fa-times-circle"></span>
                    </div>
                    <div class="widget-data">
                        <div class="widget-int num-count">{{$monthly_canceled}}</div>
                        <div class="widget-title">Monthly Canceled Orders</div>
                        <div class="widget-subtitle">Out of {{$monthly_orders_count}} In this month</div>
                        <div class="widget-subtitle">Out of {{$yearly_orders_count}} In this year</div>
                    </div>
                </div>
                <!-- END WIDGET -->
            </div>


        </div>
        <!-- END WIDGETS -->
        <div class="row">
            <div class="col-md-6">

                <!-- START SALES BLOCK -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="panel-title-box">
                            <h3>Maintenance Orders</h3>
                            <span>Activities since {{$this_month->format('l j F Y')}}</span>
                        </div>
                        <ul class="panel-controls panel-controls-title">
                            <li><a href="#" class="panel-fullscreen rounded"><span class="fa fa-expand"></span></a></li>
                        </ul>

                    </div>
                    <div class="panel-body">
                        <div class="row stacked">
                            <div class="col-md-12">
                                <div class="progress-list">
                                    <div class="pull-left"><strong>On going</strong></div>
                                    <div class="pull-right">{{$monthly_open}}</div>
                                    <div class="progress progress-small progress-striped @if($monthly_open > 0) active @endif">
                                        <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">100%</div>
                                    </div>
                                </div>
                                <div class="progress-list">
                                    <div class="pull-left"><strong>Completion Rate</strong></div>
                                    @if($monthly_orders_count > 0)
                                        <div class="pull-right">{{$monthly_closed}}/{{$monthly_orders_count}} as {{round($monthly_closed / $monthly_orders_count * 100, 1)}}%</div>
                                        <div class="progress progress-small progress-striped">
                                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: {{$monthly_closed / $monthly_orders_count * 100}}%;">{{$monthly_closed / $monthly_orders_count * 100}}%</div>
                                        </div>
                                    @else
                                        <div class="pull-right">{{$monthly_closed}}/0 as 0%</div>
                                        <div class="progress progress-small progress-striped">
                                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">0%</div>
                                        </div>
                                    @endif
                                </div>
                                <div class="progress-list">
                                    <div class="pull-left"><strong class="text-danger">Canceled Orders</strong></div>

                                    @if($monthly_orders_count > 0)
                                        <div class="pull-right">{{$monthly_canceled}}/{{$monthly_orders_count}} as {{round($monthly_canceled / $monthly_orders_count * 100, 1)}}%</div>
                                        <div class="progress progress-small progress-striped">
                                            <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: {{$monthly_canceled / $monthly_orders_count * 100}}%;">{{$monthly_canceled / $monthly_orders_count * 100}}%</div>
                                        </div>
                                    @else
                                        <div class="pull-right">0/0 as 0%</div>
                                        <div class="progress progress-small progress-striped">
                                            <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">0%</div>
                                        </div>
                                    @endif

                                </div>
                                <div class="progress-list">
                                    <div class="pull-left"><strong class="text-danger">Declination Ratio</strong></div><br/>
                                    <div class="pull-left">User ( {{$monthly_canceled_user}} )</div>
                                    <div class="pull-right">( {{$monthly_canceled_tech}} ) Technician</div>

                                    <div class="progress progress compare">
                                        @if($monthly_orders_count > 0 && $monthly_canceled > 0)
                                            <div class="progress-bar progress-bar-danger progress-bar-danger2" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: {{$monthly_canceled_user / $monthly_canceled * 100}}%;"></div>
                                        @else
                                            <div class="progress-bar progress-bar-danger progress-bar-danger2" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div id="dashboard-map-seles" style="width: 100%;"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END SALES BLOCK -->
            </div>
            <div class="col-md-6">

                <!-- START SALES BLOCK -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="panel-title-box">
                            <h3>Maintenance Orders</h3>
                            <span>Activities in {{$this_year->format('Y')}}</span>
                        </div>
                        <ul class="panel-controls panel-controls-title">
                            <li><a href="#" class="panel-fullscreen rounded"><span class="fa fa-expand"></span></a></li>
                        </ul>

                    </div>
                    <div class="panel-body">
                        <div class="row stacked">
                            <div class="col-md-12">
                                <div class="progress-list">
                                    <div class="pull-left"><strong>On going</strong></div>
                                    <div class="pull-right">{{$yearly_open}}</div>
                                    <div class="progress progress-small progress-striped @if($yearly_open > 0) active @endif">
                                        <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">100%</div>
                                    </div>
                                </div>
                                <div class="progress-list">
                                    <div class="pull-left"><strong>Completion Rate</strong></div>
                                    @if($yearly_orders_count > 0)
                                        <div class="pull-right">{{$yearly_closed}}/{{$yearly_orders_count}} as {{round($yearly_closed / $yearly_orders_count * 100, 1)}}%</div>
                                        <div class="progress progress-small progress-striped">
                                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: {{$yearly_closed / $yearly_orders_count * 100}}%;">{{$yearly_closed / $yearly_orders_count * 100}}%</div>
                                        </div>
                                    @else
                                        <div class="pull-right">0 as 0%</div>
                                        <div class="progress progress-small progress-striped">
                                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">0%</div>
                                        </div>
                                    @endif
                                </div>
                                <div class="progress-list">
                                    <div class="pull-left"><strong class="text-danger">Canceled Orders</strong></div>

                                    @if($yearly_orders_count > 0)
                                        <div class="pull-right">{{$yearly_canceled}}/{{$yearly_orders_count}} as {{round($yearly_canceled / $yearly_orders_count * 100, 1)}}%</div>
                                        <div class="progress progress-small progress-striped">
                                            <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: {{$yearly_canceled / $yearly_orders_count * 100}}%;">{{$yearly_canceled / $yearly_orders_count * 100}}%</div>
                                        </div>
                                    @else
                                        <div class="pull-right">{{$yearly_canceled}}/0 as 0%</div>
                                        <div class="progress progress-small progress-striped">
                                            <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">0%</div>
                                        </div>
                                    @endif

                                </div>
                                <div class="progress-list">
                                    <div class="pull-left"><strong class="text-danger">Declination Ratio</strong></div><br/>
                                    <div class="pull-left">User ( {{$yearly_canceled_user}} )</div>
                                    <div class="pull-right">( {{$yearly_canceled_tech}} ) Technician</div>

                                    <div class="progress progress compare">
                                        @if($yearly_orders_count > 0 && $yearly_canceled > 0)
                                            <div class="progress-bar progress-bar-danger progress-bar-danger2" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: {{$yearly_canceled_user / $yearly_canceled * 100}}%;"></div>
                                        @else
                                            <div class="progress-bar progress-bar-danger progress-bar-danger2" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                        @endif                                        </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div id="dashboard-map-seles" style="width: 100%;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END SALES BLOCK -->

        </div>
        <!-- START WIDGETS -->
        <div class="row">

            <div class="col-md-3">
                <!-- START WIDGET -->
                <div class="widget widget-default widget-item-icon">
                    <div class="widget-item-left">
                        <span class="fa fa-mail-reply"></span>
                    </div>
                    <div class="widget-data">
                        <div class="widget-int num-count">{{$monthly_parts_orders_count}}</div>
                        <div class="widget-title">Monthly Orders With Spare parts</div>
                        <div class="widget-subtitle">{{$yearly_parts_orders_count}} In this year</div>
                    </div>
                </div>
                <!-- END WIDGET -->
            </div>

            <div class="col-md-3">
                <!-- START WIDGET -->
                <div class="widget widget-default widget-item-icon">
                    <div class="widget-item-left">
                        <span class="fa fa-cubes"></span>
                    </div>
                    <div class="widget-data">
                        <div class="widget-int num-count">{{$monthly_parts_count}}</div>
                        <div class="widget-title">Monthly Spare Parts Requested</div>
                        <div class="widget-subtitle">Out of {{$yearly_parts_count}} In this year</div>
                    </div>
                </div>
                <!-- END WIDGET -->
            </div>

            @if(admin()->hasPermissionTo('statistics_financial'))
                <div class="col-md-3">
                    <!-- START WIDGET -->
                    <div class="widget widget-default widget-item-icon">
                        <div class="widget-item-left">
                            <span class="fa fa-money"></span>
                        </div>
                        <div class="widget-data">
                            <div class="widget-int num-count">{{$monthly_parts_prices}} S.R</div>
                            <div class="widget-title">Monthly Spare Parts Total Price</div>
                            <div class="widget-subtitle">Out of {{$yearly_parts_prices}} S.R In this year</div>
                        </div>
                    </div>
                    <!-- END WIDGET -->
                </div>

                <div class="col-md-3">
                    <!-- START WIDGET -->
                    <div class="widget widget-default widget-item-icon">
                        <div class="widget-item-left">
                            <span class="fa fa-money"></span>
                        </div>
                        <div class="widget-data">
                            <div class="widget-int num-count">{{$monthly_revenue}}</div>
                            <div class="widget-title">Monthly Orders Costs</div>
                            <div class="widget-subtitle">Out of {{$yearly_revenue}} In this year</div>
                        </div>
                    </div>
                    <!-- END WIDGET -->
                </div>
            @endif


            <div class="col-md-3">
                <!-- START WIDGET MESSAGES -->
                <div class="widget widget-default widget-item-icon">
                    <div class="widget-item-left">
                        <span class="fa fa-thumbs-up"></span>
                    </div>
                    <div class="widget-data">
                        <div class="widget-int num-count">{{$monthly_rate_commitment}} of 5</div>
                        <div class="widget-title link">Commitment </div>
                        <div class="widget-subtitle link">{{$yearly_rate_commitment}} of 5 in this year</div>
                    </div>
                </div>
                <!-- END WIDGET MESSAGES -->
            </div>


            <div class="col-md-3">
                <!-- START WIDGET MESSAGES -->
                <div class="widget widget-default widget-item-icon">
                    <div class="widget-item-left">
                        <span class="fa fa-thumbs-up"></span>
                    </div>
                    <div class="widget-data">
                        <div class="widget-int num-count">{{$monthly_rate_appearance}} of 5</div>
                        <div class="widget-title link">Appearance</div>
                        <div class="widget-subtitle link">{{$yearly_rate_appearance}} of 5 in this year</div>
                    </div>
                </div>
                <!-- END WIDGET MESSAGES -->
            </div>


            <div class="col-md-3">
                <!-- START WIDGET MESSAGES -->
                <div class="widget widget-default widget-item-icon">
                    <div class="widget-item-left">
                        <span class="fa fa-thumbs-up"></span>
                    </div>
                    <div class="widget-data">
                        <div class="widget-int num-count">{{$monthly_rate_performance}} of 5</div>
                        <div class="widget-title link">Performance</div>
                        <div class="widget-subtitle link">{{$yearly_rate_cleanliness}} of 5 in this year</div>
                    </div>
                </div>
                <!-- END WIDGET MESSAGES -->
            </div>


            <div class="col-md-3">
                <!-- START WIDGET MESSAGES -->
                <div class="widget widget-default widget-item-icon">
                    <div class="widget-item-left">
                        <span class="fa fa-thumbs-up"></span>
                    </div>
                    <div class="widget-data">
                        <div class="widget-int num-count">{{$monthly_rate_cleanliness}} of 5</div>
                        <div class="widget-title link">Cleanliness</div>
                        <div class="widget-subtitle link">{{$monthly_rate_cleanliness}} of 5 in this year</div>
                    </div>
                </div>
                <!-- END WIDGET MESSAGES -->
            </div>

            <div class="col-md-3">
                <!-- START WIDGET MESSAGES -->
                <div class="widget widget-default widget-item-icon">
                    <div class="widget-item-left">
                        <span class="fa fa-industry"></span>
                    </div>
                    <div class="widget-data">
                        <div class="widget-int num-count">{{$providers}}</div>
                        <div class="widget-title link"><a href="/admin/providers/active">Active Providers</a></div>
                        <div class="widget-subtitle link"><a href="/admin/providers/suspended">{{$s_providers}} Suspended</a></div>
                        <div class="widget-subtitle">{{$techs}} Technicians in total</div>
                    </div>
                </div>
                <!-- END WIDGET MESSAGES -->
            </div>

            <div class="col-md-3">
                <!-- START WIDGET MESSAGES -->
                <div class="widget widget-default widget-item-icon">
                    <div class="widget-item-left">
                        <span class="fa fa-building"></span>
                    </div>
                    <div class="widget-data">
                        <div class="widget-int num-count">{{$companies}}</div>
                        <div class="widget-title link"><a href="/admin/companies/active">Active Companies</a></div>
                        <div class="widget-subtitle link"><a href="/admin/companies/suspended">{{$s_companies}} Suspended</a></div>
                        <div class="widget-subtitle">{{$users}} Users in total</div>
                    </div>
                </div>
                <!-- END WIDGET MESSAGES -->
            </div>
            <div class="col-md-3">
                <!-- START WIDGET MESSAGES -->
                <div class="widget widget-default widget-item-icon">
                    <div class="widget-item-left">
                        <span class="fa fa-handshake-o" style="font-size: 48px;"></span>
                    </div>
                    <div class="widget-data">
                        <div class="widget-int num-count">{{$collaborations_count}}</div>
                        <div class="widget-title link"><a href="/admin/collaborations">Collaborations</a></div>
                        <div class="widget-subtitle">{{$monthly_collaborations}} In this month</div>
                        <div class="widget-subtitle">{{$yearly_collaborations}} In this year</div>
                    </div>
                </div>
                <!-- END WIDGET MESSAGES -->
            </div>
        </div>
        <!-- END WIDGETS -->
    </div>
    @endif
@endsection
