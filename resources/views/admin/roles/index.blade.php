@extends('admin.layouts.master')

@section('title')
    @lang('messages.Privacy')
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
                <a href="/admin/roles"> @lang('messages.Privacy')</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>@lang('messages.show_Privacy')</span>
            </li>
        </ul>
    </div>

    <h1 class="page-title">@lang('messages.show_Privacy')
    </h1>
@endsection
@section('content')
    @include('flash::message')

<div class="row">
    <div class="col-lg-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-body">
                <div class="table-toolbar">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="btn-group">
                                <a class="btn sbold green" href="{{ route('roles.create') }}">@lang('messages.new_addition')
                                    <i class="fa fa-plus"></i>
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
                <table class="table table-striped table-bordered table-hover table-checkable order-column"
                    id="sample_1">
                    <thead>
                        <tr>
                            <th>
                                <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                    <input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" />
                                    <span></span>
                                </label>
                            </th>
                            <th>@lang('messages.Privacy')</th>
                            <th>@lang('messages.available')</th>
                            <th> @lang('messages.Processes') </th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach( $roles as $record )
                        <tr class="odd gradeX">
                            <td>
                                <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                    <input type="checkbox" class="checkboxes" value="1" />
                                    <span></span>
                                </label>
                            </td>

                            <td class="no_dec">{{ $record->name_ar }}</td>
                            <td>
                                <ul>
                                @foreach($record->permissions()->get() as $permission)
                                  <li> {{app()->getLocale() == 'en'?$permission->display_name:$permission->display_name_ar}}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>

                                <div class="btn-group">
{{--                                    <button class="btn btn-xs green dropdown-toggle" type="button"--}}
{{--                                        data-toggle="dropdown" aria-expanded="false"> @lang('messages.Processes')--}}
{{--                                        <i class="fa fa-angle-down"></i>--}}
{{--                                    </button>--}}
{{--                                    <ul class="dropdown-menu pull-left" role="menu">--}}

{{--                                        <li>--}}
                                            <a class="btn btn-info btn-sm"  style="margin-left: 2rem;" href="{{ route('roles.edit',['id'=>$record]) }}">
                                                <i class="icon-pencil"></i> @lang('messages.update')
                                            </a>
{{--                                        </li>--}}

{{--                                        <li>--}}

                                                <a class="delete_data btn btn-danger btn-sm" data="{{ $record->id }}"
                                                   data_name="{{ $record->name}}">
                                                    <i class="fa fa-times"></i> @lang('messages.delete')
                                                </a>

{{--                                        </li>--}}

{{--                                    </ul>--}}
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
@section('content')
@if (session('information'))
<div class="alert alert-success">
    {{ session('information') }}
</div>
@endif
@if (session('pass'))
<div class="alert alert-success">
    {{ session('pass') }}
</div>
@endif
@if (session('privacy'))
<div class="alert alert-success">
    {{ session('privacy') }}
</div>
@endif
@endsection

@section('scripts')
<script src="{{ URL::asset('admin/js/datatable.js') }}"></script>
<script src="{{ URL::asset('admin/js/datatables.min.js') }}"></script>
<script src="{{ URL::asset('admin/js/datatables.bootstrap.js') }}"></script>
<script src="{{ URL::asset('admin/js/table-datatables-managed.min.js') }}"></script>
<script src="{{ URL::asset('admin/js/sweetalert.min.js') }}"></script>
<script src="{{ URL::asset('admin/js/ui-sweetalert.min.js') }}"></script>
{{--
"{{ url('/') }}" + "/admin/categories/"+id
--}}
<script>
    $( document ).ready(function () {
            $('body').on('click', '.delete_data', function() {
                var id = $(this).attr('data');
                // console.log(id);
                var swal_text = '@lang('messages.delete')' + $(this).attr('data_name');
                var swal_title = '@lang('messages.Are_you_sure_you_want_to_delete?')';

                swal({
                    title: swal_title,
                    text: swal_text,
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-warning",
                    confirmButtonText: "@lang('messages.yes')",
                    cancelButtonText: "@lang('messages.close')"
                }, function() {
                    window.location.href ="{{ url('/') }}" + "/admin/roles/"+id+"/delete";

                });

            });
        });
</script>
@endsection
