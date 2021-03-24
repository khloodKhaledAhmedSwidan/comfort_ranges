@extends('admin.layouts.master')

@section('title')
    @lang('messages.simple_system')
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
                <a href="{{url('/admin/calculation')}}">@lang('messages.simple_system')</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>@lang('messages.simple_system')  </span>
            </li>
        </ul>
    </div>

    <h1 class="page-title">@lang('messages.simple_system')
        {{-- <small>@lang('messages.show_active_orders')</small> --}}
    </h1>
@endsection

@section('content')
    @include('flash::message')
    <div class="row">
        <div class="col-lg-12">
            <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <!-- ========================= total including tax, materials price, and service price  ==================== -->
            <div class="portlet light bordered table-responsive">
         
                <div class="portlet-body">
                    <div class="table-toolbar">
                        <div class="row">
                            <div class="col-lg-11">
                                <div class="btn-group">
                                    <div>
                                        <div class="btn-group">
                      

    <div class="input-group input-daterange">
        <label class="p-3 mb-2 bg-info text-white">{{app()->getLocale() == 'en'?'Total includes (tax, materials price, and service price)':'الاجمالي شامل(الضريبه ،سعر المواد،سعر الخدمه)'}}</label>
        <div class="input-group-addon">{{app()->getLocale()=='en' ?'from':'من'}}</div>
        <input type="date" name="from" id="from_Date"  class="form-control" />
        <div class="input-group-addon">{{app()->getLocale()=='en' ?'to':'إلي'}}</div>
        <input type="date"  name="to" id="to_Date"  class="form-control" />
    </div>
    <br/>
    <div class="col-md-2">
        <button type="button" name="filter"  onclick="fetchDate()" id="filter" class="btn btn-info btn-sm">Filter</button>
        <button type="button" name="refresh" id="refresh" class="btn btn-warning btn-sm">Refresh</button>
    </div>
    
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>





                </div>
            </div>

<!-- ===================== orders price without tax, materials price, and service price -->

            <div class="portlet light bordered table-responsive">
         
                <div class="portlet-body">
                    <div class="table-toolbar">
                        <div class="row">
                            <div class="col-lg-11">
                                <div class="btn-group">
                                    <div>
                                        <div class="btn-group">
                      

    <div class="input-group input-daterange">
        <label class="p-3 mb-2 bg-info text-white">{{app()->getLocale() == 'en'?' orders price without tax, materials price, and service price':'سعر الطلبات بدون(الضريبه ،سعر المواد،سعر الخدمه)'}}</label>
        <div class="input-group-addon">{{app()->getLocale()=='en' ?'from':'من'}}</div>
        <input type="date" name="from" id="from_Price"  class="form-control" />
        <div class="input-group-addon">{{app()->getLocale()=='en' ?'to':'إلي'}}</div>
        <input type="date"  name="to" id="to_Price"  class="form-control" />
    </div>
    <br/>
    <div class="col-md-2">
        <button type="button" name="filterPrice"  onclick="Price()" id="filterPrice" class="btn btn-info btn-sm">Filter</button>
        <button type="button" name="refreshPrice" id="refreshPrice" class="btn btn-warning btn-sm">Refresh</button>
    </div>
    
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>





                </div>
            </div>
            <!-- END EXAMPLE TABLE PORTLET-->


            <!-- ===================== orders material_cost -->

            <div class="portlet light bordered table-responsive">
         
                <div class="portlet-body">
                    <div class="table-toolbar">
                        <div class="row">
                            <div class="col-lg-11">
                                <div class="btn-group">
                                    <div>
                                        <div class="btn-group">
                      

    <div class="input-group input-daterange">
        <label class="p-3 mb-2 bg-info text-white">{{app()->getLocale() == 'en'?' The price of the materials used in the orders':'سعر المواد المستخدمه في الطلبات'}}</label>
        <div class="input-group-addon">{{app()->getLocale()=='en' ?'from':'من'}}</div>
        <input type="date" name="from" id="from_material_cost"  class="form-control" />
        <div class="input-group-addon">{{app()->getLocale()=='en' ?'to':'إلي'}}</div>
        <input type="date"  name="to" id="to_material_cost"  class="form-control" />
    </div>
    <br/>
    <div class="col-md-2">
        <button type="button" name="filtermaterial_cost"  onclick="Pricematerial_cost()" id="filtermaterial_cost" class="btn btn-info btn-sm">Filter</button>
        <button type="button" name="refreshmaterial_cost" id="refreshmaterial_cost" class="btn btn-warning btn-sm">Refresh</button>
    </div>
    
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>





                </div>
            </div>
            <!-- END EXAMPLE TABLE PORTLET-->


            <!-- ===================== orders count -->

            <div class="portlet light bordered table-responsive">
         
                <div class="portlet-body">
                    <div class="table-toolbar">
                        <div class="row">
                            <div class="col-lg-11">
                                <div class="btn-group">
                                    <div>
                                        <div class="btn-group">
                      

    <div class="input-group input-daterange">
        <label class="p-3 mb-2 bg-info text-white">{{app()->getLocale() == 'en'?' number of orders':'عدد الطلبات'}}</label>
        <div class="input-group-addon">{{app()->getLocale()=='en' ?'from':'من'}}</div>
        <input type="date" name="from" id="from_count"  class="form-control" />
        <div class="input-group-addon">{{app()->getLocale()=='en' ?'to':'إلي'}}</div>
        <input type="date"  name="to" id="to_count"  class="form-control" />
    </div>
    <br/>
    <div class="col-md-2">
        <button type="button" name="filterCount"  onclick="orderCount()" id="filterCount" class="btn btn-info btn-sm">Filter</button>
        <button type="button" name="refreshCount" id="refreshCount" class="btn btn-warning btn-sm">Refresh</button>
    </div>
    
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>





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
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        function Price(){
       from  = $('#from_Price').val();
       to  = $('#to_Price').val();
    console.log(from);
if(from && to){
       $.ajax({
         url: 'orders/price/'+ from +'/'+ to,
         type: 'get',
         dataType: 'json',
         success: function(data){
    
//  $("#totalSHow").append(data);  
Swal.fire('price'+ ' ' + data)


         }
       });
}else{
//    Swal.fire({{app()->getLocale() =='en'?'you should write all data ':'يجب ادخال كل البيانات'}})
console.log('ddd');
}
     }
