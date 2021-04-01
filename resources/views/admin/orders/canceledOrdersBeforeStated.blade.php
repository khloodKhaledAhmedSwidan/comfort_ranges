@extends('admin.layouts.master')

@section('title')
    @lang('messages.orders')
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ URL::asset('admin/css/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/datatables.bootstrap-rtl.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/sweetalert.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/sweetalert.css') }}">
@endsection

@section('page_header')
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <a href="/admin/home">@lang('messages.dashboard')</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="{{url('/admin/canceled-orders-before-started')}}">@lang('messages.canceledOrdersBeforeStated')</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>@lang('messages.canceledOrdersBeforeStated')  </span>
            </li>
        </ul>
    </div>

    <h1 class="page-title">@lang('messages.canceledOrdersBeforeStated')
        {{-- <small>@lang('messages.show_canceled_orders')</small> --}}
    </h1>
@endsection

@section('content')
    @include('flash::message')
    <div class="row">
        <div class="col-lg-12">
            <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <div class="portlet light bordered table-responsive">
                <div class="portlet-body">
                    <div class="table-toolbar">
                        <div class="row">
                            <div class="col-lg-11">
                                <div class="btn-group">
                                    <div>
                                        <div class="btn-group">
                                            <a style="margin-left: 0.5rem;" class="btn btn-info btn-sm"
                                            href="{{route('new_orders')}}">@lang('messages.new_orders')
                                         </a>
                                            <a style="margin-left: 0.5rem;" class="btn btn-info btn-sm"
                                               href="{{route('active_orders')}}">@lang('messages.active_orders')
                                            </a>
                                            <a style="margin-left: 0.5rem;" class="btn btn-info btn-sm"
                                            href="{{route('waited_orders')}}">@lang('messages.waited_orders')
                                         </a>
                                            <a style="margin-left: 0.5rem;" class="btn btn-info btn-sm"
                                               href="{{route('completed_orders')}}">@lang('messages.completed_orders')
                                    
                                            </a>
                                            <a style="margin-left: 0.5rem;" class="btn btn-info btn-sm"
                                               href="{{route('completed_ordersNotPaid')}}">@lang('messages.completed_ordersNotPaid')
                                            </a>
                                            <a style="margin-left: 0.5rem;" class="btn btn-info btn-sm"
                                               href="{{route('canceled_orders')}}">@lang('messages.canceled_orders')
                                            </a>
                                            <a style="margin-left: 0.5rem;" class="btn btn-info btn-sm"
                                            href="{{route('canceled_orders_before_started')}}">@lang('messages.canceledOrdersBeforeStated')
                                         </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <table class="table table-striped table-bordered table-hover table-checkable order-column" id="sample_1">
                        <thead>
                        <tr>
                            <th>
                                <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                    <input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" />
                                    <span></span>
                                </label>
                            </th>
                            <th style="display: none;"></th>
                            <th> @lang('messages.order_num')</th>
                            <th>  @lang('messages.name')</th>
                            <th> @lang('messages.client_num')</th>
                            <th> @lang('messages.employee_name')</th>

                            <th>  @lang('messages.Classification')</th>
                            {{-- <th>@lang('messages.the_reason_of_refuse')</th> --}}
                            <th> @lang('messages.redirect_order')</th>

                            <th>@lang('messages.Processes')</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php $i=0 ?>
                        @foreach($orders as $order)
                            <tr class="odd gradeX">
                                <td>
                                    <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                        <input type="checkbox" class="checkboxes" value="1" />
                                        <span></span>
                                    </label>
                                </td>
                                <td style="display: none;"><?php echo ++$i ?></td>
                                <td> {{$order->real_num}} </td>

                                <td> {{\App\User::find($order->user_id)->name}} </td>
                                <td> {{\App\User::find($order->user_id)->phone}} </td>
                                <td>{{\App\User::find($order->employee_id)->name}}</td>

                                <td>{{app()->getLocale() =='en'?\App\Models\Category::find($order->category_id)->name:\App\Models\Category::find($order->category_id)->name_ar}} </td>




                                {{-- <td>{{$order->employee_note}}</td> --}}
                                <td>
                                    <form method="post" action="{{route('changeOrderStatus',$order->id)}}">
                                        @csrf
                                        <div class=" input-group ">

                                            <select name="status"
                                                    class="form-control select2-allow-clear btn btn-primary">
                                                <option value="" disabled
                                                        selected>@lang('messages.change_order_status')
                                                </option>
                                                <option value="1">@lang('messages.new')
                                                </option>
                                                <option value="2">@lang('messages.active')
                                                </option>
                                                <option value="3">@lang('messages.completed')
                                                </option>
                                                <option value="4">@lang('messages.canceled')
                                                </option>
                                            </select>
                                            <span class="input-group-btn">
                                        <button class="btn btn-default" type="submit"
                                                data-select2-open="single-append-text">
                                            <span class="glyphicon glyphicon-save"></span>
                                        </button>
                                        </span>
                                        </div>
                                    </form>
                                </td>
                                <td>
                                    <div class="btn-group">
{{--                                        <button class="btn btn-xs green dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"> @lang('messages.Processes')--}}
{{--                                            <i class="fa fa-angle-down"></i>--}}
{{--                                        </button>--}}
{{--                                        <ul class="dropdown-menu pull-left" role="menu">--}}
                                            {{--<li>--}}
                                            {{--<a href="">--}}
                                            {{--<i class="icon-eye"></i> عرض--}}
                                            {{--</a>--}}
                                            {{--</li>--}}
{{--                                            <li>--}}
                                                <a class="btn btn-info btn-sm" href="{{route('show_orderPage',$order->id)}}">
                                                    <i class="icon-docs"></i> @lang('messages.show')  </a>
{{--                                            </li>--}}

{{--                                        </ul>--}}
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- END EXAMPLE TABLE PORTLET-->
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ URL::asset('admin/js/datatable.js') }}"></script>
    <script src="{{ URL::asset('admin/js/datatables.min.js') }}"></script>
    <script src="{{ URL::asset('admin/js/datatables.bootstrap.js') }}"></script>
    <script src="{{ URL::asset('admin/js/table-datatables-managed.min.js') }}"></script>
    <script src="{{ URL::asset('admin/js/sweetalert.min.js') }}"></script>
    <script src="{{ URL::asset('admin/js/ui-sweetalert.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            var CSRF_TOKEN = $('meta[name="X-CSRF-TOKEN"]').attr('content');

            $('body').on('click', '.delete_user', function() {
                var id = $(this).attr('data');

                var swal_text = 'حذف ' + $(this).attr('data_name') + '؟';
                var swal_title = 'هل أنت متأكد من الحذف ؟';

                swal({
                    title: swal_title,
                    text: swal_text,
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-warning",
                    confirmButtonText: "تأكيد",
                    cancelButtonText: "إغلاق",
                    closeOnConfirm: false
                }, function() {

                    window.location.href = "{{ url('/') }}" + "/admin/delete/"+id+"/user";


                });

            });

        });
    </script>

@endsection
