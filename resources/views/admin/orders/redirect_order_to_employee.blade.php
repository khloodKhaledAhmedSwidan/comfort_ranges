@extends('admin.layouts.master')

@section('title')
    @lang('messages.redirect_order')
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
                <a href="{{url('/admin/new-orders')}}">@lang('messages.new_orders')</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>    @lang('messages.redirect_order')  </span>
            </li>
        </ul>
    </div>

    <h1 class="page-title">    @lang('messages.redirect_order')
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
                            <th></th>
                            <th> @lang('messages.employee')</th>
                            {{--                            <th>التاريخ</th>--}}
                            {{--                            <th> الفترة </th>--}}
                            <th>@lang('messages.The_number_of_his_current_orders')</th>
                            <th>@lang('messages.category_belongs_to_employee')</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i = 0 ?>
                        @foreach(\App\Models\Category::find($order->category_id)->users()->where([['type' ,2],['active',1] ])->get() as $user)
                            <tr class="odd gradeX">
                                <td>
                                    <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                        <input type="checkbox" class="checkboxes" value="1"/>
                                        <span></span>
                                    </label>
                                </td>
                                <td><?php echo ++$i ?></td>
                                <td> {{$user->name}} </td>




                                {{--<td>{{$order->date}}</td>--}}
                                {{--<td>{{'من '. \App\Models\OrderShift::find($order->order_shift_id)->from . ' الي' .\App\Models\OrderShift::find($order->order_shift_id)->to  }} </td>--}}


                                <td>
                  @php
             $orderCount    =  $user->employeeOrders->whereIn('status',[1,2])->count();
                  @endphp
                                    {{$orderCount}}
                                </td>

<td>
    {{app()->getLocale() == 'en'?App\Models\Category::where('id',$order->category_id)->first()->name:App\Models\Category::where('id',$order->category_id)->first()->name_ar}} 
</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
               
                </div>
                <br/>
                <hr style="border-top: 1px dashed black;"/>
                <b class="text-center">{{app()->getLocale() == 'en'? ' The rest of the staff ' :'باقي الموظفين '}}</b>
                
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
                 <th></th>
                 <th> @lang('messages.employee')</th>
                 {{--                            <th>التاريخ</th>--}}
                 {{--                            <th> الفترة </th>--}}
                 <th>@lang('messages.The_number_of_his_current_orders')</th>
                 <th>@lang('messages.category_belongs_to_employee')</th>
             </tr>
             </thead>
             <tbody>
             <?php $i = 0 ?>
             @php
         $allIds =   \App\Models\CategoryUser::where('category_id',$order->category_id)->pluck('user_id')->toArray();
             @endphp

             @foreach(\App\User::where([['type' ,2],['active',1] ])->whereNotIn('id',$allIds)->get() as $user)
{{-- {{$category = $user->categories()->pluck('user_id')->toArray()}}
{{dd($category)}} --}}
             {{-- @if(!$user->categories()->where('id',$order->category_id)->first()) --}}
          
                 <tr class="odd gradeX">
                     <td>
                         <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                             <input type="checkbox" class="checkboxes" value="1"/>
                             <span></span>
                         </label>
                     </td>
                     <td><?php echo ++$i ?></td>
                     <td> {{$user->name}} </td>




                     {{--<td>{{$order->date}}</td>--}}
                     {{--<td>{{'من '. \App\Models\OrderShift::find($order->order_shift_id)->from . ' الي' .\App\Models\OrderShift::find($order->order_shift_id)->to  }} </td>--}}


                     <td>
       @php
  $orderCount    =  $user->employeeOrders->whereIn('status',[1,2])->count();
       @endphp
                         {{$orderCount}}
                     </td>

<td>
@foreach($user->categories()->get() as $cat)
<li>{{app()->getLocale() == 'en' ? $cat->name:$cat->name_ar}}</li>
@endforeach
</td>
                 </tr>
                 {{-- @endif --}}
             @endforeach
             </tbody>
         </table>
<div>


   
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

</div>
{{--                {{dd(\App\Models\Category::find($order->category_id)->users()->where([['type' ,2],['active',1] ])->get())}}--}}


                <form role="form" action="{{route('redirect_order_to_another_employee',$order->id)}}" method="post" enctype="multipart/form-data">
                    <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                    <div class="portlet-body">

                        <div class="tab-content">
                            <!-- PERSONAL INFO TAB -->
                            <div class="form-group">
                                <label> @lang('messages.employees')</label>

                                <select class="form-control" name="employee_id">
                                    <option value="" disabled
                                            selected>@lang('messages.choose_employee')
                                    </option>
                                    {{-- @foreach(\App\Models\Category::find($order->category_id)->users()->where([['type' ,2],['active',1] ])->get() as $employee)
                                        @if (\Illuminate\Support\Facades\Input::old('employee_id') == $employee->id)
                                            <option value="{{ $employee->id }}"
                                                    selected>{{$employee->id}}</option>
                                        @else
                                            <option
                                                value="{{ $employee->id }}">{{$employee->name}}</option>
                                        @endif
                                        @endforeach --}}


                                        @foreach(\App\User::where([['type' ,2],['active',1] ])->get() as $employee)
                                        @if (\Illuminate\Support\Facades\Input::old('employee_id') == $employee->id)
                                            <option value="{{ $employee->id }}"
                                                    selected>{{$employee->id}}</option>
                                        @else
                                            <option
                                                value="{{ $employee->id }}">{{$employee->name}}</option>
                                        @endif
                                        @endforeach
                                </select>
                                @error('employee_id')
                                <span class="invalid-feedback" role="alert">
                        <strong style="color:red;">{{ $message }}</strong>
                    </span>
                                @enderror

                            </div>



            </div>
                        <div class="margiv-top-10">
                            <div class="form-actions">
                                <button type="submit" class="btn green" value="حفظ" onclick="this.disabled=true;this.value='تم الارسال, انتظر...';this.form.submit();">@lang('messages.save')</button>

                            </div>
                        </div>
                    </div>
                </form>

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
                }, function () {

                    window.location.href = "{{ url('/') }}" + "/admin/delete/" + id + "/user";


                });

            });

        });
    </script>

@endsection
