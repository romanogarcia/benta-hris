@extends('layouts.master')
@section('title', 'Payroll Settings')
@section('content')
<div class="content-wrapper">
  <div class="content">
    <div class="card">
		<div class="card-header">
			Payroll Settings
		</div>
		<div class="card-body">
			 <div class="col-md-12 col-sm-12 col-xs-12">
				 <ul class="nav nav-tabs" role="tablist">
					 @if((check_permission(Auth::user()->Employee->department_id,"SSS","full")) || (check_permission(Auth::user()->Employee->department_id,"SSS","ADD")) || (check_permission(Auth::user()->Employee->department_id,"SSS","View")))
					<li class="nav-item">
						<a class="nav-link active" data-toggle="tab" href="#sss" role="tab">SSS</a>
					</li>
					 @endif
					 @if((check_permission(Auth::user()->Employee->department_id,"Tax","full")) || (check_permission(Auth::user()->Employee->department_id,"Tax","ADD")) || (check_permission(Auth::user()->Employee->department_id,"Tax","View")))
					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="#tax" role="tab">Tax</a>
					</li>
					 @endif
					 @if((check_permission(Auth::user()->Employee->department_id,"Philhealth","full")) || (check_permission(Auth::user()->Employee->department_id,"Philhealth","ADD")) || (check_permission(Auth::user()->Employee->department_id,"Philhealth","View")))
					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="#philhealth" role="tab">Philhealth</a>
					</li>
					 @endif
					 @if((check_permission(Auth::user()->Employee->department_id,"Pag-lbig","full")) || (check_permission(Auth::user()->Employee->department_id,"Pag-lbig","ADD")) || (check_permission(Auth::user()->Employee->department_id,"Pag-lbig","View")))
					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="#pagibig" role="tab">Pag-Ibig</a>
					</li>
					 @endif
				</ul>

    <!-- Tab panes {Fade}  -->
				<div class="tab-content">
					<div class="tab-pane fade active show" id="sss" name="sss" role="tabpanel">
						<div class="bs-callout bs-callout-danger">
								  @if ($message = Session::get('success')) 
								  <div class="alert alert-success" role="alert">
									<i class="mdi mdi-alert-circle"></i>
									<strong>{{ $message }}</strong>
								  </div>
								  @endif

								  @if ($errors->any())
								  <div class="alert alert-danger">
									  @foreach ($errors->all() as $error)        
									  <i class="mdi mdi-alert-circle"></i>
									  <strong>{{ $error }}</strong>
									  @endforeach
								  </div>
								  @endif

								 
								  <div class="table-responsive">
									  
									<table class="table table-bordered" id="sss_table">
									  <thead>
										<tr>
										  <th> ID&nbsp; </th>
										  <th> Min&nbsp; </th>
										  <th> Max&nbsp; </th>
										  <th> Salary&nbsp; </th>
										  <th> EC&nbsp; </th>
										  <th> Total Contribution Employer&nbsp; </th>
										  <th> Total Contribution Employee&nbsp; </th>
										  <th> Total Contribution&nbsp; </th>
										  <th> Action&nbsp; </th>
										</tr>
									  </thead>
									  <tbody>
										
									  </tbody>
									</table>
								  </div>
									
						</div>
					</div>
				  <div class="tab-pane fade" id="tax" role="tabpanel">
					  <div class="bs-callout bs-callout-danger" >
								  @if ($message = Session::get('success')) 
								  <div class="alert alert-success" role="alert">
									<i class="mdi mdi-alert-circle"></i>
									<strong>{{ $message }}</strong>
								  </div>
								  @endif

								  @if ($errors->any())
								  <div class="alert alert-danger">
									  @foreach ($errors->all() as $error)        
									  <i class="mdi mdi-alert-circle"></i>
									  <strong>{{ $error }}</strong>
									  @endforeach
								  </div>
								  @endif
								  
								  <div class="table-responsive">
									<table class="table table-bordered" id="tax_table">
									  <thead>
										<tr>
										<th> Tax ID&nbsp; </th>
										<th> Compensation Level&nbsp; </th> 
										<th> Over&nbsp; </th>
										<th> Prescribe Minimum WH Tax&nbsp; </th> 
										<th> Additional Percentage over CL&nbsp; </th>
										<th> Action&nbsp; </th>
										</tr>
									  </thead>
									  <tbody>

									  </tbody>
									</table>
								  </div>
						
					   </div>
						
				  </div>
				  <div class="tab-pane fade" id="philhealth" name="philhealth" role="tabpanel">
					<div class="bs-callout bs-callout-danger">
						

							  @if ($message = Session::get('success')) 
							  <div class="alert alert-success" role="alert">
								<i class="mdi mdi-alert-circle"></i>
								<strong>{{ $message }}</strong>
							  </div>
							  @endif

							  @if ($errors->any())
							  <div class="alert alert-danger">
								  @foreach ($errors->all() as $error)        
								  <i class="mdi mdi-alert-circle"></i>
								  <strong>{{ $error }}</strong>
								  @endforeach
							  </div>
							  @endif
								
								<div class="table-responsive">
								<table class="table table-bordered" id="philhealth_table">
								  <thead>
									<tr>
									<th> ID&nbsp; </th>
									<th> Salary Bracket&nbsp; </th>
									<th> Salary Min&nbsp; </th>
									<th> Salary Max&nbsp; </th> 
									<th> Total Monthly Premium&nbsp; </th> 
									<th> Employee Share&nbsp; </th> 
									<th> Employer Share&nbsp; </th> 
									<th> Action&nbsp; </th> 
									</tr>
								  </thead>
								  <tbody>
									
								  </tbody>
								</table>
							  </div>
							
						
					</div>
				  </div>
				  <div class="tab-pane fade" id="pagibig" name="pagibig" role="tabpanel">
					 <div class="bs-callout bs-callout-danger">

							  @if ($message = Session::get('success')) 
							  <div class="alert alert-success" role="alert">
								<i class="mdi mdi-alert-circle"></i>
								<strong>{{ $message }}</strong>
							  </div>
							  @endif

							  @if ($errors->any())
							  <div class="alert alert-danger">
								  @foreach ($errors->all() as $error)        
								  <i class="mdi mdi-alert-circle"></i>
								  <strong>{{ $error }}</strong>
								  @endforeach
							  </div>
							  @endif
						
								<div class="table-responsive" >
									<table class="table table-bordered" id="pagibig_table">
									  <thead>
										<tr>
										<th> ID&nbsp;</th>	
										<th> Monthly Compensation&nbsp; </th> 
										<th> Employee Share&nbsp; </th> 
										<th> Employer Share&nbsp; </th> 
										<th colspan="2"> Action&nbsp; </th>
										</tr>
									  </thead>
									</table> 	
								</div>
						
					</div>
				  </div>
				</div>
			 </div>
		</div>	
	</div>
  </div>	  
