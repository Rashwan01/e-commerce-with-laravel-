<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class shipping extends Model
{
	protected $fillable=[

		"name_ar",
		"name_en",
		"user_id",
		"lat",
		"lng",
		"icon",
	];
}
