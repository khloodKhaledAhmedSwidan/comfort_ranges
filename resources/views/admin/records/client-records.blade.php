@extends('admin.layouts.master')

@section('title')
    @lang('messages.client_records')
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
                i>
                <a href="/admin/all-client-records">@lang('messages.all_client_records')</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>@lang('messages.show_client_records')  </span>
            </li>
        </ul>
    </div>

    <h1 class="page-title">@lang('messages.client_records')
        <small>@lang('messages.show_client_records')</small>
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
                                <div class="btn-group">
                             
                                </div>
                            </div>

                        </div>
                        <br/>


                        {{--                        <div class="row">--}}
                        {{--                            <div class="form-group">--}}
                        {{--                                <label class="control-label">الفلاتر </label>--}}
                        {{--                                <br>--}}
                        {{--                                {!! Form::open(['method' => 'get','id' => 'filter_form']) !!}--}}
                        {{--                                <div class="row">--}}
                        {{--                                    <div class="col-md-4">--}}
                        {{--                                        <div class="form-group">--}}
                        {{--                                            {!! Form::select('category_id',app()->getLocale() == 'en'?\App\Models\Category::pluck('name','id'):\App\Models\Category::pluck('name_ar','id'),null,[--}}
                        {{--                                            'placeholder' =>app()->getLocale() == 'en' ?'Services':'التصنيفات' ,--}}
                        {{--                                            'class' => 'form-control',--}}
                        {{--                                            'id' =>'category_id'--}}
                        {{--                                            ]) !!}--}}
                        {{--                                        </div>--}}
                        {{--                                    </div>--}}



                        {{--                                    <div class="col-md-1">--}}
                        {{--                                        <div class="form-group">--}}
                        {{--                                            <button class="btn btn-primary btn-block" onclick="sendOptions();" type="button"><i class="fa fa-search"></i></button>--}}
                        {{--                                        </div>--}}
                        {{--                                    </div>--}}
                        {{--                                </div>--}}
                        {{--                                {!! Form::close() !!}--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}

                    </div>
                    <table class="table table-striped table-bordered table-hover table-checkable order-column"
                           id="sample_1">
                        <thead>
                        <tr>
                            <th>
                                <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                    <input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes"/>
                                    <span></span>
                                </label>
                            </th>
                            {{--                            <th></th>--}}
                            <th style="display: none;"></th>
                            <th> @lang('messages.order_num')</th>
                            <th> @lang('messages.client_num')</th>
                            <th> @lang('messages.Classification')</th>
                            <th> @lang('messages.status')</th>
                            <th> @lang('messages.employee_name')</th>
                            <th> @lang('messages.anotherOrder')</th>
             <th> @lang('messages.show')</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php $i=0 ?>
                        @foreach($orders as $order)
                  
                            <tr class="odd gradeX">
                                <td>
                                    <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                        <input type="checkbox" class="checkboxes" value="1"/>
                                        <span></span>
                                    </label>
                                </td>
                                <td style="display: none;"><?php echo ++$i ?></td>
                                <td> {{$order->id}} </td>
            
                                <td id="phone"><a href="tel:{{\App\User::find($order->user_id)->phone}}"> {{\App\User::find($order->user_id)->phone}} </a></td>
                                <td id="cat_id">{{app()->getLocale() =='en'?\App\Models\Category::find($order->category_id)->name:\App\Models\Category::find($order->category_id)->name_ar}} </td>
                          
                                <td>
                                    @if($order->status ==  1)
                                     @lang('messages.new')
                                    @elseif($order->status == 2)
                                     @lang('messages.start_order')
                                    @elseif($order->status == 3)
                                     @lang('messages.completed_order_status')
                                    @elseif($order->status == 5)
                                     @lang('messages.waited_order')
                                    @elseif($order->status == 4)
                                     @lang('messages.cancel_order_status')
                                    @endif


                                </td>
                                <td>{{\App\User::find($order->employee_id)->name}}</td>
                                <td>
                                    @if($order->withOrderCategories()->count() >0)
                                        <a type="button" data-toggle="modal"
                                           data-target="#exampleModalLong{{$order->id}}">
                                            {{app()->getLocale() == 'en'?'View other services':'عرض الخدمات الاخري'}}
                                        </a>

                                        <!-- Modal -->
                                        <div class="modal fade" id="exampleModalLong{{$order->id}}" tabindex="-1"
                                             role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"
                                                            id="exampleModalLongTitle"> {{app()->getLocale() == 'en'?'View other services':'عرض الخدمات الاخري'}}</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <ul>
                                                            @foreach($order->withOrderCategories()->get() as $category)
                                                                <li>
                                                                    {{app()->getLocale() == 'en' ?\App\Models\Category::find($category->category_id)->name:\App\Models\Category::find($category->category_id)->name_ar}}
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Close
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <p>{{app()->getLocale() == 'en' ?'no other serveses':'لا يوجد خدمات اخري مع الطلب'}}</p>
                                    @endif
                                </td>

                                {{--<td>{{$order->date}}</td>--}}
                                {{--<td>{{'من '. \App\Models\OrderShift::find($order->order_shift_id)->from . ' الي' .\App\Models\OrderShift::find($order->order_shift_id)->to  }} </td>--}}


                     

                                <td>
                    
                                    <div class="text-center"> 
                                        <!-- Button trigger modal -->
                                        <a type="button"  data-toggle="modal" data-target="#exampleModalLong{{$order->id}}">
                                       {{ app()->getLocale() == 'en' ?'show order':'عرض الطلب'}}
                                        </a>
                                
                                        <!-- Modal -->
                                        <div class="modal fade" id="exampleModalLong{{$order->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLongTitle">{{app()->getLocale() == 'en'?'order details':'عرض تفاصيل الطلب'}}</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label> @lang('messages.branch')</label>
                                                            @php
                                                                $branch = \App\Models\Category::find($order->category_id)->branch_id;
                                                            @endphp
                                                            <input type="text" readonly class="form-control"
                                                                   value="{{app()->getLocale() == 'en'?\App\Models\Branch::find($branch)->name:\App\Models\Branch::find($branch)->name_ar}}"/>
                                
                                                        </div>
                                
                                
                                                        <div class="form-group">
                                
                                                            <label for="name">@lang('messages.choose_service')</label>
                                
                                                            <div class="total" id="category-selection">
                                
                                                                <input type="text" readonly class="form-control"
                                                                       value="{{app()->getLocale() == 'en' ?\App\Models\Category::find($order->category_id )->name:\App\Models\Category::find($order->category_id )->name_ar}}"/>
                                
                                                            </div>
                                
                                
                                                        </div>
                                                        <div class="form-group">
                                                            <label> @lang('messages.customer')</label>
                                                            <input type="text" readonly class="form-control"
                                                                   value="{{App\User::where('id' ,$order->user_id)->first()->name}}"/>
                                
                                
                                                        </div>
                                                        <div class="form-group">
                                                            <label> @lang('messages.employee')</label>
                                                            <input type="text" readonly class="form-control"
                                                                   value="{{App\User::where('id' ,$order->employee_id )->first()->name}}"/>
                                
                                
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label">@lang('messages.date') </label>
                                                            <input type="date" readonly class="form-control"
                                                                   value="{{$order->date}}"/>
                                
                                                        </div>
                                                        <div class="form-group">
                                                            <label> @lang('messages.period')</label>
                                
                                                            <input type="text" readonly class="form-control"
                                                                   value="@lang('messages.from'){{\App\Models\OrderShift::where('id' ,$order->order_shift_id )->first()->from}}@lang('messages.to'){{\App\Models\OrderShift::where('id' ,$order->order_shift_id )->first()->to}}"/>
                                
                                
                                                        </div>
                                
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                
                                
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
        $(document).ready(function () {
            var CSRF_TOKEN = $('meta[name="X-CSRF-TOKEN"]').attr('content');

            $('body').on('click', '.delete_user', function () {
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
                }, function () {

                    window.location.href = "{{ url('/') }}" + "/admin/delete/" + id + "/user";


                });

            });

        });
    </script>



    <script>
        function sendOptions() {
            // var fd = $("#filter_form").serialize();
            var query = $('#category_id').val();
            $.ajax({
                url: "{{url('admin/category-Filter/')}}" + '/' + query,
                // data:{fd :  query},

                processData: false,
                contentType: false,
                type: 'get',
                dataType: "json",
                success: function (data) {

                    $('#category_id').empty();
                    if (data.length > 0) {
                        $.each(data, function (key, value) {
                            console.log(key + 'fs' + value);
                            var namee = value['user_id'];
                            $('#name').append('<td>' + value['note'] + '</td>');

                        });
                    } else {
                        $('select[name="category_id[]"]')
                            .append('<option value="0"> لا يوجد نتائج تطابق هذة الاختيارات </option>');
                    }
                }
            });
        }

    </script>

@endsection
