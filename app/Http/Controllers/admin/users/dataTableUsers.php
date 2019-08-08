<?php 

namespace App\Http\Controllers\admin\users;
use App\Http\Controllers\controller;
use App\User;
use Illuminate\Http\Request;
use DataTables;
class dataTableUsers extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
     if (request()->ajax()) {
        $data = User::latest()->get();
        return Datatables::of($data)
        ->addColumn('edit',"admin/userDataTable/edit")
        ->addColumn("delete","admin/userDataTable/delete")
        ->addColumn("box","admin/userDataTable/inputbox")
        ->addColumn("level","admin/userDataTable/level")
        ->rawColumns([
            "edit",
            "delete",
            "box",
            "level",

        ])
        ->make(true);
    }
    return view('admin.dataTableUsers',["title"=>trans("admin.admin_control")]);

}
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("admin.userDataTable.createUser",['title'=>trans("admin.add_new_user")]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $this->validate(request(),$this->validation(),[],$this->aliases());
        $data['password'] = bcrypt(request("password"));
        User::create($data);
 $level = request("level");
        session()->flash("success",trans("admin.level_$level"));
        return redirect(adminUrl("users"));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($level)
    {

      if (request()->ajax()) {
        // check if user want to see all users or user,vendor,company
         $data=  $level === "all"?User::all(): User::where("level",$level)->get();

         return Datatables::of($data)
         ->addColumn('edit',"admin/userDataTable/edit")
         ->addColumn("delete","admin/userDataTable/delete")
         ->addColumn("box","admin/userDataTable/inputbox")
         ->addColumn("level","admin/userDataTable/level")
         ->rawColumns([
            "edit",
            "delete",
            "box",
            "level",

        ])
         ->make(true);
     }
     return view('admin.dataTableUsers',["title"=>trans("admin.admin_control")]);

 }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view("admin.userDataTable.editUser",[
            "title"=>trans("admin.edit"),
            "data"=>$user
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $data = $this->validate($request,$this->validation(),[],$this->aliases());
        User::where("id",$id)->update($data);
        session()->flash("success",trans("admin.record_updated"));
        return redirect(adminUrl("users"));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       User::find($id)->delete();
       session()->flash("success",trans("admin.record_deleted"));
       return redirect(adminUrl("users"));
   }

   public function destroyAll()
   {

    if(is_array(request("item")))
    {
        User::destroy(request("item"));
    }   
    else {
     User::find(request("item"))->delete();
 } 
 session()->flash("success",trans("admin.record_deleted"));

 return redirect(adminUrl("users"));
}

public function validation(){

  return [
    "name"=>"required",
    "email"=>"required|email|unique:users",
    "level"=>"in:user,vendor,company",
    "password"=>"required",

];
}
public function aliases (){
    return[

        "name"     =>trans("admin.name"),
        "email"    =>trans("admin.email"),
        "password" =>trans("admin.password"),
        "level"=>trans("admin.level"),

    ];

}


}
