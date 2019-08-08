<?php

use App\User;
use App\department;
use App\setting;

function settings(){

	$data = App\setting::latest()->first()->get();
	return $data;
}


function dep(){

	$data = App\department::latest()->first()->get();
	return $data;
}

function depSelect(){
	$deps = department::selectRaw('dep_name_'.session("lang").' as text')
	->selectRaw('id as id')
	->get(['id',"text"]);

	$depSelect = [];
	foreach($deps as $dep)
	{
		$depSelect += [
			"$dep->id"  => "$dep->text",
		];
	}


	return $depSelect;
}

function userSelect(){
	$User = User::where("level","company")->get();

	$userSelected = [];
	foreach($User as $user)
	{
		$userSelected += [
			"$user->id"  => "$user->name",
		];
	}


	return $userSelected;
}
function loadDep ($select = null,$dep_hide=null){
	$deps = department::selectRaw('dep_name_'.session("lang").' as text')
	->selectRaw('id as id')
	->selectRaw('parent_id as parent')
	->get(["text","id","parent"]);

	$arr = [];
	foreach ($deps as $dep) {

		$list_arr = [];
		$list_arr['icon'] =""; 
		$list_arr['children'] =[]; 
		$list_arr['li_attr'] =""; 
		$list_arr['a_attr'] =""; 
		$list_arr['icon'] =""; 

		if($select !== null and $select == $dep->id)
		{

			$list_arr['state'] = [
				"opened"=>"true",
				"selected"=>"true",

			];
		}
		// which i woulld to edit it
		if($dep_hide !== null and $dep_hide == $dep->id)
		{
			
			$list_arr['state'] = [
				"opened"=>"false",
				"selected"=>"false",
				"disabled"=>"true",
				"hidden"=>"true",

			];
		}
		$list_arr['id'] = $dep->id;
		$list_arr['parent']=$dep->parent >0?$dep->parent:"#";
		$list_arr['text']= $dep->text;
		array_push($arr,$list_arr);
	}
	return json_encode($arr,JSON_UNESCAPED_UNICODE);

}
function adminUrl($url = null){

	return url("admin/".$url);
}
/*
**lang ver 1.00
** if you have seesion lang return its value else return en as default

*/
function lang(){

	if(session()->has("lang"))
	{
		if(session("lang") == "ar")
		{
			return "ar";
		}
		else{
			return "en";
		}

	}else
	{
		$data =setting::latest()->first();
		return	$data->lang ;
	}
}
/* 
direction function 
** if there is lang check its value if en then return ltr else rtl
*/



