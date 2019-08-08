<?php 

namespace App\Http\Controllers\admin;
use App\Http\Controllers\controller;
use App\department;
use App\country;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class departmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    



    public function index(Request $request)
    {
        return view("admin.deps.index");
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view("admin.deps.createdepartment",['title'=>trans("admin.add_new_department")]);
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
               if(request()->hasFile("icon"))
        {
            //if record is exist with info delete this image from storage disk
            !empty(dep()[0]->icon)? Storage::delete(dep()[0]->icon):"";
            $data['icon'] =request()->file("icon")->store("dep");

        }

        department::create($data);
        session()->flash("success",trans("admin.department_added"));
        return redirect(adminUrl("departments"));
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
        $department = department::findOrFail($id);
        return view("admin.deps.editdepartment",[
            "title"=>trans("department.edit"),
            "data"=>$department,


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

        department::where("id",$id)->update($data);

        session()->flash("success",trans("admin.record_updated"));
        return redirect(adminUrl("departments"));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
     department::find($id)->delete();
     session()->flash("success",trans("admin.record_deleted"));
     return redirect(adminUrl("department"));
 }

 public function destroyAll(){


    if(is_array(request("item")))
    {
       department::destroy(request("item"));
   }   
   else {

     department::find(request("item"))->delete();
 } 
 session()->flash("success",trans("admin.record_deleted"));

 return redirect(adminUrl("departments"));
}


public function validation(){

  return [
    "dep_name_ar"=>"required",
    "dep_name_en"=>"required",
    "icon"=>"sometimes|nullable",
    "description"=>"required|",
    "keywords"=>"required|",
    "parent_id"=>"sometimes||numeric",






];
}
public function aliases (){
    return[

        "dep_name_ar"     =>trans("admin.dep_name_ar"),
        "dep_name_en"    =>trans("admin.dep_name_en"),
        "icon"=>trans("admin.dep_icon"),
        "description"=>trans("admin.dep_description"),
        "keywords"=>trans("admin.dep_keywords"),
        "parent_id"=>trans("admin.parent_id"),


    ];

}

public function countrySelect(){
    $countries = country::all();
    $countrySelected = [];
    foreach($countries as $country)
    {
       $countrySelected += [
        "$country->id"  => "$country->countryAr",
    ];
}

return $countrySelected;
}
}
