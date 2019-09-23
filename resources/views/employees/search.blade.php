@extends('layouts.master')
@section('title', 'Employees')
@section('content')
<div class="content-wrapper">
  <div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
      <div class="card">
		<div class="card-header">
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12" style="margin:auto;">Employees</div>
					
			</div>	
		  </div>
        <div class="card-body">			
        @if ($message = Session::get('success'))
            <div class="alert alert-success" role="alert">
                <i class="mdi mdi-alert-circle"></i>
                <strong>{{ $message }}</strong>
            </div>
        @endif														
        @if((check_permission(Auth::user()->Employee->department_id,"Employees","full")) || (check_permission(Auth::user()->Employee->department_id,"Employees","ADD")))
                <div class="row">
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <a style="position: relative; z-index: 999;" href="{{ URL::to('employees/create' ) }}" class="btn  btn-primary btn-icon-text btn-sm">
                                <i class="mdi mdi-account-plus"></i>                             
                                Add New Employee
                            </a>
							
                        </div>
                    </div>
                </div> 
			@endif

          <p class="card-description">
          </p>
          <div class="table-responsive" id="laravel_datatables_container">			  
			 <table class="table table-bordered table-striped" id="laravel_datatable">
			   <thead>
				  <tr>
					  <th> User&nbsp; </th>
					  <th> Employee No.&nbsp; </th>
					  <th> First Name&nbsp; </th>
					  <th> Last Name&nbsp; </th>
					  <th> Employment Status&nbsp; </th>
					  <th> Is Active&nbsp; </th>
					  <th> Action&nbsp; </th>
				  </tr>
			   </thead>
			</table> 
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div id="DeleteModal" class="modal fade text-default" role="dialog">
 <div class="modal-dialog ">
   <!-- Modal content-->
   <form action="" id="deleteForm" method="post">
       <div class="modal-content">
           <div class="modal-header bg-default">
               
               <h4 class="modal-title text-center">DELETE USER ?</h4>
           </div>
           <div class="modal-body">
               {{ csrf_field() }}
               {{ method_field('DELETE') }}
               <p class="text-center">
                <center>
                   <button type="button" class="btn btn-success" data-dismiss="modal">Cancel</button>
                   <button type="submit" name="" class="btn btn-danger" data-dismiss="modal" onclick="formSubmit()">Yes, Delete</button>
               </center>
             </p>
           </div>
           <div class="modal-footer">
             
           </div>
       </div>
   </form>
 </div>
</div>
@endsection

@section('customjs')
<script type="text/javascript">

function deleteData(id)
{
   var id = id;
   var url = '{{ route("employees.destroy", ":id") }}';
   url = url.replace(':id', id);
   $("#deleteForm").attr('action', url);
}

function formSubmit()
{
   $("#deleteForm").submit();
}

/* end destroy */

var SITEURL = '{{URL::to('')}}';
 $(document).ready( function () {
	 $('#date_range_pick').daterangepicker({
		showDropdowns: true,
		minYear: 1980,
		maxYear: parseInt(moment().format('YYYY')),
		locale: {
			  cancelLabel: 'Clear'
		},
		"autoUpdateInput": false,
		"autoApply":false,
		maxDate:moment(), 
	 });
   $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
      }
  });
   load_data_tables();
	
 });
  function load_data_tables(){
	 	$("#laravel_datatables_container").show();
        $('#laravel_datatable').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            responsive: true,
	 		      autoWidth : false,
            dom   : "<'row'<'col-sm-12 float-right margin-bottom20'B>><'row'<'col-sm-12 col-xs-12 text-right'l>><'row'<'col-sm-12'tr>><'row'<'col-sm-5 col-xs-6'i><'col-sm-7 col-xs-6'p>>",
            ajax: {
                url : "{{ route('employee.empSearch') }}",
                type: 'GET',
                data: function (f) {
                    f.employee_number = $('#emp_id').val();
                    f.first_name = $('#fname').val();
                    f.last_name = $('#lname').val();
                    f.department_id = $('#department_id').val();
                    f.employment_status_id = $('#employment_status_id').val();
                    f.is_active = null;
                        if($('#is_active').is(':checked') && $('#not_active').is(':checked')){
                            f.is_active = null;
                        }
                        else if($('#is_active').not(':checked') && $('#not_active').is(':checked')){
                            f.is_active = 0;
                        }
                        else if($('#is_active').is(':checked') && $('#not_active').not(':checked')){
                            f.is_active = 1;
                        }
                    
                    f.date_hired = $('#date_hired').val();
                }
            },	  
            columns: [
                    { data: 'emp_image',sortable:false},
                    { data: 'employee_number' },
                    { data: 'first_name' },
                    { data: 'last_name'},
                    { data: 'emp_status',sortable:false },
                    { data: 'active_status',sortable:false },
                    { data: 'action',sortable:false }
                    ]
        });
		
	 	$('#laravel_datatable').on( 'error.dt', function ( e, settings, techNote, message ) {
            console.log( 'An error has been reported by DataTables: ', message );
        } ) .DataTable();
        $.fn.dataTable.ext.errMode = 'none';
        $("#employee-search-form").hide();
      
    }

</script>
@endsection