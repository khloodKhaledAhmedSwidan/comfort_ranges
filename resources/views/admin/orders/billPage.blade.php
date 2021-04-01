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
    <form method="post" action="{{route('editBill',$order->id)}}" enctype="multipart/form-data" >
        <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>

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
            <textarea rows="3" name="employee_note"  placeholder="@lang('messages.description')" value="{{old('description_ar')}}" required > {{$order->employee_note}}</textarea>

        </div>
        <div class="item">
            <p>@lang('messages.ActualTime') <span class="required">*</span></p>
            <div class="actual-time">
                <input type="time" name="from"  value="{{$order->from}}" placeholder=">@lang('messages.from')" required/>

                <input type="time" name="to"  placeholder="@lang('messages.to')"  value="{{$order->to}}" required/>


            </div>
        </div>



        <div class="question item">
            <p>@lang('messages.is_paid')</p>
            @if($order->is_paid == 0)
                <div class="question-answer">
                    <input   checked="checked" type="radio" value="0" id="radio_1" name="is_paid" required />
                    <label for="radio_1" class="radio"><span>{{app()->getLocale() =='en' ?'No payment has been made':'لم يتم الدفع'}}</span></label>
                                    <input type="radio" value="1" id="radio_2" name="is_paid" required/>
                                    <label for="radio_2" class="radio"><span>{{app()->getLocale() == 'en'?'The payment was made :cash':'تم الدفع :كاش'}}</span></label>
                                    <input   type="radio" value="2" id="radio_3" name="is_paid" required />
                                    <label for="radio_3" class="radio"><span>{{app()->getLocale() =='en' ?'he payment was made : visa':'تم الدفع :فيزا'}}</span></label>

                </div>
            @elseif($order->is_paid == 1)
                <div class="question-answer">
                    <input   type="radio" value="0" id="radio_1" name="is_paid" required />
                    <label for="radio_1" class="radio"><span>{{app()->getLocale() =='en' ?'No payment has been made':'لم يتم الدفع'}}</span></label>
                    <input type="radio" checked="checked" value="1" id="radio_2" name="is_paid" required/>
                    <label for="radio_2" class="radio"><span>{{app()->getLocale() == 'en'?'The payment was made : cash':'تم الدفع : كاش'}}</span></label>
                    <input   type="radio" value="2" id="radio_3" name="is_paid" required />
                    <label for="radio_3" class="radio"><span>{{app()->getLocale() =='en' ?'he payment was made : visa':'تم الدفع :فيزا'}}</span></label>
                </div>
                @elseif($order->is_paid == 2)
                <div class="question-answer">
                    <input   type="radio" value="0" id="radio_1" name="is_paid" required />
                    <label for="radio_1" class="radio"><span>{{app()->getLocale() =='en' ?'No payment has been made':'لم يتم الدفع'}}</span></label>
                    <input type="radio"  value="1" id="radio_2" name="is_paid" required/>
                    <label for="radio_2" class="radio"><span>{{app()->getLocale() == 'en'?'The payment was made : cash':'تم الدفع : كاش'}}</span></label>
                    <input   type="radio" checked="checked" value="2" id="radio_3" name="is_paid" required />
                    <label for="radio_3" class="radio"><span>{{app()->getLocale() =='en' ?'he payment was made : visa':'تم الدفع :فيزا'}}</span></label>
                </div>
                @endif

        </div>

        <div class="item">
            <p>@lang('messages.The_cost_of_the_service') <span class="required">*</span></p>
            <input type="number" name="price"  placeholder="@lang('messages.The_cost_of_the_service')" value="{{$order->price}}"/>
            @if ($errors->has('price'))
                <span class="help-block">
                                               <strong style="color: red;">{{ $errors->first('price') }}</strong>
                                            </span>
            @endif
        </div>

        <div class="item">
            <p>@lang('messages.material_cost') <span class="required">*</span></p>
            <input  type="number" name="material_cost"  placeholder="@lang('messages.material_cost')" value="{{$order->material_cost}}"/>
            @if ($errors->has('material_cost'))
                <span class="help-block">
                                               <strong style="color: red;">{{ $errors->first('material_cost') }}</strong>
                                            </span>
            @endif
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
            <button  class="btn btn-primary button">@lang('messages.save')</button>
        </div>
    </form>
</div>
<script
    src="https://code.jquery.com/jquery-3.5.1.min.js"
    integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
    crossorigin="anonymous"></script>

</body>
</html>
