<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model {

	protected $table = 'settings';
	public $timestamps = true;
	protected $fillable = array('phone', 'whatsapp', 'term', 'condition', 'tax', 'terms_ar', 'condition_ar','search_range','shift_range','about_us','about_us_ar','image',
        'count_of_order_in_period','latitude','longitude','company_name','route_name','city_name','country_name','accept_tax','accept_tax_en',
        'company_name_en','city_name_en','route_name_en','company_name_en','tax_for_completed_order','cancel_order','tax_for_completed_order_active');

}
