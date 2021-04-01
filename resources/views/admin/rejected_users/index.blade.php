@extends('admin.layouts.master')

@section('title')
@lang('messages.rejected_users')
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ URL::asset('admin/css/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/datatables.bootstrap-rtl.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/sweetalert.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/sweetalert.css') }}">
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">

@endsection

@section('page_header')
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <a href="/admin/home">@lang('messages.dashboard') </a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="/admin/rejected_users">@lang('messages.rejected_users')</a>
                <i class="fa fa-circle"></i>
            </li>
           
        </ul>
    </div>

    {{-- <h1 class="page-title">@lang('messages.show_services')
        <small>@lang('messages.show_all_services')</small>
    </h1> --}}
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

                        <a href="{{route('rejected_users.create')}}" class="btn btn-sm btn-info">   <span class="caption-subject bold uppercase">@lang('messages.new_addition')</span></a>
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
                
                            <th> @lang('messages.date') </th>
                            <th>@lang('messages.period') </th>
                            <th>@lang('messages.employees')</th>
                            <th> @lang('messages.Processes') </th>
         
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i=0 ?>
                        @foreach($rejectesUsers as $rejectesUser)
                            <tr class="odd gradeX">
                                <td>
                                    <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                        <input type="checkbox" class="checkboxes" value="1" />
                                        <span></span>
                                    </label>
                                </td>
                                <td><?php echo ++$i ?></td>
                                <td> {{App\Models\RejectedDate::where('id',$rejectesUser->rejected_date_id)->first()->reject_date}} </td>
                                @php 
                                $time = App\Models\OrderShift::where('id',$rejectesUser->order_shift_id)->first();
                                @endphp
                                <td>  {{app()->getLocale() == 'en'?'from'. ' '. \Carbon\Carbon::createFromFormat('H:i:s',$time->from )->format('h:i') .' '.'to'.' '.\Carbon\Carbon::createFromFormat('H:i:s',$time->to )->format('h:i'):'من ' .' ' .\Carbon\Carbon::createFromFormat('H:i:s',$time->from )->format('h:i').' '.'الي'.' '.\Carbon\Carbon::createFromFormat('H:i:s',$time->to )->format('h:i')}} </td>
                     
                        

<td>
    
           <!-- Button trigger modal -->
           <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal{{$rejectesUser->id}}">
            @lang('messages.show')
              </button>
              
              <!-- Modal -->
              <div class="modal fade" id="exampleModal{{$rejectesUser->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">@lang('messages.employees')</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                        <ul>
                    @foreach ($rejectesUser->rejecteds()->get() as $user)
            
               
                        <li> {{App\User::where('id',$user->user_id)->first()->name}} </li>
                     
                    
                    @endforeach
                </ul>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('messages.close')</button>
             
                    </div>
                  </div>
                </div>
              </div>
</td>


                                <td>



                                    <a class="btn btn-info btn-sm" style="margin-left: 2rem;" href="{{route('rejected_users.edit',$rejectesUser->id)}}">
                                        <i class="icon-docs"></i> @lang('messages.update') </a>
                                    <a class="delete_attribute btn btn-danger btn-sm" data="{{$rejectesUser->id}}" data_name="{{App\Models\RejectedDate::where('id',$rejectesUser->rejected_date_id)->first()->reject_date}}" >
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
        <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

    <script>
        function testActive(state, id){
            $.ajax({
                url: 'update/activeCategory/'+id,
                type: 'GET',
                datatype: 'json',
                success: function (data) {
                    console.log(data);
                }
            });

        }
    </script>
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

                    window.location.href = "{{ url('/') }}" + "/admin/rejected_users/delete/"+id;

                });

            });

        });


    </script>



@endsection
