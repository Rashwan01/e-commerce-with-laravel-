<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class trademark extends Model
{
	protected $fillable = [
		"name_ar",
		"name_en",
		"logo",
	
	];
}
