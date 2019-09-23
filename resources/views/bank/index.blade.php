@extends('layouts.master')
@section('title', 'Company Bank')
@section('content')
<div class="content-wrapper">
  <div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-header">
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12" style="margin:auto;">Company Bank</div>
			</div>	
		  </div>
        <div class="card-body">
            @if ($message = Session::get('success'))
                    <div class="alert alert-success" role="alert">
                        <i class="mdi mdi-alert-circle"></i>
                        <strong>{{ $message }}</strong>
                    </div>
            @elseif($message = Session::get('error'))
                <div class="alert alert-danger" role="alert">
                    <i class="mdi mdi-alert-circle"></i>
                    <strong>{{ $message }}</strong>
                </div>
            @endif
            
          
            <div class="row">
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <a style="position: relative; z-index: 999;" href="{{ route('banks.create' ) }}" class="btn  btn-primary btn-icon-text btn-sm">
                            <i class="mdi mdi-plus"></i>                                                    
                            Add New Bank
                        </a>
                    </div>
                </div>
            </div>
            <!-- <div id="bank-search-form">
                <div class="row">
                    <div class="col-md-4">
                        <label class="bank_name">Bank</label>
                        <select class="form-control form-control-sm" name="bank_id" id="bank_id">
                            <option value="">Select Bank</option>
                            @foreach($banks as $bank)
                                <option value="{{ $bank->id }}">{{ $bank->bank_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 offset-md-10">
                        <div class="form-group">
                            <button type="button" id="searchBank" class="btn btn-block btn-primary btn-icon-text btn-sm">
                                <i class="mdi mdi-magnify"></i>                                                    
                                Search 
                            </button>
                        </div>       
                    </div>
                </div>	
            </div>																						 -->

            <p class="card-description">
            </p>

            <div class="table-responsive" id="laravel_datatables_container" style="display: none;">			  
                <table class="table table-bordered table-striped" id="laravel_datatable">
                <thead>
                    <tr>
                        <th> Bank&nbsp; </th>
                        <th> IBAN&nbsp; </th>
                        <th> BIC&nbsp; </th>
                        <th> Member No&nbsp; </th>
                        <th> Clearing No&nbsp; </th>
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
               <h4 class="modal-title text-center">DELETE BANK ?</h4>
           </div>
           <div class="modal-body">
                @method('DELETE')
                @csrf
                <div class="text-center">
                    <button type="button" class="btn btn-success" data-dismiss="modal">Cancel</button>
                    <button type="submit" name="" class="btn btn-danger" data-dismiss="modal" onclick="formSubmit()">Yes, Delete</button>
                </div>
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
        var url = '{{ route("banks.destroy", ":id") }}';
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
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
    
        window.onload = function () {
            load_data_tables();
            $('#laravel_datatable').DataTable().draw(true);
        }

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
                url : "{{ url('banks_list_ajax') }}",
                type: 'GET',
                data: function (f) {
                    f.id = $('#bank_id').val();
                    console.log(f.id);
                }
            },	  
            columns: [
                    { data: 'bank_name' },
                    { data: 'IBAN' },
                    { data: 'BIC' },
                    { data: 'member_no'},
                    { data: 'clearing_no' },
                    { data: 'action',sortable:false }
                ]
        });
        
        $('#laravel_datatable').on( 'error.dt', function ( e, settings, techNote, message ) {
            console.log( 'An error has been reported by DataTables: ', message );
        } ) .DataTable();
        $.fn.dataTable.ext.errMode = 'none';
        $("#bank-search-form").hide();
    }
</script>
@endsection