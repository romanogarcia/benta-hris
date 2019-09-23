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
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <i class="mdi mdi-alert-circle"></i>
                                    <strong>{{ $error }}</strong>
                                @endforeach
                            </div>
                        @endif
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success" role="alert">
                                <i class="mdi mdi-alert-circle"></i>
                                <strong>{{ $message }}</strong>
                            </div>
                    @endif
                    <!-- <form action="/employees/searchnow" method="post">
            @csrf -->
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
                        <div class="row" id="employee-search-form">
                            <div class="col-md-4">
                                <label class="">Employee Number</label>
                                <div class="form-group">
                                    <input id="emp_id" class="form-control form-control-sm" type="text" name="employee_number" placeholder="Employee Number" value="<?php echo (isset($filterdata['employee_number']))?$filterdata['employee_number']:""; ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="">First Name</label>
                                <div class="form-group">
                                    <input id="fname" class="form-control form-control-sm" type="text" name="first_name" placeholder="First name" value="<?php echo (isset($filterdata['first_name']))?$filterdata['first_name']:""; ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="">Last Name</label>
                                <div class="form-group">
                                    <input id="lname" class="form-control form-control-sm" type="text" name="last_name" placeholder="Last name" value="<?php echo (isset($filterdata['last_name']))?$filterdata['last_name']:""; ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="">Employment Status</label>
                                <div class="form-group">
                                    <select class="form-control form-control-sm" name="employment_status_id" id="emp_stat_id">
                                        <option value="">Employement Status</option>
                                        @foreach(tableDropdown('employment_statuses') as $key => $value)
                                            <?php $selected = (isset($filterdata['employment_status_id']) && $filterdata['employment_status_id'] == $value)?"selected":""; ?>
                                            <option value="{{$key}}" {{$selected}} >{{$value}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="">Department</label>
                                <div class="form-group">
                                    <select class="form-control form-control-sm" name="department_id" id="dep_id">
                                        <option value="">Department</option>
                                        @foreach(tableDropdown('departments') as $key => $value)
                                            <?php $selected = (isset($filterdata['department_id']) && $filterdata['department_id'] == $value)?"selected":""; ?>
                                            <option value="{{$key}}" >{{$value}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="l">Date Hired</label>
                                <div class="form-group">
                                    <!-- <input type="text"  class="form-control form-control-sm" name="date_range_pick" id="date_range_pick" placeholder="Date Hired Range">-->
                                    <div class="">
                                        <input id='date_hired' type="text" data-date-format="DD MMMM YYYY" class="form-control form-control-sm is_datefield" name="date_hired" value="<?php echo (isset($filterdata['date_hired']))?$filterdata['date_hired']:""; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="form-check">
                                                <label class="form-check-label text-muted">
                                                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" <?php echo (isset($filterdata['is_active']) && $filterdata['is_active'] == 1)?"checked":""; ?>>Is Active
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="form-check">
                                                <label class="form-check-label text-muted">
                                                    <input class="form-check-input" type="checkbox" name="not_active" id="not_active" <?php echo (isset($filterdata['is_active']) && $filterdata['is_active'] == 0)?"checked":""; ?>>Not Active
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 "></div>

                            <div class="col-md-2">

                                <div class="form-group text-right">
                                    <button type="button" id="searchEmployee" class="btn btn-primary btn-icon-text btn-sm">
                                        <i class="mdi mdi-magnify"></i>
                                        Search
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- </form> -->
                        <p class="card-description">
                        </p>
                        <div class="table-responsive" id="laravel_datatables_container" style="display: none;">
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
           // $('#laravel_datatable').DataTable().draw(true);



            $('#searchEmployee').on('click', function (){
                load_data_tables();
                //$('#laravel_datatable').DataTable().draw(true);
            });
            /*$('#searchEmployee').on("click",function(){
                alert('aaaaaa');
                $("#employee-search-form").hide();
                $("#laravel_datatable").show();
                load_data_tables();
                $('#laravel_datatable').DataTable().draw(true);
            });*/
        });

        function load_data_tables(postbtn){
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
                        f.department_id = $('#dep_id').val();
                        f.employment_status_id = $('#emp_stat_id').val();
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
                        f.length = $('#laravel_datatable_length').val();
                        f.postbtn = postbtn;
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

            /* if(window.matchMedia("(max-width: 992px)").matches){
                 $("#laravel_datatables_container").css("margin-top", "0px");
             }else{
                 $("#laravel_datatables_container").css("margin-top", "-80px");
             }*/
        }
    </script>
@endsection