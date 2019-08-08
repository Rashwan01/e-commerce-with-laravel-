<?php 

namespace App\Http\Controllers\admin;
use App\Http\Controllers\controller;
use App\trademark;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class trademarkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    


    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = trademark::latest()->get();
            return Datatables::of($data)
            ->addColumn('edit',"admin/trademarkDataTable/edit")
            ->addColumn("delete","admin/trademarkDataTable/delete")
            ->addColumn("box","admin/trademarkDataTable/inputbox")
            ->rawColumns([
                "edit",
                "delete",
                "box"
                
            ])
            ->make(true);
        }

        return view('admin.dataTabletrademark',["title"=>trans("admin.trademark_control")]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()

    {
        return view("admin.trademarkDataTable.createtrademark",['title'=>trans("trademark.add_new_trademark")]);
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
        if(request()->hasFile("logo"))
        {
            //if record is exist with info delete this image from storage disk
            $data['logo'] =request()->file("logo")->store("countries");

        }

        trademark::create($data);
        session()->flash("success",trans("admin.record_add"));
        return redirect(adminUrl("trademarks"));
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
        $trademark = trademark::findOrFail($id);
        return view("admin.trademarkDataTable.edittrademark",[
            "title"=>trans("trademark.edit"),
            "data"=>$trademark

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

        if(request()->hasFile("logo"))
        {
            $oldLogo = trademark::find($id);
            //if record is exist with info delete this image from storage disk
            !empty($oldLogo->logo)? Storage::delete($oldLogo->logo):"";
            $data['logo'] =request()->file("logo")->store("trademark");

        }
        trademark::where("id",$id)->update($data);

        session()->flash("success",trans("admin.record_updated"));
        return redirect(adminUrl("trademarks"));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       trademark::find($id)->delete();
       session()->flash("success",trans("admin.record_deleted"));
       return redirect(adminUrl("trademarks"));
   }

   public function destroyAll(){
    /*

    ** request("item") variable has all id of deletd Record
    ** so dates will go to search   every id and get its information in array
    ** to Access logo we need to looping in every array and get logo object 
    ** then delete it from countries storage
*/
    $dates = trademark::whereIn("id",request("item"))->get();
    foreach ($dates as $data) {
        Storage::delete($data->logo);
    }

/*
**if request item is in array with id ,so take it and destroy all
**else find this id and delete it
*/
if(is_array(request("item")))
{
 trademark::destroy(request("item"));
}   
else {

   trademark::find(request("item"))->delete();
} 
session()->flash("success",trans("admin.record_deleted"));

return redirect(adminUrl("trademarks"));
}


public function validation(){

  return [
    "name_ar"=>"required",
    "name_en"=>"required",
    "logo"=>"sometimes|required|image|mimes:jpg,png,jpeg",
    

];
}
public function aliases (){
    return[

        "name_ar"     =>trans("admin.trademarkAr"),
        "name_en"    =>trans("admin.trademarken"),
        "logo"=>trans("admin.logo"),


    ];

}
}
