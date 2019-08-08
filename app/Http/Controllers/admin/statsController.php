<?php 

namespace App\Http\Controllers\admin;
use App\Http\Controllers\controller;
use App\city;
use App\country;
use App\stats;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class statsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    


    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = stats::latest()->get();
            return Datatables::of($data)
            ->addColumn('edit',"admin/statsDataTable/edit")
            ->addColumn("delete","admin/statsDataTable/delete")
            ->addColumn("box","admin/statsDataTable/inputbox")
            ->rawColumns([
                "edit",
                "delete",
                "box"
                
            ])
            ->make(true);
        }

        return view('admin.dataTableStats',["title"=>trans("admin.stats_control")]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(request()->ajax())
        {
            $id =request("country_id");
            $cities= city::where("country_id",$id)->get();
            $Response = [];
            foreach ($cities as $city) {
                $Response += [
                    "$city->id"=>"$city->cityAr" 

                ];


            }
            return \Form::select("city_id"
                ,$Response,null,['class'=>'form-control']) ;
        }

        return view("admin.statsDataTable.createStats",['title'=>trans("admin.add_new_stats"),"countrySelected"=>$this->countrySelect(),"citySelected"=>$this->citySelect()]);
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

        stats::create($data);
        session()->flash("success",trans("admin.stats_added"));
        return redirect(adminUrl("stats"));
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
        $stats = stats::findOrFail($id);
        return view("admin.statsDataTable.editstats",[
            "title"=>trans("stats.edit"),
            "data"=>$stats,
            "countrySelected"=>$this->countrySelect(),
            "citySelected"=>$this->citySelect()

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

        stats::where("id",$id)->update($data);

        session()->flash("success",trans("admin.record_updated"));
        return redirect(adminUrl("stats"));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       stats::find($id)->delete();
       session()->flash("success",trans("admin.record_deleted"));
       return redirect(adminUrl("stats"));
   }

   public function destroyAll(){


    if(is_array(request("item")))
    {
     stats::destroy(request("item"));
 }   
 else {

   stats::find(request("item"))->delete();
} 
session()->flash("success",trans("admin.record_deleted"));

return redirect(adminUrl("cities"));
}


public function validation(){

  return [
    "statsAr"=>"required",
    "statsEn"=>"required",
    "country_id"=>"required|numeric",
    "city_id"=>"required|numeric",

];
}
public function aliases (){
    return[

        "statsAr"     =>trans("admin.statsAr"),
        "statsEn"    =>trans("admin.statsEn"),
        "country_id"=>trans("admin.country_id"),
        "city_id"=>trans("admin.city_id"),


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



public function citySelect(){
    $cities = city::all();
    $citySelected = [];
    foreach($cities as $city)
    {
     $citySelected += [
        "$city->id"  => "$city->cityAr",
    ];
}

return $citySelected;
}
}
