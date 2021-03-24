@extends('admin.layouts.master')

@section('title')
@lang('messages.contact_us')
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
                <a href="/admin/contacts">@lang('messages.contact_us')</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>@lang('messages.show_contact_us')</span>
            </li>
        </ul>
    </div>

    <h1 class="page-title">@lang('messages.show_contact_us')
        <small>@lang('messages.show_all_contact_us')</small>
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
                        <span class="caption-subject bold uppercase"> @lang('messages.contact_us')</span>
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
                            <th> @lang('messages.phone_number') </th>
                            <th> @lang('messages.message') </th>
                            <th> @lang('messages.Processes') </th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i=0 ?>
                        @foreach($contacts as $contact)
                            <tr class="odd gradeX">
                                <td>
                                    <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                        <input type="checkbox" class="checkboxes" value="1" />
                                        <span></span>
                                    </label>
                                </td>
                                <td><?php echo ++$i ?></td>
                                <td> {{\App\User::find($contact->user_id)->name}} </td>

                                @php

            $result = substr(\App\User::find($contact->user_id)->phone, 1);
            $phone = '00966' . $result;

                                @endphp
                                <td>
                                <a href="https://api.whatsapp.com/send?phone={{$phone}}">
                              {{\App\User::find($contact->user_id)->phone}}
                                </a>
                                </td>

                                <td >

                                    <!-- Button trigger modal -->
                                    <a type="button"  data-toggle="modal" data-target="#exampleModalLong{{$contact->id}}">
                                        {{ str_limit($contact->message , 50) }}
                                    </a>

                                    <!-- Modal -->
                                    <div class="modal fade" id="exampleModalLong{{$contact->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLongTitle">{{\App\User::find($contact->user_id)->name}}</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    {{$contact->message}}
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                </td>



                                <td>



                                        <a class="delete_attribute btn btn-danger btn-sm" data="{{$contact->id}}" data_name="{{\App\User::find($contact->user_id)->name}}" >
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

                    window.location.href = "{{ url('/') }}" + "/admin/contacts/delete/"+id;

                });

            });

        });
    </script>



@endsection