</div>
<div id="DeleteSSSModal" class="modal fade text-default" role="dialog">
	<div class="modal-dialog ">
		<!-- Modal content-->
		<form action="" id="deleteSSSForm" method="post">
			<div class="modal-content">
				<div class="modal-header bg-default">

					<h4 class="modal-title text-center">REMOVE SSS TABLE?</h4>
				</div>
				<div class="modal-body">
					{{ csrf_field() }}
					{{ method_field('DELETE') }}
					<p>Do you want to really remove? This request can't be removed anymore.</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-success" data-dismiss="modal">Cancel</button>
					<button type="submit" name="" class="btn btn-danger">Yes, Remove</button>
				</div>
			</div>
		</form>
	</div>
</div>
<div id="uploadModal" class="modal fade text-default" role="dialog">
    <div class="modal-dialog ">
        <!-- Modal content-->
        <form action="" id="deleteForm" method="post">
            <div class="modal-content">
                <div class="modal-header bg-default">

                    <h4 class="modal-title text-center">UPLOAD SSS TABLE</h4>
                </div>
                <div class="modal-body">
                    {{ csrf_field() }}
                    <p>
                    Upload the SSS table in csv format
                    </p>
                    <form method="POST" action="{{ route('sss.store') }}" enctype="multipart/form-data" autocomplete="off">
                        @csrf
                        <div class="form-group">
                            <div class="input-group">
                                <input type="file" class="form-control form-control-sm" placeholder="Name" name="select_file" autocomplete="off" style="text-transform: uppercase;">
                                <div class="input-group-append">
                                    <button class="btn btn-sm btn-primary" type="submit"><i class="mdi mdi-library-plus"></i>&nbsp;&nbsp;&nbsp; UPLOAD NEW FILE &nbsp;&nbsp;&nbsp;</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div id="DeleteTaxModal" class="modal fade text-default" role="dialog">
 <div class="modal-dialog ">
   <!-- Modal content-->
   <form action="" id="deleteTaxForm" method="post">
       <div class="modal-content">
           <div class="modal-header bg-default">
               
               <h4 class="modal-title text-center">DELETE ROW ?</h4>
           </div>
           <div class="modal-body">
               {{ csrf_field() }}
               {{ method_field('DELETE') }}
               <p class="text-center">
                <center>
                   <button type="button" class="btn btn-success" data-dismiss="modal">Cancel</button>
                   <button type="submit" name="" class="btn btn-danger" >Yes, Delete</button>
               </center>
             </p>
           </div>
           <div class="modal-footer">
             
           </div>
       </div>
   </form>
 </div>
