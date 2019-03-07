@extends('provider.layouts.app')
@section('content')
    <style>
        .link a:hover
        {
            text-decoration: none;
        }
    </style>
    <div class="page-content-wrap" style="margin-top: 10px;">

        <div class="row">

            <div class="col-md-3">
                <!-- START WIDGET MESSAGES -->
                <div class="widget widget-default widget-item-icon">
                    <div class="widget-item-left">
                        <span class="fa fa-thumbs-up"></span>
                    </div>
                    <div class="widget-data">
                        <div class="widget-int num-count">{{$rate_commitment}}</div>
                        <div class="widget-title link">Commitment </div>
                        <div class="widget-subtitle link">Average Rate out of 5</div>
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
                        <div class="widget-int num-count">{{$rate_appearance}}</div>
                        <div class="widget-title link">Appearance</div>
                        <div class="widget-subtitle link">Average Rate out of 5</div>
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
                        <div class="widget-int num-count">{{$rate_performance}}</div>
                        <div class="widget-title link">Performance</div>
                        <div class="widget-subtitle link">Average Rate out of 5</div>
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
                        <div class="widget-int num-count">{{$rate_cleanliness}}</div>
                        <div class="widget-title link">Cleanliness</div>
                        <div class="widget-subtitle link">Average Rate out of 5</div>
                    </div>
                </div>
                <!-- END WIDGET MESSAGES -->
            </div>

            <div class="col-md-3">
                <!-- START WIDGET MESSAGES -->
                <div class="widget widget-default widget-item-icon">
                    <div class="widget-item-left">
                        <span class="fa fa-wrench"></span>
                    </div>
                    <div class="widget-data">
                        <div class="widget-int num-count">{{$a_techs}}</div>
                        <div class="widget-title link"><a href="/provider/technicians/active">Active Technicians</a></div>
                        <div class="widget-subtitle link"><a href="/provider/technicians/suspended">{{$s_techs}} Suspended</a></div>
                        <div class="widget-subtitle">{{$techs_count}} Technicians in total</div>
                    </div>
                </div>
                <!-- END WIDGET MESSAGES -->
            </div>

            <div class="col-md-3">
                <!-- START WIDGET MESSAGES -->
                <div class="widget widget-default widget-item-icon">
                    <div class="widget-item-left">
                        <span class="fa fa-calendar"></span>
                    </div>
                    <div class="widget-data">
                        <div class="widget-int num-count">{{$monthly_techs}}</div>
                        <div class="widget-title link">New Technicians This Month</div>
                        <div class="widget-subtitle link">{{$yearly_techs}} This Year</div>
                    </div>
                </div>
                <!-- END WIDGET MESSAGES -->
            </div>

            <div class="col-md-3">
                <!-- START WIDGET MESSAGES -->
                <div class="widget widget-default widget-item-icon">
                    <div class="widget-item-left">
                        <span class="fa fa-mail-reply"></span>
                    </div>
                    <div class="widget-data">
                        <div class="widget-int num-count"><a href="/provider/orders/urgent">{{$monthly_orders}}</a></div>
                        <div class="widget-title link">Orders This Month</div>
                        <div class="widget-subtitle link">{{$yearly_orders}} This Year</div>
                    </div>
                </div>
                <!-- END WIDGET MESSAGES -->
            </div>

            <div class="col-md-3">
                <!-- START WIDGET MESSAGES -->
                <div class="widget widget-default widget-item-icon">
                    <div class="widget-item-left">
                        <span class="fa fa-times"></span>
                    </div>
                    <div class="widget-data">
                        <div class="widget-int num-count"><a href="/provider/orders/canceled">{{$monthly_canceled_orders}}</a></div>
                        <div class="widget-title link">Canceled Orders This Month</div>
                        <div class="widget-subtitle link">{{$yearly_canceled_orders}} This Year</div>
                    </div>
                </div>
                <!-- END WIDGET MESSAGES -->
            </div>

        </div>
        <!-- END WIDGETS -->
        <div class="page-content-wrap">
            <div class="row">
                <div class="col-md-12">
                <!-- START BASIC TABLE SAMPLE -->
                    <div class="panel panel-default">
                        <form class="form-horizontal" method="get" action="/provider/technicians/search">
                            <div class="form-group">
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group" style="margin-top: 10px;">
                                        <input type="text" class="form-control" name="search" value="{{isset($search) ? $search : ''}}" placeholder="Search by user badge id,name,email or phone" style="margin-top: 1px;"/>
                                        <span class="input-group-addon btn btn-default">
                                    <button class="btn btn-default">Search now</button>
                                </span>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>Badge ID</th>
                                        <th>Categories</th>
                                        <th>English Name</th>
                                        <th>Orders</th>
                                        <th>Image</th>
                                        <th>Operations</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($techs as $tech)
                                        <tr>
                                            <td>{{$tech->badge_id}}</td>
                                            <td>
                                                @foreach($tech->get_category_list($tech->cat_ids) as $cat)
                                                    <p>{{$cat}}</p>
                                                @endforeach                                                        </td>
                                            <td>{{$tech->en_name}}</td>
                                            <td>{{$tech->orders->count()}}</td>
                                            <td>
                                                <img src="/providers/technicians/{{$tech->image}}" class="image_radius"/>
                                            </td>
                                            <td>
                                                <a title="View Technician" href="/provider/technician/{{$tech->id}}/view"><button class="btn btn-info btn-condensed"><i class="fa fa-eye"></i></button></a>
                                                @if(provider()->hasPermissionTo('techs_operate'))
                                                    <form method="post" action="/provider/technician/orders/invoice/show" class="ltr_buttons">
                                                        <button type="submit" title="View Orders" class="btn btn-success btn-condensed"><i class="fa fa-file-excel-o"></i></button>
                                                        {{csrf_field()}}
                                                        <input type="hidden" name="tech_id" value="{{$tech->id}}">
                                                        <input type="hidden" name="from" value="{{$tech->created_at->toDateString()}}">
                                                        <input type="hidden" name="to" value="{{\Carbon\Carbon::now()->toDateString()}}">
                                                    </form>
                                                @endif

                                            </td>
                                        </tr>


                                    @endforeach

                                    </tbody>
                                </table>
                                {{$techs->links()}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
