<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use App\setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class settingsController extends Controller
{
    //

    public function index(){

        return view("admin.settings",["title"=>trans("admin.website_option")]);


    }
    public function settings(){

    	$aliases = [
            "logo"=>trans("admin.logo"),
            "logo1"=>trans("admin.logo1")
        ];
        $data =$this->validate(request(),[
            "webNameAr"=>"",
            "webNameEn"=>"",
            "website_email"=>"",
            "logo"=>"image|mimes:jpeg,jpg,png",
            "logo1"=>"image|mimes:jpeg,jpg,png",
            'description'=>"",
            "lang"=>"required",
            "status"=>"",
            "msg_maintanience"=>"",

        ],[],$aliases);

        if(request()->hasFile("logo"))
        {
            //if record is exist with info delete this image from storage disk
            !empty(settings()[0]->logo)? Storage::delete(settings()[0]->logo):"";
            $data['logo'] =request()->file("logo")->store("settings");

        }
        if(request()->hasFile("logo1"))

        {
            !empty(settings()[0]->logo1)? Storage::delete(settings()[0]->logo1):"";

            $data['logo1'] =request()->file("logo1")->store("settings");

        }

        setting::latest()->first()->update($data);
        session()->flash("success",trans("admin.option_saved"));
        return redirect(adminUrl("website/settings"));

    }
}
