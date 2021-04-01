<!DOCTYPE html>
<html>
<head>
    <title> Application Form</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('bill_design/css/style.css')}}">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script></head>
<body>
<div class="testbox" style="max-width: 1200px;margin: auto;">
    <form action="/">

        <div class="banner" style="background-image: url(http://bwpheritagehotel.com/wp-content/uploads/2018/12/keyboard-typing-banner.jpg);">
            <h1 style="color:#fff;">{{app()->getLocale() =='en' ?'Job Order'. $order->real_num: 'رقم الطلب' . $order->real_num}}</h1>
        </div>

         <div style="text-align:center;padding-top:15px"><img src="{{ asset('img/'.App\Models\Setting::find(1)->image) }}" style="width: 180px;" /> </div>


        <div class="item">
            <p>@lang('messages.date')</p>
            <input type="date" name="bdate" value="{{$order->date}}" placeholder="@lang('messages.date')" readonly required/>
            <i class="fas fa-calendar-alt"></i>
        </div>
        <div class="item">
            <p>@lang('messages.client_name')<span class="required">*</span></p>
            <input type="text" value="{{\App\User::find($order->user_id)->name}}" readonly name="name" placeholder="@lang('messages.client_name')"/>
        </div>
        <div class="item">
            <p>@lang('messages.phone_number')<span class="required">*</span></p>
            <input type="text"  value="{{\App\User::find($order->user_id)->phone}}" readonly name="name" placeholder="@lang('messages.phone_number')"/>
        </div>
        <div class="item">
            <p> @lang('messages.Location')<span class="required">*</span></p>
            <input type="text" name="location" placeholder=" @lang('messages.Location')" value="{{ $order->location != null ?\App\Models\Location::find($order->location_id)->name: __('messages.Location')}}" readonly/>
        </div>
        <div class="item">
            <p>@lang('messages.description')<span class="required">*</span></p>
            <textarea rows="3" name="employee_note" readonly placeholder="@lang('messages.description')" value="{{old('description_ar')}}" required > {{$order->employee_note}}</textarea>

        </div>
        <div class="item">
            <p>@lang('messages.ActualTime') <span class="required">*</span></p>
            <div class="actual-time">
                <input type="time" name="from" readonly value="{{$order->from}}" placeholder=">@lang('messages.from')" required/>

                <input type="time" name="to" readonly placeholder="@lang('messages.to')"  value="{{$order->to}}" required/>


            </div>
            @if($order->complete_in_another_day == 1 || $order->complete_in_another_day == 2)
            @if($order->timeOrders()->count() > 0)
            @foreach($order->timeOrders()->get() as $timeOrder)
            <div class="actual-time">
                <input type="date" name="date" readonly value="{{$timeOrder->date}}" placeholder=">@lang('messages.date')" required/>

                <input type="time" name="start" readonly value="{{$timeOrder->end}}" placeholder=">@lang('messages.from')" required/>

                <input type="time" name="end" readonly placeholder="@lang('messages.to')"  value="{{$timeOrder->start}}" required/>


            </div>
            @endforeach
            @endif
            @endif
        </div>
