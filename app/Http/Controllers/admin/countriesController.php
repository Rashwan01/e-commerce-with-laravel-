<?php 

namespace App\Http\Controllers\admin;
use App\Http\Controllers\controller;
use App\country;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class countriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    


    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = country::latest()->get();
            return Datatables::of($data)
            ->addColumn('edit',"admin/countryDataTable/edit")
            ->addColumn("delete","admin/countryDataTable/delete")
            ->addColumn("box","admin/countryDataTable/inputbox")
            ->rawColumns([
                "edit",
                "delete",
                "box"
                
            ])
            ->make(true);
        }

        return view('admin.dataTableCountry',["title"=>trans("admin.country_control")]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()

    {
        return view("admin.countryDataTable.createCountry",['title'=>trans("country.add_new_country")]);
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

        country::create($data);
        session()->flash("success",trans("admin.record_add"));
        return redirect(adminUrl("countries"));
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
        $country = country::findOrFail($id);
        return view("admin.countryDataTable.editcountry",[
            "title"=>trans("country.edit"),
            "data"=>$country

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
            $oldLogo = country::find($id);
            //if record is exist with info delete this image from storage disk
            !empty($oldLogo->logo)? Storage::delete($oldLogo->logo):"";
            $data['logo'] =request()->file("logo")->store("countries");

        }
        country::where("id",$id)->update($data);

        session()->flash("success",trans("admin.record_updated"));
        return redirect(adminUrl("countries"));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
     country::find($id)->delete();
     session()->flash("success",trans("admin.record_deleted"));
     return redirect(adminUrl("country"));
 }

 public function destroyAll(){
    /*

    ** request("item") variable has all id of deletd Record
    ** so dates will go to search   every id and get its information in array
    ** to Access logo we need to looping in every array and get logo object 
    ** then delete it from countries storage
*/
    $dates = country::whereIn("id",request("item"))->get();
    foreach ($dates as $data) {
        Storage::delete($data->logo);
    }

/*
**if request item is in array with id ,so take it and destroy all
**else find this id and delete it
*/
if(is_array(request("item")))
{
   country::destroy(request("item"));
}   
else {

 country::find(request("item"))->delete();
} 
session()->flash("success",trans("admin.record_deleted"));

return redirect(adminUrl("countries"));
}


public function validation(){

  return [
    "countryAr"=>"required",
    "countryEn"=>"required",
    "mob"=>"required",
    "logo"=>"required|image|mimes:jpg,png,jpeg",
    "code"=>"required",

];
}
public function aliases (){
    return[

        "countryAr"     =>trans("admin.countryAr"),
        "countryEn"    =>trans("admin.countryen"),
        "mob" =>trans("admin.mobail"),
        "logo"=>trans("admin.logo"),
        "code"=>trans("admin.code"),


    ];

}
}
