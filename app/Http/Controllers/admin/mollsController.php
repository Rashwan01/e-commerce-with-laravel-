<?php 

namespace App\Http\Controllers\admin;
use App\Http\Controllers\controller;
use App\molls;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class mollsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    


    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = molls::latest()->get();
            return Datatables::of($data)
            ->addColumn('edit',"admin/mollsDataTable/edit")
            ->addColumn("delete","admin/mollsDataTable/delete")
            ->addColumn("box","admin/mollsDataTable/inputbox")
            ->rawColumns([
                "edit",
                "delete",
                "box"
                
            ])
            ->make(true);
        }

        return view('admin.dataTablemolls',["title"=>trans("admin.molls_control")]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()

    {
        return view("admin.mollsDataTable.createmolls",['title'=>trans("molls.add_new_molls")]);
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
            $data['icon'] =request()->file("icon")->store("molls");

        }

        molls::create($data);
        session()->flash("success",trans("admin.record_add"));
        return redirect(adminUrl("molls"));
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
        $molls = molls::findOrFail($id);
        return view("admin.mollsDataTable.editmolls",[
            "title"=>trans("molls.edit"),
            "data"=>$molls

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
            $oldLogo = molls::find($id);
            //if record is exist with info delete this image from storage disk
            !empty($oldLogo->icon)? Storage::delete($oldLogo->icon):"";
            $data['icon'] =request()->file("icon")->store("molls");

        }
        molls::where("id",$id)->update($data);

        session()->flash("success",trans("admin.record_updated"));
        return redirect(adminUrl("molls"));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       molls::find($id)->delete();
       session()->flash("success",trans("admin.record_deleted"));
       return redirect(adminUrl("mollss"));
   }

   public function destroyAll(){
    /*

    ** request("item") variable has all id of deletd Record
    ** so dates will go to search   every id and get its information in array
    ** to Access logo we need to looping in every array and get logo object 
    ** then delete it from countries storage
*/
    $dates = molls::whereIn("id",request("item"))->get();
    foreach ($dates as $data) {
        Storage::delete($data->logo);
    }

/*
**if request item is in array with id ,so take it and destroy all
**else find this id and delete it
*/
if(is_array(request("item")))
{
 molls::destroy(request("item"));
}   
else {

   molls::find(request("item"))->delete();
} 
session()->flash("success",trans("admin.record_deleted"));

return redirect(adminUrl("mollss"));
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

        "name_ar"     =>trans("admin.mollsAr"),
        "name_en"    =>trans("admin.mollsEn"),
        "facebook"    =>trans("admin.mollsenFb"),
        "twitter"    =>trans("admin.mollsenTw"),
        "website"    =>trans("admin.mollsenWs"),
        "contact_name"    =>trans("admin.mollsen_contact_name"),
        "email"=>trans("admin.mollsen_email"),
        "mobail"=>trans("admin.mollsen_mobail"),
        "lat"    =>trans("admin.mollsen_lat"),
        "lng"    =>trans("admin.mollsen_lng"),
        "icon"    =>trans("admin.mollsen_icon"),




    ];

}
}
