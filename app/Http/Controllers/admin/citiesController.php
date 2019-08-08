<?php 

namespace App\Http\Controllers\admin;
use App\Http\Controllers\controller;
use App\city;
use App\country;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class citiesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    


    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = city::latest()->get();
            return Datatables::of($data)
            ->addColumn('edit',"admin/cityDataTable/edit")
            ->addColumn("delete","admin/cityDataTable/delete")
            ->addColumn("box","admin/cityDataTable/inputbox")
            ->rawColumns([
                "edit",
                "delete",
                "box"
                
            ])
            ->make(true);
        }

        return view('admin.dataTablecity',["title"=>trans("admin.city_control")]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view("admin.cityDataTable.createcity",['title'=>trans("admin.add_new_city"),"countrySelected"=>$this->countrySelect()]);
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

        city::create($data);
        session()->flash("success",trans("admin.city_added"));
        return redirect(adminUrl("cities"));
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
        $city = city::findOrFail($id);
        return view("admin.cityDataTable.editcity",[
            "title"=>trans("city.edit"),
            "data"=>$city,
            "countrySelected"=>$this->countrySelect(),

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

        city::where("id",$id)->update($data);

        session()->flash("success",trans("admin.record_updated"));
        return redirect(adminUrl("cities"));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
     city::find($id)->delete();
     session()->flash("success",trans("admin.record_deleted"));
     return redirect(adminUrl("city"));
 }

 public function destroyAll(){


    if(is_array(request("item")))
    {
       city::destroy(request("item"));
   }   
   else {

     city::find(request("item"))->delete();
 } 
 session()->flash("success",trans("admin.record_deleted"));

 return redirect(adminUrl("cities"));
}


public function validation(){

  return [
    "cityAr"=>"required",
    "cityEn"=>"required",
    "country_id"=>"required|numeric"

];
}
public function aliases (){
    return[

        "cityAr"     =>trans("admin.cityAr"),
        "cityEn"    =>trans("admin.cityen"),
        "country_id"=>trans("admin.country_id")


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
