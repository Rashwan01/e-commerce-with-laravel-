<?php 

namespace App\Http\Controllers\admin;
use App\Http\Controllers\controller;
use App\manufact;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class manufacturingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    


    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = manufact::latest()->get();
            return Datatables::of($data)
            ->addColumn('edit',"admin/manufactDataTable/edit")
            ->addColumn("delete","admin/manufactDataTable/delete")
            ->addColumn("box","admin/manufactDataTable/inputbox")
            ->rawColumns([
                "edit",
                "delete",
                "box"
                
            ])
            ->make(true);
        }

        return view('admin.dataTablemanufact',["title"=>trans("admin.manufact_control")]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()

    {
        return view("admin.manufactDataTable.createmanufact",['title'=>trans("manufact.add_new_manufact")]);
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
            $data['icon'] =request()->file("icon")->store("manufact");

        }

        manufact::create($data);
        session()->flash("success",trans("admin.record_add"));
        return redirect(adminUrl("manufacts"));
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
        $manufact = manufact::findOrFail($id);
        return view("admin.manufactDataTable.editmanufact",[
            "title"=>trans("manufact.edit"),
            "data"=>$manufact

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

        if(request()->hasFile("icon"))
        {
            $oldLogo = manufact::find($id);
            //if record is exist with info delete this image from storage disk
            !empty($oldLogo->icon)? Storage::delete($oldLogo->icon):"";
            $data['icon'] =request()->file("icon")->store("manufact");

        }
        manufact::where("id",$id)->update($data);

        session()->flash("success",trans("admin.record_updated"));
        return redirect(adminUrl("manufacts"));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       manufact::find($id)->delete();
       session()->flash("success",trans("admin.record_deleted"));
       return redirect(adminUrl("manufacts"));
   }

   public function destroyAll(){
    /*

    ** request("item") variable has all id of deletd Record
    ** so dates will go to search   every id and get its information in array
    ** to Access logo we need to looping in every array and get logo object 
    ** then delete it from countries storage
*/
    $dates = manufact::whereIn("id",request("item"))->get();
    foreach ($dates as $data) {
        Storage::delete($data->logo);
    }

/*
**if request item is in array with id ,so take it and destroy all
**else find this id and delete it
*/
if(is_array(request("item")))
{
 manufact::destroy(request("item"));
}   
else {

   manufact::find(request("item"))->delete();
} 
session()->flash("success",trans("admin.record_deleted"));

return redirect(adminUrl("manufacts"));
}


public function validation(){

  return [
    "name_ar"=>"required",
    "name_en"=>"required",
    "facebook"=>"sometimes|required|url",
    "twitter"=>"sometimes|required|url",
    "website"=>"sometimes|required|url",
    "contact_name"=>"sometimes|required|string",
    "email"=>"sometimes|required|email",
    "mobail"=>"sometimes|required|numeric",
    "lat"=>"sometimes|required|url",
    "lng"=>"sometimes|required|url",
    "icon"=>"sometimes|required|image|mimes:jpg,png,jpeg",
    

];
}
public function aliases (){
    return[

        "name_ar"     =>trans("admin.manufactAr"),
        "name_en"    =>trans("admin.manufactEn"),
        "facebook"    =>trans("admin.manufactenFb"),
        "twitter"    =>trans("admin.manufactenTw"),
        "website"    =>trans("admin.manufactenWs"),
        "contact_name"    =>trans("admin.manufacten_contact_name"),
        "email"=>trans("admin.manufacten_email"),
        "mobail"=>trans("admin.manufacten_mobail"),
        "lat"    =>trans("admin.manufacten_lat"),
        "lng"    =>trans("admin.manufacten_lng"),
        "icon"    =>trans("admin.manufacten_icon"),




    ];

}
}
