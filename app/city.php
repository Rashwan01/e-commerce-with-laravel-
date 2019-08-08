<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class city extends Model
{
	protected $fillable = [

		"cityAr","cityEn","country_id",
	];

	public function country(){

return $this->belongsTo('App\country', 'country_id', 'id');
	}
}
