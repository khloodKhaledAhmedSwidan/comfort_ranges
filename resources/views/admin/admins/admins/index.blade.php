@extends('admin.layouts.master')

@section('title')
    @lang('messages.manager')
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ URL::asset('admin/css/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/datatables.bootstrap-rtl.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/sweetalert.css') }}">
@endsection

@section('page_header')
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <a href="{{ url('admin/home') }}">@lang('messages.dashboard')</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="{{ route('admins.index') }}">    @lang('messages.manager')</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>@lang('messages.show_manager')</span>
            </li>
        </ul>
    </div>

    <h1 class="page-title">     @lang('messages.manager')
        {{--<small>عرض جميع المشرفين</small>--}}
    </h1>
@endsection

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <div class="portlet light bordered">
                <div class="portlet-body">
                    <div class="table-toolbar">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="btn-group">
                                    <a class="btn sbold green" href="{{ url('/admin/admins/create') }}"> @lang('messages.new_addition')
                                        <i class="fa fa-plus"></i>
                                    </a>
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
                            <th> @lang('messages.name') </th>
                            <th> @lang('messages.email') </th>
                            <th> @lang('messages.phone_number') </th>
                            <th> @lang('messages.Processes') </th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $array = []; ?>
                        @foreach( $data as $value )
                            <tr class="odd gradeX">
                                <td>
                                    <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                        <input type="checkbox" class="checkboxes" value="1" />
                                        <span></span>
                                    </label>
                                </td>
                                <td class="no_dec">{{ $value->name }}</td>
                                <td><a href="mailto:{{ $value->email }}"> {{ $value->email }} </a></td>
                                @php

                                    $result = substr(\App\Admin::find($value->id)->phone, 1);
                                    $phone = '00966' . $result;

                                @endphp
                                <td>
{{--                                    <a href="del:{{ $value->phone }}"> {{ $value->phone }} </a>--}}

                                    <a href="https://api.whatsapp.com/send?phone={{$phone}}">
                                        {{$value->phone}}
                                    </a>

                                </td>






                                <td>
                                    {{--@if( $value->id == 1 )--}}
                                        {{----------}}
                                    {{--@else--}}
                                        <div class="btn-group">
{{--                                            <button class="btn btn-xs green dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"> @lang('messages.Processes')--}}
{{--                                                <i class="fa fa-angle-down"></i>--}}
{{--                                            </button>--}}
{{--                                            <ul class="dropdown-menu pull-left" role="menu">--}}

{{--                                                <li>--}}
                                                    <a class="btn btn-info btn-sm" style="margin-left: 1rem;" href="{{ url('/admin/admins/' . $value->id . '/edit') }}">
                                                        <i class="icon-pencil"></i> @lang('messages.update')
                                                    </a>
{{--                                                </li>--}}
                                                @if( auth()->guard('admin')->user()->id != $value->id )
{{--                                                    <li>--}}
                                                        <a class="btn btn-danger btn-sm" class="delete_data" data="{{ $value->id }}" data_name="{{ $value->name }}">
                                                            <i class="fa fa-times"></i> @lang('messages.delete')
                                                        </a>
{{--                                                    </li>--}}
                                                @endif
{{--                                            </ul>--}}
                                        </div>
                                    {{--@endif--}}
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
        $( document ).ready(function () {
            $('body').on('click', '.delete_data', function() {
                var id = $(this).attr('data');
                var swal_text = '@lang('messages.delete') ' + $(this).attr('data_name');
                var swal_title = ' @lang('messages.Are_you_sure_you_want_to_delete?')';

                swal({
                    title: swal_title,
                    text: swal_text,
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-warning",
                    confirmButtonText: "@lang('messages.yes')",
                    cancelButtonText: "@lang('messages.close')"
                }, function() {

                    window.location.href = "{{ url('/') }}" + "/admin/admin_delete/" + id;

                });

            });
        });
    </script>
@endsection
