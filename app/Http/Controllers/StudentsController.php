<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Students;
use App\Models\Subjects;
use DataTables;

class StudentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Students::latest()->get();
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
        return view('students.index',compact('subjects'));
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
        if($request->student_id){
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|max:100|email',
                'student_sub_ids' => 'required'
            ]);
        }else{
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|max:100|unique:students',
                'student_sub_ids' => 'required'
            ]);
        }
       
        //Redirect back if validation fails
        if($validator->fails()) {
            return response()->json(['error_code'=>401,'error'=>$validator->errors()->all()]);
        }

        Students::updateOrCreate([
            'id' => $request->student_id
        ],
        [
            'name' => $request->name, 
            'email' => $request->email, 
            'student_sub_ids' => implode(',', $request->student_sub_ids), 
        ]);        
     
        return response()->json(['success'=>'Student saved successfully.']);
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
        $student = Students::find($id);
        return response()->json($student);
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
        Students::find($id)->delete();
        return response()->json(['success'=>'Student deleted successfully.']);
    }
}