</div>
<div id="DeletePhilhealthModal" class="modal fade text-default" role="dialog">
 <div class="modal-dialog ">
   <!-- Modal content-->
   <form action="" id="deletePhilhealthForm" method="post">
       <div class="modal-content">
           <div class="modal-header bg-default">
               
               <h4 class="modal-title text-center">REMOVE SALARY BRACKET ?</h4>
           </div>
           <div class="modal-body">
               {{ csrf_field() }}
               {{ method_field('DELETE') }}
               <p class="text-center">
                <center>
                   <button type="button" class="btn btn-success" data-dismiss="modal">Cancel</button>
                   <button type="submit" name="" class="btn btn-danger" >Yes, Delete</button>
               </center>
             </p>
           </div>
           <div class="modal-footer">
             
           </div>
       </div>
   </form>
 </div>
</div>
<div id="DeleteModal" class="modal fade text-default" role="dialog">
 <div class="modal-dialog ">
   <!-- Modal content-->
   <form action="" id="deleteForm" method="post">
       <div class="modal-content">
           <div class="modal-header bg-default">

				<h4 class="modal-title text-center">REMOVE MONTHLY COMPENSATION?</h4>
			</div>
			<div class="modal-body">
				{{ csrf_field() }}
				{{ method_field('DELETE') }}
				<p class="delete-alert">Do you want to really remove? This request can't be removed anymore.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success" data-dismiss="modal">Cancel</button>
				<button type="submit" name="" class="btn btn-danger">Yes, Remove</button>
			</div>
       </div>
   </form>
 </div>
