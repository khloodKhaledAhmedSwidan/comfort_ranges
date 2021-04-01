@extends('admin.layouts.master')

@section('title')
    @lang('messages.add_order')@endsection

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
                <span>@lang('messages.add_order')</span>
            </li>
        </ul>
    </div>

    <h1 class="page-title"> @lang('messages.orders')
        <small>@lang('messages.add_order')</small>
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
                        <div class="portlet light ">
                            <div class="portlet-title tabbable-line">
                                <div class="caption caption-md">
                                    <i class="icon-globe theme-font hide"></i>
                                    <span class="caption-subject font-blue-madison bold uppercase">@lang('messages.add_order')</span>
                                </div>

                            </div>
                            <form role="form" action="{{route('create_order')}}" method="post" enctype="multipart/form-data">
                                <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>

                                <div class="portlet-body">

                                    <div class="tab-content">
                                        <!-- PERSONAL INFO TAB -->
                                        <div class="form-group">
                                            <label>@lang('messages.choose_branch')</label>

                                            <select  class="form-control" name="branch_id" required>
                                                <option value="" disabled
                                                        selected>@lang('messages.choose_branch')</option>
                                                @foreach(\App\Models\Branch::all() as $branch)
                                                    @if (\Illuminate\Support\Facades\Input::old('branch_id') == $branch->id)
                                                        <option value="{{ $branch->id }}" selected>{{app()->getLocale() =='en'?$branch->name:$branch->name_ar}}</option>
                                                    @else
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

                                                <select class="form-control select2" name="category_id" required >

                                                    <option selected
                                                            disabled>@lang('messages.choose_service') </option>

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

                                            <select class="form-control" name="user_id" required>
                                                <option value="" disabled
                                                        selected>@lang('messages.choose_client')
                                                </option>
                                                @foreach(\App\User::where('type' ,1 )->where('active',1)->get() as $client)
                                                    @if (\Illuminate\Support\Facades\Input::old('user_id') == $client->id)
                                                        <option value="{{ $client->id }}"
                                                                selected>{{$client->name}}</option>
                                                    @else
                                                        <option
                                                            value="{{ $client->id }}">{{$client->name}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            @error('user_id')
                                            <span class="invalid-feedback" role="alert">
                        <strong style="color:red;">{{ $message }}</strong>
                    </span>
                                            @enderror

                                        </div>
                                        <div class="form-group">
                                            <label> @lang('messages.employees')</label>

                                            <select class="form-control" name="employee_id" required>
                                                <option value="" disabled
                                                        selected>@lang('messages.choose_employee')
                                                </option>
                                                @foreach(\App\User::where('type' ,2 )->where('active',1)->get() as $employee)
                                                    @if (\Illuminate\Support\Facades\Input::old('employee_id') == $employee->id)
                                                        <option value="{{ $employee->id }}"
                                                                selected>{{$employee->name}}</option>
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
                                        <div class="form-group">
                                            <label class="control-label">@lang('messages.date') </label>
                                            <input type="date" name="date" placeholder="@lang('messages.date') " class="form-control" value="{{old('date')}}" required />
                                            @if ($errors->has('date'))
                                                <span class="help-block">
                                                       <strong style="color: red;">{{ $errors->first('date') }}</strong>
                                                    </span>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label> @lang('messages.Periods')</label>

                                            <select class="form-control" name="order_shift_id" required>
                                                <option value="" disabled
                                                        selected>@lang('messages.choose_period')
                                                </option>
                                                @foreach(\App\Models\OrderShift::all() as $shift)
                                                    @if (\Illuminate\Support\Facades\Input::old('employee_id') == $shift->id)
                                                        <option value="{{ $shift->id }}"
                                                                selected>@lang('messages.from'){{$shift->from}}@lang('messages.to'){{ $shift->to}}</option>
                                                    @else
                                                        <option
                                                            value="{{ $shift->id }}">@lang('messages.from') {{$shift->from }}@lang('messages.to'){{$shift->to}}</option>
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
                                            <textarea type="text" name="note" placeholder="@lang('messages.Note_on_order')  " class="form-control" value="{{old('note')}}"></textarea>
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


            <input type="text" id="lat" name="latitude" class="form-control mb-2"
                   readonly="yes" required/>

            @if ($errors->has('latitude'))

                <span class="help-block">

                            <strong style="color: red;">{{ $errors->first('latitude') }}</strong>

                        </span>

            @endif

            <input type="text" id="lng" name="longitude" class="form-control mb-2"
                   readonly="yes" required/>

            @if ($errors->has('longitude'))

                <span class="help-block">

                            <strong style="color: red;">{{ $errors->first('longitude') }}</strong>

                        </span>

            @endif

            <div id="map"></div>

        </div>

    </div>
</div>

                                        <div class="form-group">


                                        <div class="form-group ">
                                            <label class="control-label col-md-3">@lang('messages.images')</label>
                                            <div class="col-md-9">
                                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                                    <div class="fileinput-preview thumbnail"
                                                         data-trigger="fileinput"
                                                         style="width: 200px; height: 150px;">
                                                    </div>
                                                    <div>
                                                            <span class="btn red btn-outline btn-file">
                                                                <span class="fileinput-new">@lang('messages.choose_images') </span>
                                                                <span class="fileinput-exists"> @lang('messages.change') </span>
                                                                <input type="file" name="image[]" multiple> </span>
                                                        <a href="javascript:;" class="btn red fileinput-exists"
                                                           data-dismiss="fileinput"> @lang('messages.delete') </a>
                                                    </div>
                                                </div>
                                                @if ($errors->has('image'))
                                                    <span class="help-block">
                                                        <strong
                                                            style="color: red;">{{ $errors->first('image') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <!-- END PRIVACY SETTINGS TAB -->
                                    </div>


                            </div>
                            <input type="hidden" id="currentLang" name="currentLang" class="form-control mb-2" value="{{app()->getLocale()}}"/>
                                <div class="margiv-top-10">
                                    <div class="form-actions">
                                        <button type="submit" class="btn green" value="حفظ"  >@lang('messages.save')</button>

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
    <script
        src="https://code.jquery.com/jquery-3.5.1.min.js"
        integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
        crossorigin="anonymous"></script>
<script>

    $(document).ready(function () {


var lang = $('#currentLang').val();
console.log('lang' + lang);
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

                function getLocation() {

                    if (navigator.geolocation) {

                        navigator.geolocation.getCurrentPosition(showPosition);

                    } else {

                        x.innerHTML = "Geolocation is not supported by this browser.";

                    }

                }


                function showPosition(position) {

                    lat = position.coords.latitude;

                    lon = position.coords.longitude;


                    document.getElementById('lat').value = lat; //latitude

                    document.getElementById('lng').value = lon; //longitude

                    latlon = new google.maps.LatLng(lat, lon)

                    mapholder = document.getElementById('mapholder')

                    //mapholder.style.height='250px';

                    //mapholder.style.width='100%';


                    var myOptions = {

                        center: latlon,

                        zoom: 14,

                        mapTypeId: google.maps.MapTypeId.ROADMAP,

                        mapTypeControl: false,

                        navigationControlOptions: {

                            style: google.maps.NavigationControlStyle.SMALL

                        }

                    };

                    var map = new google.maps.Map(document.getElementById("map"), myOptions);

                    var marker = new google.maps.Marker({

                        position: latlon,

                        map: map,

                        title: "You are here!"

                    });

                }


            </script>

            <script type="text/javascript">

                var map;


                function initMap() {

                    var latitude = 24.774265; // YOUR LATITUDE VALUE

                    var longitude = 46.738586; // YOUR LONGITUDE VALUE

                    var myLatLng = {

                        lat: latitude,

                        lng: longitude

                    };

                    map = new google.maps.Map(document.getElementById('map'), {

                        center: myLatLng,

                        zoom: 5,

                        gestureHandling: 'true',

                        zoomControl: false // disable the default map zoom on double click

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

                    google.maps.event.addListener(map, 'click', function (event) {

                        //Get the location that the user clicked.

                        var clickedLocation = event.latLng;

                        //If the marker hasn't been added.

                        if (marker === false) {

                            //Create the marker.

                            marker = new google.maps.Marker({

                                position: clickedLocation,

                                map: map,

                                draggable: true //make it draggable

                            });

                            //Listen for drag events!

                            google.maps.event.addListener(marker, 'dragend', function (event) {

                                markerLocation();

                            });

                        } else {

                            //Marker has already been added, so just change its location.

                            marker.setPosition(clickedLocation);

                        }

                        //Get the marker's location.

                        markerLocation();

                    });


                    function markerLocation() {

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
