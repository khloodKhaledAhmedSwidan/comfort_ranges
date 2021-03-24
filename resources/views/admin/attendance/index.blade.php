@extends('admin.layouts.master')

@section('title')
    @lang('messages.employees')
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
                <a href="{{url('/admin/users')}}"> @lang('messages.employees')</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>@lang('messages.show_employees') </span>
            </li>
        </ul>
    </div>

    <h1 class="page-title">@lang('messages.show_employees')
        <small>@lang('messages.show_all_employees')</small>
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
                            <div class="col-lg-6">

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
                            <th></th>
                            <th> @lang("messages.name") </th>
                            <th> @lang('messages.Last_attendance')</th>
                            <th>@lang('messages.Last_check_out') </th>
                            <th>@lang('messages.work_time') </th>
                            <th> @lang('messages.Attendance_Record') </th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i=0 ?>
                        @foreach($users as $user)
                            @php
                            $shift = $user->shifts()->latest()->first();
                            @endphp
{{--                            {{dd($shift)}}--}}
                            @if($shift)

                            <tr class="odd gradeX">
                                <td>
                                    <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                        <input type="checkbox" class="checkboxes" value="1" />
                                        <span></span>
                                    </label>
                                </td>
                                <td><?php echo ++$i ?></td>
                                <td> {{$user->name}} </td>
                                <td>{{ \Carbon\Carbon::parse($shift->from)->format('h:i')}} </td>
                                <td>{{$shift->to != null ? \Carbon\Carbon::parse($shift->to)->format('h:i'):'لم يتم تسجيل الانصراف بعد'}} </td>
                                @if($shift->to != null)
@php
    $t1 = \Carbon\Carbon::parse($shift->from);
    $t2 = \Carbon\Carbon::parse($shift->to);
    $diff = $t1->diff($t2);
@endphp
                                    <td>{{$diff->h}}  @lang('messages.hours') {{$diff->i}} @lang('messages.minute')</td>

                                @else
                                    <td>@lang('messages.The_number_of_working_hours_has_not_been_calculated_yet') </td>
                                    @endif

                                <td>
                                    <div class="btn-group">


                                            {{--<li>--}}
                                            {{--<a href="">--}}
                                            {{--<i class="icon-eye"></i> عرض--}}
                                            {{--</a>--}}
                                            {{--</li>--}}
                                            <li>
                                                <a style="text-decoration: none" href="/admin/attendance/user/{{$user->id}}">
                                                    <i class="icon-docs"></i> @lang('messages.Watch') </a>
                                            </li>
                                            {{--                                            @if( auth()->user()->id != $value->id )--}}
{{--                                            <li>--}}
{{--                                                <a class="delete_user" data="{{ $user->id }}" data_name="{{ $user->name }}" >--}}
{{--                                                    <i class="fa fa-key"></i> مسح--}}
{{--                                                </a>--}}
{{--                                            </li>--}}

                                            {{--@endif--}}

                                    </div>
                                </td>
                            </tr>
                            @endif
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
