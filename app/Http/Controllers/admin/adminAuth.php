<?php

namespace App\Http\Controllers\admin;
use  App\Http\Controllers\Controller;
use App\admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use  App\Mail\restPassword;
use Carbon\Carbon;

class adminAuth extends Controller
{
	public function index(){

		return view("admin.login");
	}
	public function login(){
		if(auth()->guard("admin")->attempt(['email'=>request("email"),"password"=>request("password")])){
			return view("admin.home",["title"=>trans("admin.dashboard")]);
		}
		return redirect("/admin/login")->withErrors(["msg"=>trans("admin.LOGIN_FAILS")]);
	}
	public function forgetPassword(){
		return view("admin.forgetPassword");
	}

	public function retrievePassword(){
		$this->validate(request(),[
			'email'=>"required"
		]);
		$admin = admin::where("email",request("email"))->first();
		if(!empty($admin)){
			$token = app(\Illuminate\Auth\Passwords\PasswordBroker::class)->createToken($admin);
			DB::table("password_resets")->insert([
				"email"=>$admin->email,
				"token" =>$token,
				"created_at"=> Carbon::now(),
			]);	

			\Mail::to($admin->email)->send(new restPassword([
				"data"=> $admin,
				"token"=>$token
			]));
			return view("admin.checkYourBox");
		}
		return redirect(adminUrl("forget/password"))->withErrors(["fails"=>trans("admin.EMAIL_NOT_EXIST")]);
	}


	public function retrieveNewPassword($token){
		$check = db::table("password_Resets")->where("token",$token)->where("created_at",">",Carbon::now()->subHour(2))->first();

		if(!empty($check)){
			return view("admin.retrieveNewPassword",compact("check"));
		}
	}
	public function vcsNewPassword($token){
		//validate
		$this->validate(request(),[
			"email"=>"required",
			"password" =>"required|min:3|confirmed",
		]);
		//check if data is coorected
		$check = DB::table("password_Resets")->where("token",$token)->where("created_at",">",Carbon::now()->subHour(2))->first();
		//if it is not empty
		if(!empty($check)){
			$adamin = admin::where("email",$check->email)->update(["email" =>$check->email,"password"=>bcrypt(request("password"))]);
			DB::table("password_Resets")->where("email",request("email"))->delete();
			auth()->guard("admin")->attempt(["email"=>request("email"),"password"=>request("password")]);
			return redirect(adminUrl("home"));
		}
	}
	public function logout(){
		auth()->guard("admin")->logout();
		return redirect("admin/login");
		
	}
}
