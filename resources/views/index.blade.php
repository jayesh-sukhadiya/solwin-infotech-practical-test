@extends('layouts.app')
@section('title')
<title>Dashboard - {{config('app.name')}}</title>
@endsection
@section('content')
<div class="container" style="margin-top: 20px;">
    <h1>Dashboard</h1>
    <a class="btn btn-success" href="{{ url('subjects-crud') }}" class="list-record" style="margin:10px 0px;">Subjects</a>
    <a class="btn btn-success" href="{{ url('students-crud') }}" class="list-record" style="margin:10px 0px;">Students</a>
    <a class="btn btn-success" href="{{ url('teachers-crud') }}" class="list-record" style="margin:10px 0px;">Teachers</a>
    <a class="btn btn-success" href="{{ url('assign-student-crud') }}" class="list-record" style="margin:10px 0px;">Assign Student to Teacher</a>
    <table class="table table-bordered data-table">
        <thead>
            <tr>
                <th>Teacher</th>
                <th>Subject</th>
                <th>Total Students Count</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($teachers as $teacher)
            <tr>
                <?php $studentCount = explode(',',$teacher->assign_stu_ids); ?>
                <td>{{$teacher['teacher']->name}}</td>
                <td>{{$teacher['subject']->name}}</td>
                <td>{{ count($studentCount) }}</td>
                <td>@if($teacher->status == 1){{ "Active" }} @else {{ 'Inactive' }} @endif</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
@section('javascript')
@endsection
</html>