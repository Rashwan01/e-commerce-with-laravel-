<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class setting extends Model
{
	protected $table ="settings"; 
	protected $fillable = [
		"webNameAr",
		"webNameEn",
		"website_email",
		"logo",
		"logo1",
		"description",
		"lang",
		"status",
		"msg_maintanience"
	];
	}
