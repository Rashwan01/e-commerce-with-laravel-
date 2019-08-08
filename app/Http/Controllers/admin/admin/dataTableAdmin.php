<?php 

namespace App\Http\Controllers\admin\admin;
use App\Http\Controllers\controller;
use App\admin;
use Illuminate\Http\Request;
use DataTables;

class dataTableAdmin extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    


    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = admin::latest()->get();
            return Datatables::of($data)
            ->addColumn('edit',"admin/adminDataTable/edit")
            ->addColumn("delete","admin/adminDataTable/delete")
            ->addColumn("box","admin/adminDataTable/inputbox")
            ->rawColumns([
                "edit",
                "delete",
                "box"
                
            ])
            ->make(true);
        }

        return view('admin.dataTableAdmin',["title"=>trans("admin.admin_control")]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("admin.adminDataTable.createAdmin",['title'=>trans("admin.add_new_user")]);
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
        admin::create($data);
        session()->flash("record_add",trans("admin.record_add"));
        return redirect(adminUrl("admin"));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = admin::findOrFail($id);
        return view("admin.adminDataTable.editAdmin",[
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
        $data = $this->validate(request(),$this->validation(),[],$this->aliases());
        admin::where("id",$id)->update($dataStored);
        session()->flash("success",trans("admin.record_updated"));
        return redirect(adminUrl("admin"));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       admin::find($id)->delete();
       session()->flash("success",trans("admin.record_deleted"));
       return redirect(adminUrl("admin"));
   }

   public function destroyAll(){

    if(is_array(request("item")))
    {
        admin::destroy(request("item"));
    }   
    else {
       admin::find(request("item"))->delete();
   } 
   session()->flash("success",trans("admin.record_deleted"));

   return redirect(adminUrl("admin"));
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
