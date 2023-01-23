<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Teachers;
use App\Models\Subjects;
use App\Models\Students;
use App\Models\AssignStudentToTeacher;
use DataTables;

class AssignStudentsController extends Controller
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
        $students = Students::select('id','name')->where('status',1)->orderby('name','ASC')->get();
        $teachers = Teachers::select('id','name')->where('status',1)->orderby('name','ASC')->get();
        return view('teachers.assign-student',compact('subjects','students','teachers'));
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
        if($request->assign_id){
            $validator = Validator::make($request->all(), [
                'teacher_id' => 'required',
                'subject_id' => 'required',
                'assign_stu_ids' => 'required',
            ]);
        }else{
            $validator = Validator::make($request->all(), [
                'teacher_id' => 'required|unique:assign_student_to_teachers',
                'subject_id' => 'required',
                'assign_stu_ids' => 'required',
            ]);
        }
        

        //Redirect back if validation fails
        if($validator->fails()) {
            return response()->json(['error_code'=>401,'error'=>$validator->errors()->all()]);
        }

        if(AssignStudentToTeacher::where('teacher_id',$request->teacher_id)->doesntExist()){
            AssignStudentToTeacher::updateOrCreate([
                'id' => $request->assign_id
            ],
            [
                'teacher_id' => $request->teacher_id, 
                'subject_id' => $request->subject_id, 
                'assign_stu_ids' => implode(',', $request->assign_stu_ids), 
            ]); 

            return response()->json(['success'=>'Assign students saved successfully.']);
        }else{
            return response()->json(['success'=>'Already assign students!']);
        } 
     
        
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
        return response()->json(['success'=>'Subject deleted successfully.']);
    }

    public function assignStuToTeacher()
    {
        $subjects = Subjects::select('id','name')->where('status',1)->orderby('name','ASC')->get();
        $students = Students::select('id','name')->where('status',1)->orderby('name','ASC')->get();
        $teachers = Teachers::select('id','name')->where('status',1)->orderby('name','ASC')->get();
        return view('teachers.assign-student',compact('subjects','students','teachers'));
    }
}