@extends('admin.layouts.master')

@section('title')
   @lang('messages.add_clients')
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
                <a href="{{url('/admin/clients')}}">@lang('messages.clients')</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>@lang('messages.add_clients')</span>
            </li>
        </ul>
    </div>

    <h1 class="page-title"> @lang('messages.clients')
        <small>@lang('messages.add_clients')</small>
    </h1>
@endsection

@section('content')



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
                                <div class="caption caption-md">
                                    <i class="icon-globe theme-font hide"></i>
                                    <span
                                        class="caption-subject font-blue-madison bold uppercase">@lang('messages.Profile_account')</span>
                                </div>
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#tab_1_1" data-toggle="tab">@lang('messages.personal_information')</a>
                                    </li>
                                    <li>
                                        <a href="#tab_1_3" data-toggle="tab">@lang('messages.specify_location')</a>
                                    </li>
                                    <li>
                                        <a href="#tab_1_4" data-toggle="tab">@lang('messages.Privacy_settings')</a>
                                    </li>
                                </ul>
                            </div>
                            <form role="form" action="/admin/add/client" method="post" enctype="multipart/form-data">
                                <input type='hidden' name='_token' value='{{Session::token()}}'>
                                <div class="portlet-body">

                                    <div class="tab-content">
                                        <!-- PERSONAL INFO TAB -->
                                        <div class="tab-pane active" id="tab_1_1">


                                            <div class="form-group">
                                                <label class="control-label">@lang('messages.name')</label>
                                                <input type="text" name="name" placeholder="@lang('messages.name')" class="form-control"
                                                       value="{{old('name')}}"/>
                                                @if ($errors->has('name'))
                                                    <span class="help-block">
                                                       <strong style="color: red;">{{ $errors->first('name') }}</strong>
                                                    </span>
                                                @endif
                                            </div>


                                            <div class="form-group">
                                                <label class="control-label">@lang('messages.phone_number')</label>

                                                <input type="number" name="phone" placeholder="@lang('messages.phone_number')"
                                                       class="form-control" value="{{old('phone')}}"/>
                                                @if ($errors->has('phone'))
                                                    <span class="help-block">
                                                       <strong
                                                           style="color: red;">{{ $errors->first('phone') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label">@lang('messages.password')</label>
                                                <input type="password" name="password" class="form-control"/>
                                                @if ($errors->has('password'))
                                                    <span class="help-block">
                                                       <strong
                                                           style="color: red;">{{ $errors->first('password') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label">@lang('messages.password_confirmation')</label>
                                                <input type="password" name="password_confirmation"
                                                       class="form-control"/>
                                                @if ($errors->has('password_confirmation'))
                                                    <span class="help-block">
                                                       <strong
                                                           style="color: red;">{{ $errors->first('password_confirmation') }}</strong>
                                                    </span>
                                                @endif
                                            </div>


                                            <div class="form-body">
                                                <div class="form-group ">
                                                    <label class="control-label col-md-3">@lang('messages.image')</label>
                                                    <div class="col-md-9">
                                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                                            <div class="fileinput-preview thumbnail"
                                                                 data-trigger="fileinput"
                                                                 style="width: 200px; height: 150px;">
                                                            </div>
                                                            <div>
                                                            <span class="btn red btn-outline btn-file">
                                                                <span class="fileinput-new"> @lang('messages.choose_image') </span>
                                                                <span class="fileinput-exists"> @lang('messages.change') </span>
                                                                <input type="file" name="image"> </span>
                                                                <a href="javascript:;" class="btn red fileinput-exists"
                                                                   data-dismiss="fileinput"> @lang('messages.remove') </a>


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
                                            </div>

                                        </div>
                                        <!-- END PERSONAL INFO TAB -->
                                        <div class="tab-pane" id="tab_1_3" >


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
                                                           readonly="yes" required/>

                                                    @if ($errors->has('latitude'))

                                                        <span class="help-block">

                            <strong style="color: red;">{{ $errors->first('latitude') }}</strong>

                        </span>

                                                    @endif

                                                    <input type="hidden" id="lng" name="longitude" class="form-control mb-2"
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

                                        <!-- PRIVACY SETTINGS TAB -->
                                        <div class="tab-pane" id="tab_1_4">

                                            <table class="table table-light table-hover">

                                                <tr>
                                                    <td> @lang('messages.active_customer')</td>
                                                    <td>
                                                        <div class="mt-radio-inline">
                                                            <label class="mt-radio">
                                                                <input type="radio" name="active"
                                                                       value="1" {{ old('active') == "1" ? 'checked' : '' }}/>
                                                                @lang('messages.yes_active')
                                                                <span></span>
                                                            </label>
                                                            <label class="mt-radio">
                                                                <input type="radio" name="active"
                                                                       value="0" {{ old('active') == "0" ? 'checked' : '' }}/>
                                                                @lang('messages.no_active')
                                                                <span></span>
                                                            </label>
                                                            @if ($errors->has('active'))
                                                                <span class="help-block">
                                                                       <strong
                                                                           style="color: red;">{{ $errors->first('active') }}</strong>
                                                                    </span>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>


                                            </table>


                                        </div>


                                        <!-- END PRIVACY SETTINGS TAB -->
                                    </div>

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



    <script>
        $(document).ready(function () {
            $('select[name="address[country]"]').on('change', function () {
                var id = $(this).val();
                $.ajax({
                    url: '/get/cities/' + id,
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                        $('#register_city').empty();


                        $('select[name="address[city]"]').append('<option value>المدينة</option>');
                        // $('select[name="city"]').append('<option value>المدينة</option>');
                        $.each(data['cities'], function (index, cities) {

                            $('select[name="address[city]"]').append('<option value="' + cities.id + '">' + cities.name + '</option>');

                        });


                    }
                });


            });
        });
    </script>
@endsection
