@extends('layouts.app')
@section('title')
<title>Assign Student to Teacher - {{config('app.name')}}</title>
@endsection
@section('content')
<div class="container" style="margin-top: 20px;">
    <h1>Assign Student to Teacher</h1>
    <a class="btn btn-success" href="javascript:void(0)" id="createNewRecord" style="margin:10px 0px;">Assign Student</a>
    <a class="btn btn-success" href="{{ url('/') }}" id="createNewRecord" style="margin:10px 0px;float:right;">Back</a>
    <table class="table table-bordered data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Email</th>
                <th width="280px">Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
     
<div class="modal fade" id="ajaxModel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
            </div>
            <div class="modal-body">
              <span id="nameRequired"></span>
                <form id="assignStudentForm" name="assignStudentForm" class="form-horizontal">
                   <input type="hidden" name="assign_id" id="assign_id">
                    <div class="form-group">
            			<label for="name" class="col-sm-6 control-label">Teacher<span class="required-field">*</span></label>
			            <div class="col-sm-12">
			              <select class="form-control" id="teacher_id"  name="teacher_id" required="">
			                @foreach($teachers as $teacher)
			                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
			                @endforeach
			                </select>
			            </div>
			        </div>

			        <div class="form-group">
			            <label for="name" class="col-sm-6 control-label">Subject<span class="required-field">*</span></label>
			            <div class="col-sm-12">
			              <select class="form-control" id="subject_id" name="subject_id" required="">
			                @foreach($subjects as $subject)
			                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
			                @endforeach
			                </select>
			            </div>
			        </div>
			      
			        <div class="form-group">
			            <label for="name" class="col-sm-6 control-label">Assign Students<span class="required-field">*</span></label>
			            <div class="col-sm-12">
			              <select class="form-control" id="assign_stu_ids" multiple="multiple" name="assign_stu_ids[]" required="">
			                @foreach($students as $student)
			                    <option value="{{ $student->id }}">{{ $student->name }}</option>
			                @endforeach
			                </select>
			            </div>
			        </div>
        
                    <div class="col-sm-offset-2 col-sm-10">
                     <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Save changes
                     </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>  
@endsection
@section('javascript')
<script type="text/javascript">
  $(function () {
      
    /*--------------------- Pass Header Token --------------*/ 
    $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
    });

    /*--------------------- Render DataTable --------------*/ 
      
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('assign-student-crud.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'name', name: 'name'},
            {data: 'email', name: 'email'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });

    /*--------------------- Click to Button --------------*/ 
      
    $('#createNewRecord').click(function () {
        $('#saveBtn').val("create-record");
        $('#assign_id').val('');
        $('#assignStudentForm').trigger("reset");
        $('#modelHeading').html("Create New Record");
        $('#ajaxModel').modal('show');
    });

    /*--------------------- Click to Edit Button --------------*/ 
    
    $('body').on('click', '.editRecord', function () {
      var assign_id = $(this).data('id');
      $.get("{{ route('assign-student-crud.index') }}" +'/' + assign_id +'/edit', function (data) {
          $('#modelHeading').html("Edit Record");
          $('#saveBtn').val("edit-user");
          $('#ajaxModel').modal('show');
          $('#assign_id').val(data.id);
          $('#teacher_id').val(data.teacher_id);
          $('#subject_id').val(data.subject_id);
          $('#assign_stu_ids').val(data.assign_stu_ids);
      })
    });

    /*--------------------- Create New Record Code --------------*/ 
    
    $('#saveBtn').click(function (e) {
        e.preventDefault();
        $(this).html('Sending..');
      
        $.ajax({
          data: $('#assignStudentForm').serialize(),
          url: "{{ route('assign-student-crud.store') }}",
          type: "POST",
          dataType: 'json',
          success: function (data) {
            if(data.error_code == 401){
               $('#nameRequired').html("<label class='text-danger'>"+ data.error+"</label>");
            }else{
              $('#assignStudentForm').trigger("reset");
              $('#ajaxModel').modal('hide');
              $('#nameRequired').html("");
              alert(data.success);
              table.draw();
            }
          },
          error: function (data) {
              console.log('Error:', data);
              $('#saveBtn').html('Save Changes');
          }
      });
    });

    /*--------------------- Delete Record Code --------------*/ 
      
    $('body').on('click', '.deleteRecord', function () {
        var assign_id = $(this).data("id");
        confirm("Are You sure want to delete !");
        $.ajax({
            type: "DELETE",
            url: "{{ route('assign-student-crud.store') }}"+'/'+assign_id,
            success: function (data) {
                table.draw();
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });
       
  });
</script>
@endsection
</html>