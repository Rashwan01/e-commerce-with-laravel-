<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class molls extends Model
{
	protected $fillable=[

		"name_ar",
		"name_en",
		"facebook",
		"twitter",
		"website",
		"contact_name",
		"lat",
		"lng",
		"icon",
		"email",
		"mobail"
	];

}
