<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Teachers;
use App\Models\Subjects;
use App\Models\Students;
use DataTables;

class TeachersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Teachers::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                       $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editRecord">Edit</a>';
                       $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteRecord">Delete</a>';
                        return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $subjects = Subjects::select('id','name')->where('status',1)->orderby('name','ASC')->get();
        return view('teachers.index',compact('subjects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->teacher_id){
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|max:100|email',
                'teacher_sub_ids' => 'required'
            ]);
        }else{
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|max:100|unique:teachers',
                'teacher_sub_ids' => 'required'
            ]);
        }
       
        //Redirect back if validation fails
        if($validator->fails()) {
            return response()->json(['error_code'=>401,'error'=>$validator->errors()->all()]);
        }

        Teachers::updateOrCreate([
            'id' => $request->teacher_id
        ],
        [
            'name' => $request->name, 
            'email' => $request->email, 
            'teacher_sub_ids' => implode(',', $request->teacher_sub_ids), 
        ]);        
     
        return response()->json(['success'=>'Teacher saved successfully.']);
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
        $teacher = Teachers::find($id);
        return response()->json($teacher);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Teachers::find($id)->delete();
        return response()->json(['success'=>'Teacher deleted successfully.']);
    }
}