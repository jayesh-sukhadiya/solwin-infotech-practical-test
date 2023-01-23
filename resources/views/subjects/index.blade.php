@extends('layouts.app')
@section('title')
<title>Subjects - {{config('app.name')}}</title>
@endsection
@section('content')
<div class="container" style="margin-top: 20px;">
    <h1>Subjects Record</h1>
    <a class="btn btn-success" href="javascript:void(0)" id="createNewRecord" style="margin:10px 0px;"> Create New Subject</a>
    <a class="btn btn-success" href="{{ url('/') }}" id="createNewRecord" style="margin:10px 0px;float:right;">Back</a>
    <table class="table table-bordered data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
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
                <form id="subjectForm" name="subjectForm" class="form-horizontal">
                   <input type="hidden" name="subject_id" id="subject_id">
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">Name<span class="required-field">*</span></label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="" maxlength="50" required="">
                            <span id="nameRequired"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">Status<span class="required-field">*</span></label>
                        <div class="col-sm-12">
                            <select class="form-control" id="status" name="status">
                              <option value="1">Active</option>}
                              <option value="0">Inactive</option>}
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
        ajax: "{{ route('subjects-crud.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'name', name: 'name'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });

    /*--------------------- Click to Button --------------*/ 
      
    $('#createNewRecord').click(function () {
        $('#saveBtn').val("create-record");
        $('#subject_id').val('');
        $('#subjectForm').trigger("reset");
        $('#modelHeading').html("Create New Record");
        $('#ajaxModel').modal('show');
    });

    /*--------------------- Click to Edit Button --------------*/ 
    
    $('body').on('click', '.ediRecord', function () {
      var subject_id = $(this).data('id');
      $.get("{{ route('subjects-crud.index') }}" +'/' + subject_id +'/edit', function (data) {
          $('#modelHeading').html("Edit Record");
          $('#saveBtn').val("edit-user");
          $('#ajaxModel').modal('show');
          $('#subject_id').val(data.id);
          $('#name').val(data.name);
          $('#status').val(data.status);
      })
    });

    /*--------------------- Create New Record Code --------------*/ 
    
    $('#saveBtn').click(function (e) {
        e.preventDefault();
        $(this).html('Sending..');
      
        $.ajax({
          data: $('#subjectForm').serialize(),
          url: "{{ route('subjects-crud.store') }}",
          type: "POST",
          dataType: 'json',
          success: function (data) {
              if(data.error_code == 401){
               $('#nameRequired').html("<label class='text-danger'>"+ data.error+"</label>");
              }else{
                $('#subjectForm').trigger("reset");
                $('#ajaxModel').modal('hide');
                $('#nameRequired').html('');
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
        var subject_id = $(this).data("id");
        confirm("Are You sure want to delete !");
        $.ajax({
            type: "DELETE",
            url: "{{ route('subjects-crud.store') }}"+'/'+subject_id,
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