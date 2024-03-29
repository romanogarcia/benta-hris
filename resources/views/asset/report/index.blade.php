@extends('layouts.master')
@section('title', 'Asset Inventory')
@section('content')
<div class="content-wrapper">
  <div class="content">
    <div class="card">
      <div class="card-header">
        Asset Inventory Report
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
        <div class="row" id="search_form">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="employee">Accountable Employee</label>
                                    <div class="input-group">
                                        <select style="border: 1px solid #aeaeae;" class="form-control" name="employee" id="employee">
                                            <option value="">-Employee-</option>
                                                @foreach($employees as $employee)
                                                    <option @if($employee->id == old('employee')|| $employee->id == $employee->employee_id) selected @endif value="{{$employee->id}}">{{ucfirst($employee->first_name)}} {{ucfirst($employee->last_name)}}</option>
                                                @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="location">Location</label>
                                    <div class="input-group">
                                        <select style="border: 1px solid #aeaeae;" class="form-control" name="location" id="location">
                                            <option value="">-Location-</option>
                                                @foreach($locations as $location)
                                                    <option @if($location->id == old('location')|| $location->id == $location->location_id) selected @endif value="{{$location->id}}">{{ucfirst($location->location)}}</option>
                                                @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="property_no">Property No.</label>
                                    <input type="text" class="form-control" name="property_no" id="property_no" placeholder="Property No.">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="employee">Category</label>
                                    <div class="input-group">
                                        <select style="border: 1px solid #aeaeae;" class="form-control" name="category" id="category">
                                            <option value="">-Category-</option>
                                            @foreach($categories as $category)
                                                <option @if($category->id == old('category') || $category->id == $category->category_id) selected @endif value="{{$category->id}}">{{ucfirst($category->category)}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="employee">Supplier</label>
                                    <div class="input-group">
                                        <select style="border: 1px solid #aeaeae;" class="form-control" name="supplier" id="supplier">
                                            <option value="">-Supplier-</option>
                                            @foreach($suppliers as $supplier)
                                                <option @if($supplier->id == old('supplier')|| $supplier->id == $supplier->supplier_id) selected @endif value="{{$supplier->id}}">{{ucfirst($supplier->supplier)}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="date_range_pick">Date Range</label>
                                    <input type="text" class="form-control" name="date_range_pick" id="date_range_pick" placeholder="From - To" readonly="true">
                                </div>
                            </div>
                            <div class="col-md-4">
                            
                            </div>
                            <div class="col-md-4">
                              
                            </div>
                            <div class="col-md-4" align="right">
                            <div class="form-group" >
                                <button type="button" id="search-btn" class="btn btn-primary btn-icon-text btn-sm">
                                <i class="mdi mdi-magnify"></i>                                                    
                                Search
                                </button>
                               </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3" align="left" id="import-btn" style="display:none">
                            <div class="form-group" >
                                <a style="position: relative; z-index: 999;" href="javascript:void(0);" class="btn btn-success btn-icon-text btn-block " data-toggle="modal" data-target="#ExportModal"><i class="mdi mdi-arrow-down-bold-circle-outline"></i>Export</a>
                            </div>
                        </div>
    <div class="table-responsive" id="search_result_container" style="display:none">
        <table id="id-data_table" class="table">
            
          <thead>
            <tr>
              <th>Property No.</th>
              <th>Item Description</th>
              <th>Acquisition Cost</th>
              <th>Date Acquired</th>
              <th>Location</th>
              <th>Condition</th>
              <th>Employee</th>
            </tr>
          </thead>
          <tbody>
            
          </tbody>
        </table>
      </div>

    </div>
  </div>
</div>
</div>

<!-- modal -->

<div id="ExportModal" class="modal fade text-default" role="dialog">
        <div class="modal-dialog ">
            <!-- Modal content-->
            <form action="" id="exportform" method="post">
                <div class="modal-content">
                    <div class="modal-header bg-default">

                        <h4 class="modal-title text-center">SELECT EXPORT TYPE </h4>
                    </div>
                    <div class="modal-body">
						<select name="export_type" id="export_type" class="form-control form-control-sm">
							<option value="excel">Excel</option>
							<option value="pdf">PDF</option>
						</select>
                        <p class="text-center">
                        </p><center>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                            <button type="button" name="" id="btn-import" class="btn btn-success" data-dismiss="modal">Export</button>
                        </center>
                        <p></p>
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
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
        $('#date_range_pick[readonly]').css({'background-color':'#FFFFFF'});
        $('#date_range_pick').daterangepicker({
            showDropdowns: true,
            minYear: 1980,
            maxYear: parseInt(moment().format('YYYY')),
            locale: {
                cancelLabel: 'Clear'
            },
            "autoUpdateInput": false,
            "autoApply":false,
            // maxDate:moment(),
        });

        $('#date_range_pick').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
        });

        $('#date_range_pick').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });
        function load_datatable(){
          $("#search_result_container").show();
            $('#id-data_table').DataTable({
                searching: false,
                processing: true,
                serverSide: true,
                responsive: true,
                autoWidth : false,
                order: [[0, 'asc'],[1, 'asc']],
                dom   : "<'row'<'col-sm-12 float-right margin-bottom20'B>><'row'<'col-sm-12 col-xs-12 text-right'l>><'row'<'col-sm-12'tr>><'row'<'col-sm-5 col-xs-6'i><'col-sm-7 col-xs-6'p>>",
                ajax: {
                    url : "{{ route('asset_inventory.search_filter') }}",
                    type: 'GET',
                    data: function (f) {
                        f.employee_id       = $("#employee").val();
                        f.location_id       = $("#location").val();
                        f.property_number   = $("#property_no").val();
                        f.category_id       = $("#category").val();
                        f.supplier_id       = $("#supplier").val();
                        f.date_range_pick   = $("#date_range_pick").val();
                    }
                },
                columns: [
                    { data: 'property_no', sortable:true },
                    { data: 'item_description', sortable:true },
                    { data: 'acquisition_cost', sortable:false },
                    { data: 'date_acquired', sortable:true },
                    { data: 'location', sortable:true },
                    { data: 'condition', sortable:true },
                    { data: 'employee', sortable:true },   
                ],
            });
            
            $("#search_form_container").hide();
            if(window.matchMedia("(max-width: 992px)").matches){
                $("#search_result_container").css("margin-top", "0px");
            }else{
                $("#search_result_container").css("margin-top", "-70px");
            }

            $("#id-data_table").on('click', '.btn-delete_row', function (){
                var id = $(this).data('id');
                var url = '{{ route("asset_inventory.destroy", ":id") }}';
                url = url.replace(':id', id);
                $("#DeleteModal").find('.delete-alert').html('Do you want to remove <span class="badge badge-primary">'+id+'</span> ? This inventory can\'t be restored anymore.');
                $("#deleteForm").attr('action', url);
            });

            $("#btn-submit_delete_row").on('click', function (){
                $("#deleteForm").submit();
            });

        }

        $('#btn-import').on('click',function(){
            var query = {
                    export_type: $("#export_type").val(),
                    employee_id: $("#employee").val(),
                    location_id: $("#location").val(),
                    property_number : $('#property_no').val(),
                    category_id :$('#category').val(),
                    supplier_id :$('#supplier').val(),
                    date_range_pick : $('#date_range_pick').val(),
            }
	    var url = "{{route('asset_report.inventory')}}?" + $.param(query)

	    window.location = url;
        });
        
        
        $('#search-btn').on('click', function (){
            load_datatable();
            $("#search_form").hide();
            $("#import-btn").show();
            $('#id-data_table').DataTable().draw(true);
            });

    
    });
    
</script>
@endsection