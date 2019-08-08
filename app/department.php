<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class department extends Model
{
	protected $fillable = [


		"dep_name_ar",
		"dep_name_en",
		"icon",
		"description",
		"keywords",
		"parent_id"
	];
	public function parent(){

		return $this->belongsTo('App\department', 'parent_id', 'id');
	}
}
