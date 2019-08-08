<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class stats extends Model
{
	protected $fillable = [
	"statsAr",
	"statsEn",
	"country_id",
	"city_id",
	];
}
