@extends('layouts.master')
@section('title', 'Reports')
@section('content')
<div class="content-wrapper">
    <div class="content">
        @include('includes.messages')
        <div class="card ">
            <div class="card-header ">
            Reports

            </div>
			<div class="card-body">
				@if((check_permission(Auth::user()->Employee->department_id,"Reports","full")) || (check_permission(Auth::user()->Employee->department_id,"Reports","View")))
				<div class="table-responsive" id="table_container">
					<table class="table" id="id-data_table">
						<thead>
							<th>Report Name</th>
						</thead>
						<tbody>
							<?php $check=checkreports("Employee Attendance Report",Auth::user()->Employee->department_id);?>
							
							@if($check)
							<tr>
								<td><a  href="{{ route('dtr.history') }}" style="text-decoration: none;"><i class="mdi  mdi-clock" ></i>  &nbsp;&nbsp; Employee Attendance Report </a></td>
							</tr>
							@endif
							<?php $check=checkreports("Employee Tardiness Report",Auth::user()->Employee->department_id);?>
							@if($check)
							<tr>
								<td><a  href="{{ route('dtr.tardiness') }}" style="text-decoration: none;"><i class="mdi  mdi-calendar-month" ></i>  &nbsp;&nbsp; Employee Tardiness Report </a></td>
							</tr>
							@endif
							<?php $check=checkreports("Employee Absenses Report",Auth::user()->Employee->department_id);?>
							@if($check)
							<tr>
								<td><a  href="{{ route('dtr.absences') }}" style="text-decoration: none;"><i class="mdi mdi-calendar-plus" ></i> &nbsp;&nbsp; Employee Absenses Report </a></td>
							</tr>
							@endif
							<?php $check=checkreports("Payroll Report",Auth::user()->Employee->department_id);?>
							@if($check)
							<tr>
								<td><a  href="{{route('report.comming_soon')}}" style="text-decoration: none;"><i class="mdi mdi-calendar-plus" ></i> &nbsp;&nbsp; Payroll Report </a></td>
							</tr>
							@endif
							<?php $check=checkreports("Philippines: BIR 1601C Report",Auth::user()->Employee->department_id);?>
							@if($check)
							<tr>
								<td><a  href="{{route('report_bir_1601c.index')}}" style="text-decoration: none;"><i class="mdi mdi-calendar-plus" ></i> &nbsp;&nbsp; Philippines: BIR 1601C Report </a></td>
							</tr>
							@endif
							<?php $check=checkreports("Philippines: Payroll BPI Payroll Report",Auth::user()->Employee->department_id);?>
							@if($check)
							<tr>
								<td><a  href="{{route('report.comming_soon')}}" style="text-decoration: none;"><i class="mdi mdi-calendar-plus" ></i> &nbsp;&nbsp; Philippines: Payroll BPI Payroll Report </a></td>
							</tr>
							@endif
							<?php $check=checkreports("Philippines: Payroll BDO Payroll Report",Auth::user()->Employee->department_id);?>
							@if($check)
							<tr>
								<td><a  href="{{route('report.comming_soon')}}" style="text-decoration: none;"><i class="mdi mdi-calendar-plus" ></i> &nbsp;&nbsp; Philippines: Payroll BDO Payroll Report </a></td>
							</tr>
							@endif
							<?php $check=checkreports("Philippines: Payroll HSBC Payroll Report",Auth::user()->Employee->department_id);?>
							@if($check)
							<tr>
								<td><a  href="{{route('report.comming_soon')}}" style="text-decoration: none;"><i class="mdi mdi-calendar-plus" ></i> &nbsp;&nbsp; Philippines: Payroll HSBC Payroll Report </a></td>
							</tr>
							@endif
							<?php $check=checkreports("Philippines: Payroll Maybank Payroll Report",Auth::user()->Employee->department_id);?>
							@if($check)
							<tr>
								<td><a  href="{{route('report.comming_soon')}}" style="text-decoration: none;"><i class="mdi mdi-calendar-plus" ></i> &nbsp;&nbsp; Philippines: Payroll Maybank Payroll Report </a></td>
							</tr>
							@endif
							<?php $check=checkreports("Philippines: Pag-IBIG HQP-PFF-053 Report",Auth::user()->Employee->department_id);?>
							@if($check)
							<tr>
								<td><a  href="{{route('report.comming_soon')}}" style="text-decoration: none;"><i class="mdi mdi-calendar-plus" ></i> &nbsp;&nbsp; Philippines: Pag-IBIG HQP-PFF-053 Report </a></td>
							</tr>
							@endif
							<?php $check=checkreports("Philippines: Pag-IBIG Soft Copy Report",Auth::user()->Employee->department_id);?>
							@if($check)
							<tr>
								<td><a  href="{{route('report.comming_soon')}}" style="text-decoration: none;"><i class="mdi mdi-calendar-plus" ></i> &nbsp;&nbsp; Philippines: Pag-IBIG Soft Copy Report </a></td>
							</tr>
							@endif
							<?php $check=checkreports("Philippines: Philhealth Er2 Report",Auth::user()->Employee->department_id);?>
							@if($check)
							<tr>
								<td><a  href="{{route('report.comming_soon')}}" style="text-decoration: none;"><i class="mdi mdi-calendar-plus" ></i> &nbsp;&nbsp; Philippines: Philhealth Er2 Report </a></td>
							</tr>
							@endif
							<?php $check=checkreports("Philippines: Philhealth RF-1 Report",Auth::user()->Employee->department_id);?>
							@if($check)
							<tr>
								<td><a  href="{{route('report.comming_soon')}}" style="text-decoration: none;"><i class="mdi mdi-calendar-plus" ></i> &nbsp;&nbsp; Philippines: Philhealth RF-1 Report </a></td>
							</tr>
							@endif
							<?php $check=checkreports("Philippines: SSS R-1a Report",Auth::user()->Employee->department_id);?>
							@if($check)
							<tr>
								<td><a  href="{{route('report.comming_soon')}}" style="text-decoration: none;"><i class="mdi mdi-calendar-plus" ></i> &nbsp;&nbsp; Philippines: SSS R-1a Report </a></td>
							</tr>
							@endif
							<?php $check=checkreports("Philippines: SSS R-3 Report",Auth::user()->Employee->department_id);?>
							@if($check)
							<tr>
								<td><a  href="{{route('report.comming_soon')}}" style="text-decoration: none;"><i class="mdi mdi-calendar-plus" ></i> &nbsp;&nbsp; Philippines: SSS R-3 Report </a></td>
							</tr>
							@endif
							<?php $check=checkreports("Philippines: HMO Report",Auth::user()->Employee->department_id);?>
							@if($check)
							<tr>
								<td><a  href="{{route('report.comming_soon')}}" style="text-decoration: none;"><i class="mdi mdi-calendar-plus" ></i> &nbsp;&nbsp; Philippines: HMO Report </a></td>
							</tr>
							@endif
							<?php $check=checkreports("Payroll Register Report",Auth::user()->Employee->department_id);?>
							@if($check)
							<tr>
								<td><a  href="{{ route('dtr.payrolls') }}" style="text-decoration: none;"><i class="mdi  mdi-calendar-month" ></i> &nbsp;&nbsp; Payroll Register Report </a></td>
							</tr>
							@endif
							<?php $check=checkreports("Inventory Asset Report",Auth::user()->Employee->department_id);?>
							@if($check)
							<tr>
								<td><a  href="{{route('asset_report.index')}}" style="text-decoration: none;"><i class="mdi mdi-calendar-plus" ></i> &nbsp;&nbsp; Inventory Asset Report </a></td>
							</tr>
							@endif
						</tbody>
					</table>
				</div>	
				@endif
			</div>	
		</div>
	</div>
</div>
@endsection
@section('customjs')
@endsection