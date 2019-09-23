@extends('layouts.master')
@section('title', 'Scanned Barcode')
@section('content')
<div class="content-wrapper">
  <div class="content">
    <div class="card">
      <div class="card-header">
        Scanned Barcode
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

        <div class="form-group">
          <a style="position: relative; z-index: 999;" href="{{ route('asset_scanned_barcode.create') }}" class="btn btn-primary btn-icon-text btn-sm"><i class="mdi mdi-plus"></i> Barcode Entry</a>
        </div>

        <div id="search_form_container">
            <div class="row">
                <div class="col-sm-6 col-md-3">
                    <div class="form-group">
                        <input type="text" class="form-control form-control-sm" name="barcode" id="barcode" placeholder="Scanned Barcode">
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="form-group">
                        <select class="form-control form-control-sm" name="asset_location" id="asset_location">
                            <option value="">Scanned Location</option>
                            @foreach($locations as $location)
                                <option value="{{$location->id}}">{{ ucfirst($location->location) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="form-group">
                        <select class="form-control form-control-sm" name="status" id="status">
                            <option value="">Status</option>
                            <option value="found">Found</option>
                            <option value="not_found">Not Found</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="form-group">
                        <input type="text" readonly class="form-control form-control-sm" name="date_range_pick" id="date_range_pick" placeholder="Date From - To" value="<?php echo (isset($filterdata['date_range']))?$filterdata['date_range']:""; ?>">
                    </div>
                </div>
            </div>
            <div class="text-right">
                <a style="position: relative; z-index: 999;" href="javascript:void(0);" id="search-btn" class="btn btn-primary btn-icon-text btn-submit-search btn-sm">
                    <i class="mdi mdi-magnify"></i>                                                    
                    Search
                </a>
            </div>
        </div>

        <div class="table-responsive" id="search_result_container" style="display:none">
        
          <table id="id-data_table" class="table">
            <thead>
              <tr>
                <th>Scanned Barcode</th>
                <th>Scanned Location</th>
                <th>Status</th>
                <th>Added By</th>
                <th>Source</th>
                <th>Date Added</th>
                <th>Action</th>
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

<!-- Modal -->
<div class="modal fade" id="DeleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalCenterTitle">Are you sure?</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <span class="delete-alert"></span>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <form action="" id="deleteForm" method="post">
                @method('DELETE')
                @csrf
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        </div>
        </div>
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
                order: [[5, 'desc']],
                dom   : "<'row'<'col-sm-12 float-right margin-bottom20'B>><'row'<'col-sm-12 col-xs-12 text-right'l>><'row'<'col-sm-12'tr>><'row'<'col-sm-5 col-xs-6'i><'col-sm-7 col-xs-6'p>>",
                ajax: {
                    url : "{{ route('asset_scanned_barcode.search_filter') }}",
                    type: 'GET',
                    data: function (f) {
                        f.asset_location    = $("#asset_location").val();
                        f.barcode           = $("#barcode").val();
                        f.date_range_pick        = $("#date_range_pick").val();
                        f.status            = $("#status").val();
                    }
                },	  
                columns: [
                    { data: 'scanned_barcode', sortable:true },
                    { data: 'scanned_location', sortable:true },
                    { data: 'status', sortable:true },
                    { data: 'added_by', sortable:true },
                    { data: 'source', sortable:true },
                    { data: 'date_added', sortable:true },
                    { data: 'action', sortable:false },
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
                var url = '{{ route("asset_scanned_barcode.destroy", ":id") }}';
                url = url.replace(':id', id);
                $("#DeleteModal").find('.delete-alert').html('Do you want to remove <span class="badge badge-primary">'+id+'</span> ? This scanned barcode can\'t be restored anymore.');
                $("#deleteForm").attr('action', url);
            });

            $("#btn-submit_delete_row").on('click', function (){
                $("#deleteForm").submit();
            });

        }
        
        $('#search-btn').on('click', function (){
          load_datatable();
          $("#search_form").hide();
          $('#id-data_table').DataTable().draw(true);
        });

    });
    
</script>
@endsection