</div>
@endsection	  
@section('customjs')
<script type="text/javascript">
$(document).ready(function(){
	load_sss_tables();
	 $('#sss_table').DataTable().draw(true);
		$("div.sss_function").html('@if((check_permission(Auth::user()->Employee->department_id,"SSS","full")) || (check_permission(Auth::user()->Employee->department_id,"SSS","ADD")))<div class="col-md-6 mrt-sm-2"><a href="{{route('sss.create')}}" class="btn btn-primary btn-block w-sm-100"><i class="mdi mdi-library-plus"></i> ADD NEW SSS TABLE</a></div><div class="col-md-6 mrt-sm-2"><button type="button" data-target="#uploadModal" data-toggle="modal" class="btn btn-primary btn-block w-sm-100"><i class="mdi mdi-library-plus"></i>&nbsp;&nbsp;&nbsp; UPLOAD NEW FILE &nbsp;&nbsp;&nbsp;</button></div>@endif');
	
	load_tax_tables();
	$('#tax_table').DataTable().draw(true);
	$("div.tax_function").html('@if((check_permission(Auth::user()->Employee->department_id,"Tax","full")) || (check_permission(Auth::user()->Employee->department_id,"Tax","ADD")))<div class="col-md-6 mrt-sm-2"><a href="{{route('tax.create')}}" class="btn btn-primary btn-block w-sm-100"><i class="mdi mdi-library-plus"></i> ADD NEW TAX</a></div>@endif');

	load_philhealth_tables();
	$('#philhealth_table').DataTable().draw(true);
	$("div.philhealth_function").html(' @if((check_permission(Auth::user()->Employee->department_id,"Philhealth","full")) || (check_permission(Auth::user()->Employee->department_id,"Philhealth","ADD")))<div class="col-md-6 mrt-sm-2"><a href="{{route('philhealth.create')}}" class="btn btn-primary btn-block w-sm-100"><i class="mdi mdi-library-plus"></i>  Add New Philhealth</a></div>@endif');
	
	load_pagibig_tables();
	$('#pagibig_table').DataTable().draw(true);
	$("div.pagibig_function").html('@if((check_permission(Auth::user()->Employee->department_id,"Pag-lbig","full")) || (check_permission(Auth::user()->Employee->department_id,"Pag-lbig","ADD")))<div class="col-md-6 mrt-sm-2"><a href="{{route('pagibig.create')}}" class="btn btn-primary btn-block w-sm-100"><i class="mdi mdi-library-plus"></i>   ADD NEW PAG-IBIG</a></div>@endif');
	
	$("#deleteSSSForm").submit(function(e){
		e.preventDefault();
		var action = $(this).attr('action');
		$.ajax({
			url:action,
			type:'DELETE',
			data:{"_token": "{{ csrf_token() }}",},
			success:function(res){
				window.location.reload();
			}
		})
	});
	$("#deleteTaxForm").submit(function(e){
		e.preventDefault();
		var action = $(this).attr('action');
		$.ajax({
			url:action,
			type:'DELETE',
			data:{"_token": "{{ csrf_token() }}",},
			success:function(res){
				window.location.reload();
			}
		})
	});
	$("#deletePhilhealthForm").submit(function(e){
		e.preventDefault();
		var action = $(this).attr('action');
		$.ajax({
			url:action,
			type:'DELETE',
			data:{"_token": "{{ csrf_token() }}",},
			success:function(res){
				window.location.reload();
			}
		})
	});
	
	
});
function deletesss(id){
   var id = id;
   var url = '{{ route("sss.destroy", ":id") }}';
   url = url.replace(':id', id);
   $("#deleteSSSForm").attr('action', url);
}	
function deletetax(id){
   var id = id;
   var url = '{{ route("tax.destroy", ":id") }}';
   url = url.replace(':id', id);
   $("#deleteTaxForm").attr('action', url);
}		
function deletephilhealth(id){
   var id = id;
   var url = '{{ route("philhealth.destroy", ":id") }}';
   url = url.replace(':id', id);
   $("#deletePhilhealthForm").attr('action', url);
}	
function load_sss_tables(){
   	$('#sss_table').DataTable({
        searching: false,
        processing: true,
        serverSide: true,
        responsive: true,
        autoWidth : false,
        dom   : "<'row'<'col-sm-6 col-xs-12 col-md-8 '<'row sss_function'>><'col-sm-6 col-xs-12 col-md-4 text-right'l>><'row'<'col-sm-12'tr>><'row'<'col-sm-5 col-xs-6'i><'col-sm-7 col-xs-6 'p>>",
        ajax: {
            url : "{{ route('payroll.get_sss_table') }}",
            type: 'GET',
        },	  
        columns: [
            { data: 'id', sortable:true },
            { data: 'min', sortable:true },
            { data: 'max', sortable:true },
            { data: 'salary', sortable:true },
            { data: 'sss_ec_er', sortable:true },
            { data: 'total_contribution_er', sortable:true },
            { data: 'total_contribution_ee', sortable:true },
            { data: 'total_contribution_total', sortable:true },
            { data: 'action', sortable:false },
        ],
    });
}	
function load_tax_tables(){
   	$('#tax_table').DataTable({
        searching: false,
        processing: true,
        serverSide: true,
        responsive: true,
        autoWidth : false,
        dom   : "<'row'<'col-sm-6 col-xs-12 col-md-8 '<'row tax_function'>><'col-sm-6 col-xs-12 col-md-4 text-right'l>><'row'<'col-sm-12'tr>><'row'<'col-sm-5 col-xs-6'i><'col-sm-7 col-xs-6 'p>>",
        ajax: {
            url : "{{ route('payroll.get_tax_table') }}",
            type: 'GET',
        },	  
        columns: [
            { data: 'id', sortable:true },
            { data: 'compensation_level', sortable:true },
            { data: 'over', sortable:true },
            { data: 'tax', sortable:true },
            { data: 'percentage', sortable:true },
            { data: 'action', sortable:false },
        ],
    });
}	
function load_philhealth_tables()
{
	$('#philhealth_table').DataTable({
        searching: false,
        processing: true,
        serverSide: true,
        responsive: true,
        autoWidth : false,
        dom   : "<'row'<'col-sm-6 col-xs-12 col-md-8 '<'row philhealth_function'>><'col-sm-6 col-xs-12 col-md-4 text-right'l>><'row'<'col-sm-12'tr>><'row'<'col-sm-5 col-xs-6'i><'col-sm-7 col-xs-6 'p>>",
        ajax: {
            url : "{{ route('payroll.get_philhealth_table') }}",
            type: 'GET',
        },	  
        columns: [
            { data: 'id', sortable:true },
            { data: 'salary_bracket', sortable:true },
            { data: 'salary_min', sortable:true },
            { data: 'salary_max', sortable:true },
            { data: 'total_monthly_premium', sortable:true },
            { data: 'employee_share', sortable:true },
            { data: 'employer_share', sortable:true },
            { data: 'action', sortable:false },
        ],
    });
}	
function load_pagibig_tables()
{
        $('#pagibig_table').DataTable({
            searching: false,
            processing: true,
            serverSide: true,
            responsive: true,
            autoWidth : false, 
            dom   : "<'row'<'col-sm-6 col-xs-12 col-md-8 '<'row pagibig_function'>><'col-sm-6 col-xs-12 col-md-4 text-right'l>><'row'<'col-sm-12'tr>><'row'<'col-sm-5 col-xs-6'i><'col-sm-7 col-xs-6 'p>>",
            ajax: {
                url : "{{ url('pagibigs/pagibig_list') }}",
                type: 'GET',
                data: function (f) {
                  
                }
            },	  
            columns: [
                { data: 'id', sortable:true },
                { data: 'monthly_compensation', sortable:false },
                { data: 'employee_share', sortable:true },
                { data: 'employer_share', sortable:true },
                { data: 'action_edit', sortable:false },
                { data: 'action_delete', sortable:false }
            ],
        });
        $('#pagibig_table').on( 'error.dt', function ( e, settings, techNote, message ) {
            console.log( 'An error has been reported by DataTables: ', message );
        } ) .DataTable();
        $.fn.dataTable.ext.errMode = 'none';
      
        $("#pagibig_table").on('click', '.btn-delete_row', function (){
            var id = $(this).data('id');
            var url = '{{ route("pagibig.destroy", ":id") }}';
            url = url.replace(':id', id);
            $("#DeleteModal").find('.delete-alert').html('Do you want to remove <span class="badge badge-primary">'+id+'</span> ? This overtime request can\'t be restored anymore.');
            $("#DeleteModal #deleteForm").attr('action', url);
        });

        $("#deleteForm #btn-submit_delete_row").on('click', function (){
            $("#DeleteModal #deleteForm").submit();
        });
}
</script>
@endsection