</script>
<script>
    function Pricematerial_cost(){
   from  = $('#from_material_cost').val();
   to  = $('#to_material_cost').val();
console.log(from);
if(from && to){
   $.ajax({
     url: 'orders/material_cost/'+ from +'/'+ to,
     type: 'get',
     dataType: 'json',
     success: function(data){

//  $("#totalSHow").append(data);  
Swal.fire('price'+ ' ' + data)


     }
   });
}else{
//    Swal.fire({{app()->getLocale() =='en'?'you should write all data ':'يجب ادخال كل البيانات'}})
console.log('ddd');
}
 }
</script>
<script>
    function fetchDate(){
        from  = $('#from_Date').val();
        to  = $('#to_Date').val();
    
if(from && to){
        $.ajax({
          url: 'calculation/'+ from +'/'+ to,
          type: 'get',
          dataType: 'json',
          success: function(data){
     
//  $("#totalSHow").append(data);  
 Swal.fire('total' + ' ' + data)


          }
        });
}else{
    // Swal.fire({{app()->getLocale() =='en'?'you should write all data ':'يجب ادخال كل البيانات'}})
    console.log('ddd');
}
      }


 
</script>
<script>
    function orderCount(){
   from  = $('#from_count').val();
   to  = $('#to_count').val();
console.log(from);
if(from && to){
   $.ajax({
     url: 'orders/count/'+ from +'/'+ to,
     type: 'get',
     dataType: 'json',
     success: function(data){

//  $("#totalSHow").append(data);  
Swal.fire('orders count'+ ' ' + data)


     }
   });
}else{
//    Swal.fire({{app()->getLocale() =='en'?'you should write all data ':'يجب ادخال كل البيانات'}})
console.log('ddd');
}
 }
</script>
<script>
         $('#refresh').click(function(){
             console.log('here');
  $('#from_Date').val('');
  
  $('#to_Date').val('');


 });
</script>
<script>
    $('#refreshPrice').click(function(){

$('#from_Price').val('');

$('#to_Price').val('');


});
</script>
<script>
    $('#refreshCount').click(function(){

$('#from_count').val('');

$('#to_count').val('');


});
</script>
<script>
    $('#refreshmaterial_cost').click(function(){
        console.log('here');
$('#from_material_cost').val('');

$('#to_material_cost').val('');


});
</script>
@endsection
