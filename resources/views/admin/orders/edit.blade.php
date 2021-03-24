@extends('admin.layouts.master')

@section('title')
    @lang('messages.edit_order')
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ URL::asset('admin/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/select2-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/bootstrap-fileinput.css') }}">
    <style>
        #map {
            height: 500px;
            width: 500px;
        }
    </style>
@endsection

@section('page_header')
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <a href="/admin/home">@lang('messages.dashboard')</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="{{url('/admin/new-orders')}}">@lang('messages.orders')</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>@lang('messages.edit_order')</span>
            </li>
        </ul>
    </div>

    <h1 class="page-title"> @lang('messages.orders')
        <small>@lang('messages.edit_order')</small>
    </h1>
@endsection

@section('content')

    @include('flash::message')


    <!-- END PAGE TITLE-->
    <!-- END PAGE HEADER-->
    <div class="row">
        <div class="col-md-12">

            <!-- BEGIN PROFILE CONTENT -->
            <div class="profile-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light ">
                            <div class="portlet-title tabbable-line">


                            </div>
                            <form role="form" action="{{route('update_orderPage',$order->id)}}" method="post"
                                  enctype="multipart/form-data">
                                <input type='hidden' name='_token' value='{{Session::token()}}'>
                                <div class="portlet-body">

                                    <div class="tab-content">

                                        <div class="form-group">
                                            <label>@lang('messages.choose_branch')</label>

                                            <select class="form-control" name="branch_id">

                                                <option
                                                    value="{{$branch = \App\Models\Category::find($order->category_id)->branch_id}}"
                                                    selected>{{app()->getLocale() =='en'?\App\Models\Branch::find($branch)->name:\App\Models\Branch::find($branch)->name_ar}}</option>
                                                @foreach(\App\Models\Branch::all() as $branch)
                                                    @if (\App\Models\Category::find($order->category_id)->branch_id != $branch->id)
                                                        <option value="{{ $branch->id }}">{{app()->getLocale() =='en'?$branch->name:$branch->name_ar}}</option>

                                                    @endif
                                                @endforeach
                                            </select>
                                            @error('branch_id')
                                            <span class="invalid-feedback" role="alert">
                        <strong style="color:red;">{{ $message }}</strong>
                    </span>
                                            @enderror

                                        </div>


                                        <div class="form-group">

                                            <label for="name">@lang('messages.choose_service')</label>

                                            <div class="total" id="category-selection">

                                                {{-- <span> @lang('messages.choose-branch') : </span> --}}

                                                <select class="form-control select2" name="category_id" required>

                                                    <option selected
                                                            disabled>@lang('messages.choose_service')
                                                    </option>

                                                </select>

                                                @error('category_id')

                                                <span class="help-block">

                                <strong style="color: red;">

                                    {{ $errors->first('category_id') }}

                                </strong>

                            </span>

                                                @enderror

                                            </div>


                                        </div>
                                        <div class="form-group">
                                            <label> @lang('messages.clients')</label>

                                            <input readonly type="text" name="user_id"  class="form-control"
                                            value="{{App\User::where('id' ,$order->user_id)->first()->name}}"/>
                                     @if ($errors->has('user_id'))
                                         <span class="help-block">
                                                <strong style="color: red;">{{ $errors->first('user_id') }}</strong>
                                             </span>
                                     @endif
                                        </div>
                                        <div class="form-group">
                                            <label> @lang('messages.employees')</label>

                                            <input readonly type="text" name="employee_id"  class="form-control"
                                            value="{{App\User::where('id' ,$order->employee_id )->first()->name}}"/>
                                     @if ($errors->has('employee_id'))
                                         <span class="help-block">
                                                <strong style="color: red;">{{ $errors->first('employee_id') }}</strong>
                                             </span>
                                     @endif
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">@lang('messages.date') </label>
                                            <input type="date" name="date" placeholder="@lang('messages.date')  " class="form-control"
                                                   value="{{$order->date}}"/>
                                            @if ($errors->has('date'))
                                                <span class="help-block">
                                                       <strong style="color: red;">{{ $errors->first('date') }}</strong>
                                                    </span>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label> @lang('messages.Periods')</label>


                                            <select class="form-control" name="order_shift_id">

                                                <option
                                                    value="{{\App\Models\OrderShift::where('id' ,$order->order_shift_id )->first()->id}}"
                                                    selected>@lang('messages.from'){{\App\Models\OrderShift::where('id' ,$order->order_shift_id )->first()->from }}@lang('messages.to'){{\App\Models\OrderShift::where('id' ,$order->order_shift_id )->first()->to}}</option>
                                                @foreach(\App\Models\OrderShift::all() as $shift)
                                                    @if ($shift->id != $order->order_shift_id   )
                                                        <option
                                                            value="{{ $shift->id }}">@lang('messages.from'){{$shift->from }}@lang('messages.to'){{$shift->to}}</option>

                                                    @endif
                                                @endforeach
                                            </select>


                                            @error('order_shift_id')
                                            <span class="invalid-feedback" role="alert">
                        <strong style="color:red;">{{ $message }}</strong>
                    </span>
                                            @enderror

                                        </div>
                                        <div class="form-group">
                                            <label class="control-label"> @lang('messages.Note_on_order')</label>
                                            <textarea type="text" name="note" placeholder=" @lang('messages.Note_on_order') "
                                                      class="form-control">{{$order->note}}</textarea>
                                            @if ($errors->has('note'))
                                                <span class="help-block">
                                                       <strong style="color: red;">{{ $errors->first('note') }}</strong>
                                                    </span>
                                            @endif
                                        </div>

                                        <div class="form-group">

                                            <div class=" form-group " id="hide-map">


                                                <div class="content sections">


                                                    <div class="wrap-title d-flex justify-content-between mm">

                                                        {{--                    <h6>--}}

                                                        {{--                        <i class="fas fa-map-marker-alt"></i> @lang('messages.specify_your_location')--}}

                                                        {{--                    </h6>--}}

                                                        <a onclick="getLocation();" > <i
                                                                class="btn btn-primary ">@lang('messages.specify_location')</i>

                                                        </a>

                                                    </div>


                                                    <input type="hidden" id="lat" name="latitude" class="form-control mb-2"
                                                           readonly="yes" value="{{$order->latitude}}" required/>

                                                    @if ($errors->has('latitude'))

                                                        <span class="help-block">

                            <strong style="color: red;">{{ $errors->first('latitude') }}</strong>

                        </span>

                                                    @endif

                                                    <input type="hidden" id="lng" name="longitude" class="form-control mb-2"
                                                           readonly="yes" value="{{$order->longitude}}" required/>

                                                    @if ($errors->has('longitude'))

                                                        <span class="help-block">

                            <strong style="color: red;">{{ $errors->first('longitude') }}</strong>

                        </span>

                                                    @endif

                                                    <div id="map"></div>

                                                </div>

                                            </div>
                                        </div>

                                        <div class="form-body">
                                            <div class="form-group ">
                                                <label
                                                    class="control-label col-md-3">@lang('messages.images')</label>
                                                <div class="col-md-9">
                                                    <div class="fileinput fileinput-new">

                                                        @if($order->images()->count()>0)
                                                            @foreach($order->images()->get() as $img)

                                                                <div
                                                                    class="fileinput-preview thumbnail img_{{ $img->id }}"

                                                                    style="position:relative;width: 19.2%;">
                                                                    <a id="{{ $img->id }}"
                                                                       style="position: absolute;top: 2%;left: 1%;color: white;text-decoration: none;"
                                                                       class="delete_image hideDiv btn btn-danger">
                                                                        <i class="glyphicon glyphicon-trash "></i> </a>
                                                                    <img
                                                                        src="{{asset('uploads/orders/'.$img->image)}}"
                                                                        alt="" id="file_name"
                                                                        style="max-width:100%; height: 140px;">

                                                                </div>



                                                            @endforeach

                                                        @endif
                                                        <input type="file" name="image[]" multiple
                                                               placeholder=" @lang('messages.choose_images')">
                                                        @if ($errors->has('image'))
                                                            <span class="help-block">
                                                        <strong style="color: red;">{{ $errors->first('image') }}
                                                        </strong>
                                                    </span>
                                                        @endif


                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                        <!-- END PRIVACY SETTINGS TAB -->
                                    </div>
                                    <input type="hidden" id="currentLang" name="currentLang" class="form-control mb-2" value="{{app()->getLocale()}}"/>

                                </div>
                                <div class="margiv-top-10">
                                    <div class="form-actions">
                                        <button type="submit" class="btn green" value="حفظ"
                                                onclick="this.disabled=true;this.value='تم الارسال, انتظر...';this.form.submit();">
                                            @lang('messages.save')
                                        </button>

                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END PROFILE CONTENT -->
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ URL::asset('admin/js/select2.full.min.js') }}"></script>
    <script src="{{ URL::asset('admin/js/components-select2.min.js') }}"></script>
    <script src="{{ URL::asset('admin/js/bootstrap-fileinput.js') }}"></script>

    <script>
        $(".delete_image").click(function () {
            var id = $(this).attr('id');
            var url = '{{ route("remove_image", ":id") }}';

            url = url.replace(':id', id);

            //alert(image_id );
            $.ajax({
                url: url,
                type: 'GET',
                success: function (result) {
                    if (!result.message) {
                        $(".img_" + id).fadeOut('1000');
                    }

                }
            });
        });
    </script>


    <script>

        $(document).ready(function () {
            var lang = $('#currentLang').val();
            $('select[name="branch_id"]').on('change', function () {

                var branch_id = $(this).val();
                if (branch_id) {

                    $.ajax({

                        url: '/admin/get_categories/' + branch_id,

                        type: 'GET',

                        datatype: 'json',

                        success: function (data) {
                            console.log(data)
                            $('select[name="category_id"]').empty();
                            $.each(data, function (key, value) {

                           
                                if(lang == 'en'){
    $('select[name="category_id"]').append('<option value="' + value['id'] + '">' + value['name'] + '</option>');

  }else{
    $('select[name="category_id"]').append('<option value="' + value['id'] + '">' + value['name_ar'] + '</option>');
  }
                            });

                        }

                    });

                } else {

                    $('select[name="branch_id"]').empty();


                }


            });


        });

    </script>

    <script>
        function getLocation()
        {
            if (navigator.geolocation)
            {
                navigator.geolocation.getCurrentPosition(showPosition);
            }
            else{x.innerHTML="Geolocation is not supported by this browser.";}
        }

        function showPosition(position)
        {
            lat= position.coords.latitude;
            lon= position.coords.longitude;

            document.getElementById('lat').value = lat; //latitude
            document.getElementById('lng').value = lon; //longitude
            latlon=new google.maps.LatLng(lat, lon)
            mapholder=document.getElementById('mapholder')
            //mapholder.style.height='250px';
            //mapholder.style.width='100%';

            var myOptions={
                center:latlon,zoom:14,
                mapTypeId:google.maps.MapTypeId.ROADMAP,
                mapTypeControl:false,
                navigationControlOptions:{style:google.maps.NavigationControlStyle.SMALL}
            };
            var map = new google.maps.Map(document.getElementById("map"),myOptions);
            var marker=new google.maps.Marker({position:latlon,map:map,title:"You are here!"});
        }

    </script>
    <script type="text/javascript">
        var map;

        function initMap() {
            var latitude = {{$order->longitude}}; // YOUR LATITUDE VALUE
            var longitude = {{$order->latitude}}; // YOUR LONGITUDE VALUE
            var myLatLng = {lat: latitude, lng: longitude};
            map = new google.maps.Map(document.getElementById('map'), {
                center: myLatLng,
                zoom: 5,
                gestureHandling: 'true',
                zoomControl: false// disable the default map zoom on double click
            });

            var marker = new google.maps.Marker({
                position: myLatLng,
                map: map,
                //title: 'Hello World'

                // setting latitude & longitude as title of the marker
                // title is shown when you hover over the marker
                title: latitude + ', ' + longitude
            });

            //Listen for any clicks on the map.
            google.maps.event.addListener(map, 'click', function(event) {
                //Get the location that the user clicked.
                var clickedLocation = event.latLng;
                //If the marker hasn't been added.
                if(marker === false){
                    //Create the marker.
                    marker = new google.maps.Marker({
                        position: clickedLocation,
                        map: map,
                        draggable: true //make it draggable
                    });
                    //Listen for drag events!
                    google.maps.event.addListener(marker, 'dragend', function(event){
                        markerLocation();
                    });
                } else{
                    //Marker has already been added, so just change its location.
                    marker.setPosition(clickedLocation);
                }
                //Get the marker's location.
                markerLocation();
            });

            function markerLocation(){
                //Get location.
                var currentLocation = marker.getPosition();
                //Add lat and lng values to a field that we can save.
                document.getElementById('lat').value = currentLocation.lat(); //latitude
                document.getElementById('lng').value = currentLocation.lng(); //longitude
            }
        }
    </script>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAFUMq5htfgLMNYvN4cuHvfGmhe8AwBeKU&callback=initMap" async
            defer></script>

@endsection
