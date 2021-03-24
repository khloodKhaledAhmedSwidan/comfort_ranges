@extends('admin.layouts.master')

@section('title')
    @lang('messages.show_order')
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
                <span>  @lang('messages.show_order')</span>
            </li>
        </ul>
    </div>

    <h1 class="page-title"> @lang('messages.orders')
        <small>  @lang('messages.show_order')</small>
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
                                @if($order->is_paid == 0 && $order->status == 3)
                                    <div>
                                        <p class="alecrt alert-danger blink_me">
                                            @lang('messages.The_order_has_not_been_paid_for')
                                            @lang('messages.Order_value') :- {{$order->total}}
                                        </p>
                                    </div>
                                @endif
                                @if($order->is_paid == 0 && $order->status == 4)
                                    <div>
                                        <p class="alecrt alert-danger blink_me">
                                            @lang('messages.The_tax_has_not_been_paid')
                                            @lang("messages.Tax_value") :- {{\App\Models\Setting::find(1)->tax  }}
                                        </p>
                                    </div>
                                @endif

                            </div>
                            <form role="form"
                                  enctype="multipart/form-data">
                                <input type='hidden' name='_token' value='{{Session::token()}}'>
                                <div class="portlet-body">

                                    <div class="tab-content">

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

                                        @if($order->complete_in_another_day == 1 || $order->complete_in_another_day == 2)
                                        @if($order->timeOrders()->count() > 0)
                                        @foreach($order->timeOrders()->get() as $timeOrder)
                                        <div class="form-group">
                                            <label class="control-label">@lang('messages.date') </label>
                                            <input type="date" readonly class="form-control"
                                                   value="{{$timeOrder->date}}"/>

                                        </div>
                                        <div class="form-group">
                                            <label> @lang('messages.period')</label>

                                            <input type="text" readonly class="form-control"
                                                   value="@lang('messages.from'){{\App\Models\OrderShift::where('id' ,$timeOrder->order_shift_id )->first()->from}}@lang('messages.to'){{\App\Models\OrderShift::where('id' ,$timeOrder->order_shift_id )->first()->to}}"/>


                                        </div>
                                        @endforeach
                                        @endif
                                        @endif

                                        @if($order->from != null && $order->status == 3)
                                            <div class="form-group">
                                                <label> @lang('messages.start_order')</label>

                                                <input type="text" readonly class="form-control"
                                                       value="{{  \Carbon\Carbon::parse($order->from)->format('h:i') }}"/>


                                            </div>
                                        @endif
                                        @if($order->to != null && $order->status == 3)
                                            <div class="form-group">
                                                <label> @lang('messages.end_order')</label>

                                                <input type="text" readonly class="form-control"
                                                       value="{{  \Carbon\Carbon::parse($order->to)->format('h:i') }}"/>


                                            </div>
                                        @endif
                                        <div class="form-group">

                                            @if($order->price != null && $order->status == 3)
                                                @lang('messages.price') :-     {{$order->price}},
                                            @endif
                                            @if($order->material_cost != null && $order->status == 3)
                                                @lang('messages.material_cost')  :-    {{$order->material_cost}},
                                            @endif           @if($order->total != null && $order->status == 3)
                                                @lang('messages.Total'):-   {{$order->total}}
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label"> @lang('messages.Note_on_order')</label>
                                            <textarea type="text" readonly
                                                      class="form-control">{{$order->note}}</textarea>

                                        </div>

                                        @if($order->status == 3 && $order->is_paid == 1)
                                   
@if($order->rate()->count())
                                            <div>
                                                <h2>@lang('messages.evaluation_singular') </h2>
                                                <label
                                                    class="control-label"> @lang('messages.Status_evaluation') </label>
                                                @php
                                                    $value_of_rate =  $order->rate->rate;
                                                $rate = $value_of_rate == 3 ?'ممتاز':($value_of_rate == 2 ?'جيد':($value_of_rate == 1 ?'غير مرضي':'لا يوجد تقييم بعد'));

                                                       $Rate = \App\Models\Rate::find($order->rate()->first()->id);


                                                @endphp
                                                <input type="text" readonly
                                                       class="form-control" value="@if($value_of_rate == 3)
                                                @lang('messages.Excellent')
                                                @elseif($value_of_rate == 2)
                                                @lang('messages.Good')
                                                @elseif($value_of_rate == 1)
                                                @lang('messages.Bad')
                                                @else
                                                @lang('messages.There_is_no_evaluation_yet')
                                                @endif"/>


                                                <br/>

                                                <label
                                                    class="control-label"> @lang('messages.sentences_of_evaluation_choosen') </label>
                                                <ul>
                                                    @foreach($Rate->sentences as $sentence)
                                                        <li>{{app()->getLocale() == 'en'?$sentence->sentence : $sentence->sentence_ar}}</li>
                                                    @endforeach
                                                </ul>


@if($order->rate->comment != null)
    <p>{{$order->rate->comment}}</p>
    @endif
                                            </div>

                                    @endif

                                    @endif

                                    <!-- END PRIVACY SETTINGS TAB -->
                                    </div>

                                </div>
                        
                                @if($order->images()->count() > 0)
                                <a href="{{route('showImagesOfOrder',$order->id)}}"
                                   class="btn btn-info">@lang('messages.show_images')
                                </a>
                                @endif
                                @if($order->vedio != null)
                                    <a href="{{route('showVedioOfOrder',$order->id)}}"
                                       class="btn btn-info">@lang('messages.show_vedio')
                                    </a>
                                @endif

                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#exampleModal{{$order->id}}">
                                    @lang('messages.show_location')
                                </button>
                                <div class="modal fade" id="exampleModal{{$order->id}}" tabindex="-1" role="dialog"
                                     aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title"
                                                    id="exampleModalLabel">@lang('messages.show_order_location')</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">


                                                <div class="body-site hideThis">
                                                    <div class="d-flex">
                                                        <div class="col-m-9">

                                                            <div class="content sections">
                                                                <input type="hidden" id="lat" name="lat"
                                                                       value="{{$order->latitude != null ?$order->latitude : \App\Models\Location::find($order->location_id)->latitude}}">
                                                                <input type="hidden" id="long" name="long"
                                                                       value="{{$order->longitude != null ? $order->longitude :\App\Models\Location::find($order->location_id)->longitude}}">
                                                                <div id="map"></div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">@lang('messages.close')</button>
                                            </div>
                                        </div>
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

    <script type="text/javascript">
        var map;

        function initMap() {
            var lat = Number.parseFloat($("#lat").val());
            var long = Number.parseFloat($("#long").val());
            var latitude = lat // YOUR LATITUDE VALUE
            var longitude = long; // YOUR LONGITUDE VALUE
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

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAFUMq5htfgLMNYvN4cuHvfGmhe8AwBeKU&callback=initMap"
            async
            defer></script>
@endsection
