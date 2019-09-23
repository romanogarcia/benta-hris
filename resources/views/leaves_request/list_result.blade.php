@extends('layouts.master')
@section('title', 'Search Leave Request')
@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        Leave Request
                    </div>
                    <div class="card-body">
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success" role="alert">
                                <i class="mdi mdi-alert-circle"></i>
                                <strong>{{ $message }}</strong>
                            </div>
                        @endif
                        <div class="form-group">
                            <a style="position: relative; z-index: 999;" href="{{  route('leave.leavecreate') }}" class="btn btn-primary btn-icon-text btn-sm">
                                <i class="mdi mdi-plus"></i>
                                Request New Leave
                            </a>
                        </div>

                        <div class="table-responsive" id="search_result_container" style="display: none;">
                            <table class="table" id="id-data_table">
                                <thead>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Status</th>
                                <th>Approved By</th>
                                <th>From Date</th>
                                <th>To Date</th>
                                <th colspan="2">Action</th>
                                </thead>

                            </table>
                        </div>
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
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script type="text/javascript">

        var SITEURL = '{{URL::to('')}}';
        $(document).ready( function () {

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

            function load_datatable(){
                $("#search_result_container").show();
                $('#id-data_table').DataTable({
                    searching: false,
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    autoWidth : false,
                    dom   : "<'row'<'col-sm-12 float-right margin-bottom20'B>><'row'<'col-sm-12 col-xs-12 text-right'l>><'row'<'col-sm-12'tr>><'row'<'col-sm-5 col-xs-6'i><'col-sm-7 col-xs-6'p>>",
                    ajax: {
                        url : "{{ route('leave.leaves_search_filter') }}",
                        type: 'GET',
                        data: function (f) {
                            f.employee_id       = $('#employee_id').val();
                            f.date_range_pick   = $('#date_range_pick').val();
                            f.leave_type        = $('#leave_type').val();
                            f.state_status      = $('#state_status').val();
                            f.approved_by       = $('#approved_by').val();
                        }
                    },
                    columns: [
                        { data: 'id', sortable:true },
                        { data: 'name', sortable:true },
                        { data: 'state_status', sortable:true },
                        { data: 'approved_by', sortable:true },
                        { data: 'from_date', sortable:true },
                        { data: 'to_date', sortable:true },
                        { data: 'action', sortable:false }
                    ],
                });
                $('#id-data_table').on( 'error.dt', function ( e, settings, techNote, message ) {
                    console.log( 'An error has been reported by DataTables: ', message );
                } ) .DataTable();
                $.fn.dataTable.ext.errMode = 'none';
                $("#search_form_container").hide();
                if(window.matchMedia("(max-width: 992px)").matches){
                    $("#search_result_container").css("margin-top", "0px");
                }else{
                    $("#search_result_container").css("margin-top", "-70px");
                }

                $("#id-data_table").on('click', '.btn-delete_row', function (){
                    var id = $(this).data('id');
                    var url = '{{ route("leave.leavedestroy", ":id") }}';
                    url = url.replace(':id', id);
                    $("#DeleteModal").find('.delete-alert').html('Do you want to remove <span class="badge badge-primary">'+id+'</span> ? This leave request can\'t be restored anymore.');
                    $("#deleteForm").attr('action', url);
                });

                $("#btn-submit_delete_row").on('click', function (){
                    $("#deleteForm").submit();
                });
            }

            load_datatable();
            $('#id-data_table').DataTable().draw(true);
        });


    </script>
@endsection


