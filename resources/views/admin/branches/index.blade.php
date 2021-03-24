@extends('admin.layouts.master')

@section('title')
@lang('messages.branches')
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
                <a href="/admin/branches">@lang('messages.branches')
                </a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>@lang('messages.show_branches')</span>
            </li>
        </ul>
    </div>

    <h1 class="page-title">@lang('messages.show_branches')
        <small>@lang('messages.show_all_branch')</small>
    </h1>
@endsection

@section('content')
    @include('flash::message')

    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption font-dark">
                        <i class="icon-settings font-dark"></i>

                        <a href="{{route('branches.create')}}" class="btn btn-sm btn-info">   <span class="caption-subject bold uppercase">@lang('messages.add_branch')</span></a>
                    </div>

                </div>
                <div class="portlet-body">

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
                            <th> @lang('messages.name') </th>
                            <th> @lang('messages.name_en') </th>
                            <th> @lang('messages.Processes') </th>

                        </tr>
                        </thead>
                        <tbody>
                        <?php $i=0 ?>
                        @foreach($branches as $branch)
                            <tr class="odd gradeX">
                                <td>
                                    <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                        <input type="checkbox" class="checkboxes" value="1" />
                                        <span></span>
                                    </label>
                                </td>
                                <td><?php echo ++$i ?></td>
                                <td> {{$branch->name_ar}} </td>
                                <td> {{$branch->name}} </td>




                                <td>



                                    <a class="btn-sm btn btn-info" style="margin-left: 2rem;" href="{{route('branches.edit',$branch->id)}}">
                                        <i class="icon-docs"></i> @lang('messages.update') </a>
                                    <a class="delete_attribute btn-sm btn btn-danger" data="{{$branch->id}}" data_name="{{app()->getLocale() =='en'?$branch->name:$branch->name_ar}}" >
                                        <i class="fa fa-key"></i> @lang('messages.delete')
                                    </a>

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

            $('body').on('click', '.delete_attribute', function() {
                var id = $(this).attr('data');

                var swal_text = '@lang('messages.delete') ' + $(this).attr('data_name') + '؟';
                var swal_title = '@lang('messages.Are_you_sure_you_want_to_delete?')';

                swal({
                    title: swal_title,
                    text: swal_text,
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-warning",
                    confirmButtonText: "@lang('messages.yes')",
                    cancelButtonText: "@lang('messages.close')",
                    closeOnConfirm: false
                }, function() {

                    window.location.href = "{{ url('/') }}" + "/admin/branches/delete/"+id;

                });

            });

        });


    </script>



@endsection