@if($order->rate()->count() > 0)
        @php
            $value_of_rate =  $order->rate->rate;
        $rate = $value_of_rate == 3 ?'ممتاز':($value_of_rate == 2 ?'جيد':($value_of_rate == 1 ?'غير مرضي':'لا يوجد تقييم بعد'));

               $Rate = \App\Models\Rate::find($order->rate()->first()->id);


        @endphp

                <div class="question item">
                    <p>@lang('messages.sentences_of_evaluation_choosen')<span class="required">*</span></p>

                    <div class="question-answer checkbox-item">
                        @foreach($Rate->sentences as $sentence)
                        <div>
                            <input type="checkbox" value="none" id="check_1" name="checklist" checked="checked" readonly required/>
                            <label for="check_1" class="check"><span>{{app()->getLocale() == 'en'?$sentence->sentence : $sentence->sentence_ar}}</span></label>
                        </div>
                        @endforeach







                    </div>
                </div>


                <div class="question item">
                    <p>@lang('messages.rate_our_Service')</p>
                    <div class="question-answer">


                        @if($value_of_rate == 3)
                            <input type="radio" value="none" id="radio_1" name="investigator" checked="checked" readonly required/>
                            <label for="radio_1" class="radio"><span><img src="{{asset('bill_design/img/smiling.png')}}" style="width: 19px;" /> @lang('messages.Excellent')</span></label>
                            <input type="radio" readonly value="none" id="radio_2" name="investigator" required/>
                            <label for="radio_2" class="radio"><span><img src="{{asset('bill_design/img/smile.png')}}" style="width: 19px;" /> @lang('messages.Good')</span></label>
                            <input type="radio" readonly value="none" id="radio_3" name="investigator" required/>
                            <label for="radio_3" class="radio"><span><img src="{{asset('bill_design/img/sad.png')}}" style="width: 19px;" /> @lang('messages.Bad')</span></label>
                        @elseif($value_of_rate == 2)
                            <input type="radio" readonly value="none" id="radio_1" name="investigator"  required/>
                            <label for="radio_1" class="radio"><span><img src="{{asset('bill_design/img/smiling.png')}}" style="width: 19px;" /> @lang('messages.Excellent')</span></label>
                            <input checked="checked" readonly type="radio" value="none" id="radio_2" name="investigator" required/>
                            <label for="radio_2" class="radio"><span><img src="{{asset('bill_design/img/smile.png')}}" style="width: 19px;" /> @lang('messages.Good')</span></label>
                            <input type="radio" readonly value="none" id="radio_3" name="investigator" required/>
                            <label for="radio_3" class="radio"><span><img src="{{asset('bill_design/img/sad.png')}}" style="width: 19px;" /> @lang('messages.Bad')</span></label>
                        @elseif($value_of_rate == 1)
                            <input type="radio" readonly value="none" id="radio_1" name="investigator"  required/>
                            <label for="radio_1" class="radio"><span><img src="{{asset('bill_design/img/smiling.png')}}" style="width: 19px;" />   @lang('messages.Excellent')</span></label>
                            <input type="radio" readonly value="none" id="radio_2" name="investigator" required/>
                            <label for="radio_2" class="radio"><span><img src="{{asset('bill_design/img/smile.png')}}" style="width: 19px;" />  @lang('messages.Good')</span></label>
                            <input type="radio"  checked="checked" readonly value="none" id="radio_3" name="investigator" required/>
                            <label for="radio_3" class="radio"><span><img src="{{asset('bill_design/img/sad.png')}}" style="width: 19px;" /> @lang('messages.Bad')</span></label>
                        @else
                            <input readonly type="radio" value="none" id="radio_1" name="investigator" required/>
                            <label for="radio_1" class="radio"><span><img src="{{asset('bill_design/img/smiling.png')}}" style="width: 19px;" /> Excellent</span></label>
                            <input readonly type="radio" value="none" id="radio_2" name="investigator" required/>
                            <label for="radio_2" class="radio"><span><img src="{{asset('bill_design/img/smile.png')}}" style="width: 19px;" /> Good</span></label>
                            <input readonly type="radio" value="none" id="radio_3" name="investigator" required/>
                            <label for="radio_3" class="radio"><span><img src="{{asset('bill_design/img/sad.png')}}" style="width: 19px;" /> Bad</span></label>
                            @endif

                    </div>
                </div>
        @if($order->rate->comment != null)
            <div class="item">
                <p>@lang('messages.comments')<span class="required">*</span></p>
                <textarea rows="3" name="employee_note" readonly placeholder="@lang('messages.comments')"  required > {{$order->rate->comment}}</textarea>

            </div>
        @endif
        @endif


        <div class="question item">
            <p>@lang('messages.is_paid')</p>
  
            <div class="question-answer">
                <input readonly  checked="checked" type="radio" value="none" id="radio_1" name="is_paid" required />
                <label for="radio_1" class="radio"><span>
                    
                @if($order->is_paid == 0)
                لم يتم الدفع
                @elseif($order->is_paid == 1)
                تم الدفع :كاش
                @elseif($order->is_paid == 2)
                تم الدفع : فيزا
                @endif
                </span></label>
{{--                <input type="radio" value="none" id="radio_2" name="investigator" required/>--}}
{{--                <label for="radio_2" class="radio"><span>SPAN</span></label>--}}

            </div>
        </div>

        <div class="item">
            <p>@lang('messages.The_cost_of_the_service') <span class="required">*</span></p>
            <input type="number" name="price" readonly placeholder="@lang('messages.The_cost_of_the_service')" value="{{$order->price}}"/>
        </div>

        <div class="item">
            <p>@lang('messages.material_cost') <span class="required">*</span></p>
            <input readonly type="number" name="material_cost"  placeholder="@lang('messages.material_cost')" value="{{$order->material_cost}}"/>
        </div>

        <div class="item">
            <p>@lang('messages.tax_price') <span class="required">*</span></p>
            <input readonly type="number" name="tax_price" placeholder="@lang('messages.tax_price')"   value="{{\App\Models\Setting::find(1)->tax}}"/>
        </div>

        <div class="item">
            <p>@lang('messages.Total')<span class="required">*</span></p>
            <input readonly type="number" name="total" placeholder="@lang('messages.Total')" value="{{$order->total}}"/>
        </div>
@if($order->notes_on_what_was_done != null)
        <div class="item">
            <p>@lang('messages.what_was_done')<span class="required">*</span></p>
            <textarea rows="3" name="notes_on_what_was_done" readonly placeholder="@lang('messages.what_was_done')" value="{{old('notes_on_what_was_done')}}" required > {{$order->notes_on_what_was_done}}</textarea>

        </div>
@endif

        <div class="btn-block">
            <a  class="btn btn-primary button"  href="{{route('completed_orders')}}">@lang('messages.back')</a>
            <h3 class="text-center"><a href="#" class="printPage btn btn-info btn-sm">{{app()->getLocale() == 'en'?'print':'طباعه'}}</a></h3>
        </div>
    </form>
</div>
<script
    src="https://code.jquery.com/jquery-3.5.1.min.js"
    integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
    crossorigin="anonymous"></script>
  

      <script>
  
          $(document).ready(function () {
              $('a.printPage').click(function(){
      
                  window.print();
                  return false;
              });
  
  
              // $("a.printPage").click(function () {
              //     $("#printarea").print();
              // });
          });
      </script>
</body>
</html>
