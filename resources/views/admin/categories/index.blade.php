@extends('admin.layouts.app')
@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="/admin/dashboard">Dashboard</a></li>
        @if(Request::is('admin/categories/all'))
            <li class="active">Categories</li>
        @else
            <li><a href="/admin/categories/all">Categories</a></li>

                @if(isset($parent))
                    @if(\App\Models\Category::get_cat_all($parent)->type == 2)
                        <li>
                            <a href="/admin/categories/{{\App\Models\Category::get_cat_all($parent)->parent->id}}">
                                {{\App\Models\Category::get_cat_all($parent)->parent->en_name}}
                            </a>
                        </li>
                        <li class="active">
                        {{\App\Models\Category::get_cat($parent)}}
                        </li>
                    @else
                        <li class="active">
                            {{\App\Models\Category::get_cat($parent)}}
                        </li>
                    @endif
                @else
                    <li class="active">
                        Search
                    </li>
                @endif

        @endif
    </ul>



    <!-- END BREADCRUMB -->
    <div class="page-content-wrap">
        <div class="row">
            <div class="col-md-12">
            @include('admin.layouts.message')
            <!-- START BASIC TABLE SAMPLE -->
                <div class="panel panel-default">
                    @if(admin()->hasPermissionTo('categories_operate'))
                        <div class="panel-heading">
                            <a href="/admin/category/main/create"><button type="button" class="btn btn-info"> Add a new category </button></a>
                            <a href="/admin/category/sub/create"><button type="button" class="btn btn-info"> Add a new sub category </button></a>
                            <a href="/admin/category/secondary/create"><button type="button" class="btn btn-info"> Add a new problem category </button></a>

                            <a href="/admin/categories/excel/export" style="float: right; margin-right: 3px;"><button type="button" class="btn btn-success"> Export Categories <i class="fa fa-file-excel-o"></i> </button></a>
                        </div>
                    @endif
                    <form class="form-horizontal" method="get" action="/admin/categories/search">
                        <div class="form-group">
                            <div class="col-md-6 col-xs-12">
                                <div class="input-group" style="margin-top: 10px;">
                                    <input type="text" class="form-control" name="search" value="{{isset($search) ? $search : ''}}" placeholder="Search by name" style="margin-top: 1px;"/>
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
                                    <th>ID</th>
                                    <th>English Name</th>
                                    <th>Arabic Name</th>
                                    @if($categories->count() > 0 && $categories[0]->sub_cats->count() != 0)
                                        <th>Sub Categories</th>
                                    @endif
                                    @if($categories->count() > 0 && $categories[0]->image != NULL)
                                        <th>Image</th>
                                    @endif
                                    @if($categories->count() > 0 && $categories[0]->type == 2)
                                        <th>Price</th>
                                    @endif

                                    @if(admin()->hasPermissionTo('categories_operate'))
                                        <th>Operations</th>
                                    @endif
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($categories as $category)
                                    <tr>
                                        <td>{{$category->id}}</td>
                                        <td>{{$category->en_name}}</td>
                                        <td>{{$category->ar_name}}</td>
                                        @if($categories[0]->sub_cats->count() != 0)
                                            <td>
                                                {{$category->sub_cats->count()}}
                                            </td>
                                        @endif

                                        @if(isset($category->image))
                                            <td>
                                                <img src="/categories/{{$category->image}}" class="image_radius"/>
                                            </td>
                                        @endif

                                        @if(isset($category->price))
                                            <td>
                                                {{$category->price}}
                                            </td>
                                        @endif

                                        <td>
                                            @if($category->sub_cats->count() != 0)
                                                <a title="View Sub Categories" href="/admin/categories/{{$category->id}}"><button class="btn btn-info btn-condensed"><i class="fa fa-eye"></i></button></a>
                                            @endif
                                            @if(admin()->hasPermissionTo('categories_operate'))
                                                @if($category->type == 1)
                                                        <a title="Edit" href="/admin/category/{{$category->id}}/main_edit"><button class="btn btn-warning btn-condensed"><i class="fa fa-edit"></i></button></a>
                                                @elseif($category->type == 2)
                                                        <a title="Edit" href="/admin/category/{{$category->id}}/sub_edit"><button class="btn btn-warning btn-condensed"><i class="fa fa-edit"></i></button></a>
                                                @else
                                                        <a title="Edit" href="/admin/category/{{$category->id}}/sec_edit"><button class="btn btn-warning btn-condensed"><i class="fa fa-edit"></i></button></a>
                                                @endif
                                                <button class="btn btn-danger btn-condensed mb-control" data-box="#message-box-danger-{{$category->id}}" title="Delete"><i class="fa fa-trash-o"></i></button>
                                            @endif
                                        </td>
                                    </tr>
                                    <!-- danger with sound -->
                                    <div class="message-box message-box-danger animated fadeIn" data-sound="alert/fail" id="message-box-danger-{{$category->id}}">
                                        <div class="mb-container">
                                            <div class="mb-middle warning-msg alert-msg">
                                                <div class="mb-title"><span class="fa fa-times"></span>Alert !</div>
                                                <div class="mb-content">
                                                    <p>Your are about to delete a category,and you won't be able to restore its data again like providers,companies,individuals under this category .</p>
                                                    <br/>
                                                    <p>Are you sure ?</p>
                                                </div>
                                                <div class="mb-footer buttons">
                                                    <button class="btn btn-default btn-lg pull-right mb-control-close" style="margin-left: 5px;">Close</button>
                                                    <form method="post" action="/admin/category/delete" class="buttons">
                                                        {{csrf_field()}}
                                                        <input type="hidden" name="cat_id" value="{{$category->id}}">
                                                        <button type="submit" class="btn btn-danger btn-lg pull-right">Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end danger with sound -->
                                @endforeach
                                </tbody>
                            </table>
                            {{$categories->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
