@extends('admin.layouts.master')

@section('title')
@lang('messages.dashboard')
@endsection

@section('content')

    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <a href="/admin/home">@lang('messages.dashboard')</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>@lang('messages.statistics')</span>
            </li>
        </ul>
    </div>

    <h1 class="page-title"> @lang('messages.statistics')
        <small>@lang('messages.show_statistics')</small>
    </h1>

    <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a class="dashboard-stat dashboard-stat-v2 blue" href="{{url('admin/admins')}}">
                <div class="visual">
                    <i class="fa fa-users"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <span>{{$admins}}</span>
                    </div>
                    <div class="desc"> @lang('messages.managers_number')</div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a class="dashboard-stat dashboard-stat-v2 red" href="{{url('admin/users')}}">
                <div class="visual">
                    <i class="fa fa-users"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <span>{{$users}}</span>
                    </div>
                    <div class="desc"> @lang('messages.employees_number')</div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a class="dashboard-stat dashboard-stat-v2 red" href="{{url('admin/clients')}}">
                <div class="visual">
                    <i class="fa fa-users"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <span>{{$clients}}</span>
                    </div>
                    <div class="desc"> @lang('messages.clients_number')</div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a class="dashboard-stat dashboard-stat-v2 red" href="{{url('admin/branches')}}">
                <div class="visual">
                    <i class="fa fa-users"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <span>{{$branches}}</span>
                    </div>
                    <div class="desc"> @lang('messages.branches_number')</div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a class="dashboard-stat dashboard-stat-v2 red" href="{{url('admin/categories')}}">
                <div class="visual">
                    <i class="fa fa-users"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <span>{{$categories}}</span>
                    </div>
                    <div class="desc"> @lang('messages.services_number')</div>
                </div>
            </a>
        </div>

        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a class="dashboard-stat dashboard-stat-v2 blue" href="#">
                <div class="visual">
                    <i class="fa fa-users"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <span>{{$orders}}</span>
                    </div>
                    <div class="desc"> @lang('messages.orders_number')</div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a class="dashboard-stat dashboard-stat-v2 blue" href="{{url('admin/new-orders')}}">
                <div class="visual">
                    <i class="fa fa-users"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <span>{{App\Models\Order::where('status',1)->count()}}</span>
                    </div>
                    <div class="desc"> @lang('messages.count_new_order')</div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a class="dashboard-stat dashboard-stat-v2 blue" href="{{url('admin/active-orders')}}">
                <div class="visual">
                    <i class="fa fa-users"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <span>{{App\Models\Order::where('status',2)->count()}}</span>
                    </div>
                    <div class="desc"> @lang('messages.count_active_order')</div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a class="dashboard-stat dashboard-stat-v2 blue" href="{{url('admin/waited-orders')}}">
                <div class="visual">
                    <i class="fa fa-users"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <span>{{App\Models\Order::where('status',5)->count()}}</span>
                    </div>
                    <div class="desc"> @lang('messages.count_waited_order')</div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a class="dashboard-stat dashboard-stat-v2 blue" href="{{url('admin/completed-orders')}}">
                <div class="visual">
                    <i class="fa fa-users"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <span>{{App\Models\Order::where('status',3)->where('is_paid',1)->count()}}</span>
                    </div>
                    <div class="desc"> @lang('messages.count_complete_order')</div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a class="dashboard-stat dashboard-stat-v2 blue" href="{{url('admin/completed_orders-not-paid')}}">
                <div class="visual">
                    <i class="fa fa-users"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <span>{{App\Models\Order::where('status',3)->where('is_paid',0)->count()}}</span>
                    </div>
                    <div class="desc"> @lang('messages.count_complete_order_not_paid')</div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a class="dashboard-stat dashboard-stat-v2 blue" href="{{url('admin/canceled-orders')}}">
                <div class="visual">
                    <i class="fa fa-users"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <span>{{App\Models\Order::where('status',4)->count()}}</span>
                    </div>
                    <div class="desc"> @lang('messages.count_canceled_order')</div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a class="dashboard-stat dashboard-stat-v2 blue" href="{{url('admin/complaints')}}">
                <div class="visual">
                    <i class="fa fa-users"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <span>{{$complaints}}</span>
                    </div>
                    <div class="desc"> @lang('messages.complaints_number')</div>
                </div>
            </a>
        </div>        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a class="dashboard-stat dashboard-stat-v2 blue" href="#">
                <div class="visual">
                    <i class="fa fa-users"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <span>{{$count}}</span>
                    </div>
                    <div class="desc"> @lang('messages.Number_of_orders_today')</div>
                </div>
            </a>
        </div>

        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>

        <div class="col-lg-3">
            <h3 class="center">@lang('messages.orders')</h3>
            <canvas id="myChart" width="400" height="400"></canvas>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
    <script>
        var ctx = document.getElementById('myChart').getContext('2d');

        var myPieChart = new Chart(ctx, {
            type: 'pie',
            data: {

                datasets: [{
                    data: [{{\App\Models\Order::where('status',2)->count()}}, {{\App\Models\Order::where([['status',3],['is_paid',1]])->count()}},
                        {{\App\Models\Order::where([['status',3],['is_paid',0]])->count()}},{{\App\Models\Order::where('status',4)->count()}}   ],
                    backgroundColor: [
                        'rgb(255, 99, 132)',
                        'rgb(0, 89, 93)',
                        'rgb(255, 205, 86)',
                        'rgb(252, 94, 65)',
                    ],


                }],

// These labels appear in the legend and in the tooltips when hovering different arcs
                labels: [
                    '@lang('messages.active')',
                    '@lang('messages.completed')',
                    '@lang('messages.Incomplete_unpaid')',
                    '@lang('messages.canceled') '
                ],


            },
        });
    </script>

@endsection
