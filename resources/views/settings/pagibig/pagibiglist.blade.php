@extends('layouts.master')
@section('title', 'Pag Ibig')
@section('content')
<div class="content-wrapper">
  <div class="row">
    <div class="col-sm-12 grid-margin stretch-card">
      <div class="card">
      <div class="card-header">Pag Ibig 2019</div>
        <div class="card-body">
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
			@if((check_permission(Auth::user()->Employee->department_id,"Pag-lbig","full")) || (check_permission(Auth::user()->Employee->department_id,"Pag-lbig","Add")))
			<div class="form-group">
          		<a href="{{ route('pagibig.create') }}" class="btn btn-primary w-sm-100" style="position:relative;z-index:999;"><i class="mdi mdi-plus"></i> ADD NEW PAG-IBIG</a>
			</div>	
			@endif
          @if((check_permission(Auth::user()->Employee->department_id,"Pag-lbig","full")) || (check_permission(Auth::user()->Employee->department_id,"Pag-lbig","View")))
			<div class="table-responsive" id="search_pag_ibig_container">
				<table class="table table-bordered" id="pag_ibig_tbl">
				  <thead>
					<tr>
					<th> ID&nbsp;</th>	
					<th> Monthly Compensation&nbsp; </th> 
					<th> Employee Share&nbsp; </th> 
					<th> Employer Share&nbsp; </th> 
					<th colspan="2"> Action&nbsp;</th>
					</tr>
				  </thead>
				</table> 	
			</div>
			@endif
        </div>
      </div>
    </div><!-- /col-lg-12 -->
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

<script type="text/javascript">
function deleteData(id)
{
   var id = id;
   var url = '{{ route("pagibig.destroy", ":id") }}';
   url = url.replace(':id', id);
   $("#deleteForm").attr('action', url);
}

function formSubmit()
{
   $("#deleteForm").submit();
}


</script>
@section('customjs')
<script type="text/javascript">

var SITEURL = '{{URL::to('')}}';
 $(document).ready( function () {
    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
      }
    });
    
	load_datatable();
	
 });
  function load_datatable(){
        $("#search_pag_ibig_container").show();
        $('#pag_ibig_tbl').DataTable({
            searching: false,
            processing: true,
            serverSide: true,
            responsive: true,
            autoWidth : false, 
            dom   : "<'row'<'col-sm-12 float-right margin-bottom20'B>><'row'<'col-sm-12 col-xs-12 text-right'l>><'row'<'col-sm-12'tr>><'row'<'col-sm-5 col-xs-6'i><'col-sm-7 col-xs-6'p>>",
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
        $('#pag_ibig_tbl').on( 'error.dt', function ( e, settings, techNote, message ) {
            console.log( 'An error has been reported by DataTables: ', message );
        } ) .DataTable();
        $.fn.dataTable.ext.errMode = 'none';
        //$("#search_pag_ibig_container").hide();
      	/*if(window.matchMedia("(max-width: 992px)").matches){
            $("#search_result_container").css("margin-top", "0px");
        }else{
            $("#search_result_container").css("margin-top", "-50px");
        }*/
		
        $("#pag_ibig_tbl").on('click', '.btn-delete_row', function (){
            var id = $(this).data('id');
            var url = '{{ route("pagibig.destroy", ":id") }}';
            url = url.replace(':id', id);
            $("#DeleteModal").find('.delete-alert').html('Do you want to remove <span class="badge badge-primary">'+id+'</span> ? This overtime request can\'t be restored anymore.');
            $("#deleteForm").attr('action', url);
        });

        $("#btn-submit_delete_row").on('click', function (){
            $("#deleteForm").submit();
        });
    }

</script>
@endsection