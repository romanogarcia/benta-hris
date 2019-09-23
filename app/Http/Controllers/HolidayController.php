<?php

namespace App\Http\Controllers;

use App\Holiday;
use Illuminate\Http\Request;
use DB;
use DataTables;
use Auth;

class HolidayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if(date('m-d') == '01-01') {
            Holiday::truncate();
        }
        $dbholiday = Holiday::orderBy('id', 'desc')->paginate(10);
        $isempty = !empty($dbholiday->toArray()['data']) ? false:true;
        $year = date('Y',strtotime(date("Y-m-d"). " - 365 day"));
        if($isempty) {
        $holidays = json_decode(file_get_contents("https://holidayapi.com/v1/holidays?pretty&key=b5984110-9ac9-46ac-be36-22562b88a07a&country=PH&year=$year"),true);
        foreach($holidays['holidays'] as $h)
        {
            $date = explode('-',$h['date']);
            DB::table('holidays')->insert([
                'name' => $h['name'],
                'description' => $h['name'],
                'type' => $h['public']==true?'regular':'special',
                'holiday_date' => date('Y').$date[1].$date[2]
            ]);
        }
        }
        $dbholiday = Holiday::orderBy('id', 'desc')->paginate(10);
        return view('settings.holiday.index',['holidays'=>$dbholiday]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        return view('settings.holiday.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'holiday' => 'required',
            'description' => 'required',
            'type' => 'required',
            'date' => 'required'
        ]);

        $holiday = new Holiday;
        $holiday->name = $request->holiday;
        $holiday->description = $request->description;
        $holiday->type = $request->type;
        $holiday->holiday_date = date('Y-m-d', strtotime(str_replace("-","/",$request->date)));
        $holiday->save();

        return redirect('holidays')->with('success',"Holiday is successfully created.");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Holiday  $holiday
     * @return \Illuminate\Http\Response
     */
    public function show(Holiday $holiday)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Holiday  $holiday
     * @return \Illuminate\Http\Response
     */
    public function edit(Holiday $holiday)
    {
        $holiday = Holiday::findOrFail($holiday->id);
        return view('settings.holiday.edit',['holiday'=>$holiday]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Holiday  $holiday
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Holiday $holiday)
    {
        $this->validate($request, [
            'holiday' => 'required',
            'description' => 'required',
            'type' => 'required',
            'date' => 'required'
        ]);
        $status = $request->status == 'on' ? true:false;
        $holiday = Holiday::find($holiday->id);
        $holiday->name = $request->holiday;
        $holiday->description = $request->description;
        $holiday->type = $request->type;
        $holiday->holiday_date = date('Y-m-d', strtotime(str_replace("-","/",$request->date)));
        $holiday->status = $status;
        $holiday->save();
        return redirect(route('holidays.index'))->with('success','Holiday is successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Holiday  $holiday
     * @return \Illuminate\Http\Response
     */
    public function destroy(Holiday $holiday)
    {
        if(Holiday::destroy($holiday->id)){
            return redirect('holidays')->with('success','Holiday deleted successfully!');
        } else {
            return redirect()->back()->with('error','Request Failed!');
        }
    }

    public function search(){

    }

    public function search_filter(Request $request){
        //search filter query and result
        $to_select = array(
            'name',
            'description',
            'type',
            'status',
            'id as r_id',
        );

        $data = Holiday::get($to_select);
    
        $data_tables = Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('id', function($row){
				if((check_permission(Auth::user()->Employee->department_id,"Holidays","full")) || (check_permission(Auth::user()->Employee->department_id,"Holidays","Edit"))){
                $url_edit = route('holidays.edit', [$row->r_id]);
                $response = '<a href="'.$url_edit.'">'.$row->r_id.'</a>';
                return $response;
				}
				else{
				return $row->r_id;
				}
            })
            ->addIndexColumn()
            ->addColumn('holiday_name', function($row){
                return ucfirst($row->name);
            })
            ->addIndexColumn()
            ->addColumn('description', function($row){
                return ucfirst($row->description);
			})
			->addIndexColumn()
            ->addColumn('type', function($row){
                return ucfirst($row->type);
			})
			->addIndexColumn()
            ->addColumn('active', function($row){
				
				if($row->status){
					$status = '<div class="badge badge-success badge-pill">&nbsp;&nbsp;YES&nbsp;&nbsp;</div>';
				}else{
					$status = '<div class="badge badge-danger badge-pill">&nbsp;&nbsp;NO&nbsp;&nbsp;</div>';
				}

                return $status;
			})
			->addIndexColumn()
            ->addColumn('action', function($row){
				$url_edit = route('holidays.edit', [$row->r_id]);
				$response="";
				if((check_permission(Auth::user()->Employee->department_id,"Holidays","full")) || (check_permission(Auth::user()->Employee->department_id,"Holidays","Edit"))){
				$response = '<button  type="button" class="btn btn-outline-secondary btn-rounded btn-icon btn-sm" onclick="window.location.href = \''.$url_edit.'\';"><i style="margin-left: -6px;" class="mdi mdi-lead-pencil"></i></button>';
				}
				if((check_permission(Auth::user()->Employee->department_id,"Holidays","full")) || (check_permission(Auth::user()->Employee->department_id,"Holidays","Delete"))){
                $response .= ' <button type="button" data-toggle="modal" data-id="'.$row->r_id.'" data-target="#DeleteModal" class="btn btn-outline-secondary btn-rounded btn-icon btn-sm btn-delete_row ml-3 "><i style="margin-left: -7px;" class="mdi mdi-delete"></i></button>';
				}
                return $response;
			})
            ->rawColumns(['id','holiday_name','description','type','active', 'action'])
            ->make(true);
            
        return $data_tables;
    }
}
