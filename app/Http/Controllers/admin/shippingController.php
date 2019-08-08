<?php 

namespace App\Http\Controllers\admin;
use App\Http\Controllers\controller;
use App\shipping;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class shippingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    


    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = shipping::latest()->get();
            return Datatables::of($data)
            ->addColumn('edit',"admin/shippingDataTable/edit")
            ->addColumn("delete","admin/shippingDataTable/delete")
            ->addColumn("box","admin/shippingDataTable/inputbox")
            ->rawColumns([
                "edit",
                "delete",
                "box"
                
            ])
            ->make(true);
        }

        return view('admin.dataTableshipping',["title"=>trans("admin.shipping_control")]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()

    {
        return view("admin.shippingDataTable.createshipping",['title'=>trans("shipping.add_new_shipping")]);
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
            $data['icon'] =request()->file("icon")->store("shipping");

        }

        shipping::create($data);
        session()->flash("success",trans("admin.record_add"));
        return redirect(adminUrl("shippings"));
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
        $shipping = shipping::findOrFail($id);
        return view("admin.shippingDataTable.editshipping",[
            "title"=>trans("shipping.edit"),
            "data"=>$shipping

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
            $oldLogo = shipping::find($id);
            //if record is exist with info delete this image from storage disk
            !empty($oldLogo->icon)? Storage::delete($oldLogo->icon):"";
            $data['icon'] =request()->file("icon")->store("shipping");

        }
        shipping::where("id",$id)->update($data);

        session()->flash("success",trans("admin.record_updated"));
        return redirect(adminUrl("shippings"));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       shipping::find($id)->delete();
       session()->flash("success",trans("admin.record_deleted"));
       return redirect(adminUrl("shippings"));
   }

   public function destroyAll(){
    /*

    ** request("item") variable has all id of deletd Record
    ** so dates will go to search   every id and get its information in array
    ** to Access logo we need to looping in every array and get logo object 
    ** then delete it from countries storage
*/
    $dates = shipping::whereIn("id",request("item"))->get();
    foreach ($dates as $data) {
        Storage::delete($data->logo);
    }

/*
**if request item is in array with id ,so take it and destroy all
**else find this id and delete it
*/
if(is_array(request("item")))
{
 shipping::destroy(request("item"));
}   
else {

   shipping::find(request("item"))->delete();
} 
session()->flash("success",trans("admin.record_deleted"));

return redirect(adminUrl("shippings"));
}


public function validation(){

  return [
    "name_ar"=>"required",
    "name_en"=>"required",
    "user_id"=>"sometimes|required|numeric",
    "lat"=>"sometimes|required|url",
    "lng"=>"sometimes|required|url",
    "icon"=>"sometimes|required|image|mimes:jpg,png,jpeg",
    

];
}
public function aliases (){
    return[

        "name_ar"     =>trans("admin.shippingAr"),
        "name_en"    =>trans("admin.shippingEn"),
        "user_id"    =>trans("admin.user_id"),
        "lat"    =>trans("admin.shipping_lat"),
        "lng"    =>trans("admin.shipping_lng"),
        "icon"    =>trans("admin.shipping_icon"),




    ];

}
}
