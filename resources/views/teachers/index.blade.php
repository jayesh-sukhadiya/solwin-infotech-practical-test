@extends('layouts.app')
@section('title')
<title>Teachers - {{config('app.name')}}</title>
@endsection
@section('content')
<div class="container" style="margin-top: 20px;">
    <h1>Teachers Record</h1>
    <a class="btn btn-success" href="javascript:void(0)" id="createNewRecord" style="margin:10px 0px;">Add New Teacher</a>
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
                <form id="teacherForm" name="teacherForm" class="form-horizontal">
                   <input type="hidden" name="teacher_id" id="teacher_id">
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">Name<span class="required-field">*</span></label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="" maxlength="100" required="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">Email<span class="required-field">*</span></label>
                        <div class="col-sm-12">
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email" value="" maxlength="100" required="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-6 control-label">Subjects<span class="required-field">*</span></label>
                        <div class="col-sm-12">
                          <select class="form-control" id="teacher_sub_ids" multiple="multiple" name="teacher_sub_ids[]" required="">
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
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
        ajax: "{{ route('teachers-crud.index') }}",
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
        $('#teacher_id').val('');
        $('#teacherForm').trigger("reset");
        $('#modelHeading').html("Create New Record");
        $('#ajaxModel').modal('show');
    });

    /*--------------------- Click to Edit Button --------------*/ 
    
    $('body').on('click', '.editRecord', function () {
      var teacher_id = $(this).data('id');
      $.get("{{ route('teachers-crud.index') }}" +'/' + teacher_id +'/edit', function (data) {
          $('#modelHeading').html("Edit Record");
          $('#saveBtn').val("edit-user");
          $('#ajaxModel').modal('show');
          $('#teacher_id').val(data.id);
          $('#name').val(data.name);
          $('#email').val(data.email);
          $('#teacher_sub_ids').val(data.teacher_sub_ids);
      })
    });

    /*--------------------- Create New Record Code --------------*/ 
    
    $('#saveBtn').click(function (e) {
        e.preventDefault();
        $(this).html('Sending..');
      
        $.ajax({
          data: $('#teacherForm').serialize(),
          url: "{{ route('teachers-crud.store') }}",
          type: "POST",
          dataType: 'json',
          success: function (data) {
       
              $('#teacherForm').trigger("reset");
              $('#ajaxModel').modal('hide');
              table.draw();
           
          },
          error: function (data) {
              console.log('Error:', data);
              $('#saveBtn').html('Save Changes');
          }
      });
    });

    /*--------------------- Delete Record Code --------------*/ 
      
    $('body').on('click', '.deleteRecord', function () {
        var teacher_id = $(this).data("id");
        confirm("Are You sure want to delete !");
        $.ajax({
            type: "DELETE",
            url: "{{ route('teachers-crud.store') }}"+'/'+teacher_id,
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