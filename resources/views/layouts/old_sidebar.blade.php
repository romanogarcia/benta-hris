<style>
	.menu_hidden{
	  color: currentColor;
	  cursor: not-allowed;
	  opacity: 0.5;
	  text-decoration: none;	
	  pointer-events: none;
	  display :none;	
	}
</style>
<!-- partial:partials/_sidebar.html -->
      <nav class="sidebar sidebar-offcanvas" id="sidebar" style="z-index: 1000;">
        <ul class="nav">
		@if(Auth::user()->employee->department_id!='' && Auth::user()->employee->department_id!=NULL)	
          <li class="nav-item  {{check_pages_for_department(Auth::user()->employee->department_id,['Home'])}}">
            <a class="nav-link" href="{{ route('home') }}">
              <i class="mdi mdi-home menu-icon"></i>
              <span class="menu-title">Dashboard</span>
            </a>
          </li>
          <li class="nav-item {{check_pages_for_department(Auth::user()->employee->department_id,['Employee','Attendance','Leave','OvertimeRequest'])}}">
            <a class="nav-link colla" data-toggle="collapse" href="#management" aria-expanded="false" aria-controls="management">
              <i class="mdi mdi-account-settings menu-icon"></i>
              <span class="menu-title">Management</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="management">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item {{check_pages_for_department(Auth::user()->employee->department_id,['Employee'])}}"> <a class="nav-link" href="{{ route('employees.index') }}"><i class="mdi mdi-account-tie"></i> &nbsp;&nbsp; Employees </a></li>
                <li class="nav-item {{check_pages_for_department(Auth::user()->employee->department_id,['Attendance'])}}"> <a class="nav-link" href="{{ route('attendance.index') }}"><i class="mdi mdi-account-badge"></i> &nbsp;&nbsp; Attendance</a></li>
				 <li class="nav-item {{check_pages_for_department(Auth::user()->employee->department_id,['Leave'])}}"> <a class="nav-link" href="{{ route('leave.leavesearch')}}"><i class="mdi mdi-file-search-outline"></i> &nbsp;&nbsp; Leave Request</a></li>  
                <li class="nav-item {{check_pages_for_department(Auth::user()->employee->department_id,['OvertimeRequest'])}} "> <a class="nav-link" href="{{ route('overtime_request.search')}}"><i class="mdi mdi-file-document-edit-outline"></i> &nbsp;&nbsp;Overtime Request</a></li>
              </ul>
            </div>
          </li>
		@if(!Auth::user()->payroll_deactivate)	
          <li class="nav-item {{check_pages_for_department(Auth::user()->employee->department_id,['Payroll','PayrollLedger'])}}">
            <a class="nav-link" data-toggle="collapse" href="#payroll" aria-expanded="false" aria-controls="payroll">
              <i class="mdi mdi-cash-multiple menu-icon"></i>
              <span class="menu-title">Payroll</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="payroll">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item {{check_pages_for_department(Auth::user()->employee->department_id,['PayrollLedger'])}}"> <a class="nav-link" href="{{ route('payrollledger.create') }}"><i class="mdi mdi-view-headline"></i> &nbsp;&nbsp; Generate Payroll </a></li>
                <li class="nav-item  {{check_pages_for_department(Auth::user()->employee->department_id,['Payroll'])}}"> <a class="nav-link" href="{{ route('payroll.search') }}"><i class="mdi mdi-file-search-outline"></i> &nbsp;&nbsp; Search Payroll </a></li>
                <li class="nav-item  {{check_pages_for_department(Auth::user()->employee->department_id,['Payroll'])}}"> <a class="nav-link" href="{{ route('payroll.index') }}"><i class="mdi mdi-cash-usd"></i> &nbsp;&nbsp; Bulk Payroll </a></li>
              </ul>
            </div>
          </li>
         @endif 
          <li class="nav-item {{check_pages_for_department(Auth::user()->employee->department_id,['User','Role'])}}">
            <a class="nav-link" data-toggle="collapse" href="#management-role" aria-expanded="false" aria-controls="management-role">
              <i class="mdi mdi-account-settings menu-icon"></i>
              <span class="menu-title">Access Management</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="management-role">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item <?php echo check_pages_for_department(Auth::user()->employee->department_id,['User']) ?> "> <a class="nav-link" href="{{ route('users.index') }}"><i class="mdi mdi-account-key" ></i>  &nbsp;&nbsp; User Management </a></li>
                <li class="nav-item <?php echo check_pages_for_department(Auth::user()->employee->department_id,['Role']) ?>"> <a class="nav-link" href="{{ route('roles.index') }}"><i class="mdi mdi-account-multiple-plus" ></i>  &nbsp;&nbsp; Role Management </a></li>
              </ul>
            </div>
          </li>
          <li class="nav-item {{check_pages_for_department(Auth::user()->employee->department_id,['Company','Department','Holiday','Leave','EmploymentStatus','Bank','SocialSecurity','Tax','Philhealth','Pagibig'])}}">
            <a class="nav-link" data-toggle="collapse" href="#settings" aria-expanded="false" aria-controls="settings">
              <i class="mdi mdi-settings menu-icon"></i>
              <span class="menu-title">Settings</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="settings">
              <ul class="nav flex-column sub-menu">
				<li class="nav-item <?php echo check_pages_for_department(Auth::user()->employee->department_id,['Company']) ?>"> <a class="nav-link" href="{{ route('company.index') }}"><i class="mdi  mdi-office-building" ></i>  &nbsp;&nbsp; My Company </a></li>
                <li class="nav-item <?php echo check_pages_for_department(Auth::user()->employee->department_id,['Department']) ?>"> <a class="nav-link" href="{{ route('departments.index') }}"><i class="mdi mdi-home-modern"></i>&nbsp;&nbsp; Departments </a></li>
				<li class="nav-item {{check_pages_for_department(Auth::user()->employee->department_id,['Holiday'])}}"> <a class="nav-link" href="{{ route('holidays.index') }}"><i class="mdi mdi-calendar-remove" ></i>  &nbsp;&nbsp;  Holidays </a></li> 
                <li class="nav-item {{check_pages_for_department(Auth::user()->employee->department_id,['Leave'])}}"> <a class="nav-link" href="{{ route('leave.leave_list') }}"><i class="mdi mdi-account-remove-outline"></i> &nbsp;&nbsp; Leaves </a></li>
                <li class="nav-item {{check_pages_for_department(Auth::user()->employee->department_id,['EmploymentStatus'])}}"> <a class="nav-link" href="{{ route('employment-status.index') }}"><i class="mdi mdi-clipboard-check-outline"></i>&nbsp;&nbsp; Employment Status </a></li>
                <li class="nav-item {{check_pages_for_department(Auth::user()->employee->department_id,['Bank'])}}"> <a class="nav-link" href="{{ route('banks.index') }}"><i class="mdi  mdi-bank" ></i>  &nbsp;&nbsp; Company Bank  </a></li>
				<li class="nav-item {{check_pages_for_department(Auth::user()->employee->department_id,['Bank'])}}" > <a class="nav-link" href="{{ route('banks.settings') }}"><i class="mdi mdi-settings" ></i>  &nbsp;&nbsp; Settings  </a></li>
				@if(!Auth::user()->payroll_deactivate)	  
                <li class="nav-item {{check_pages_for_department(Auth::user()->employee->department_id,['SocialSecurity'])}}"> <a class="nav-link" href="{{ route('sss.index') }}"><i class="mdi  mdi-table-large" ></i>  &nbsp;&nbsp; SSS  </a></li>
                <li class="nav-item {{check_pages_for_department(Auth::user()->employee->department_id,['Tax'])}}"> <a class="nav-link" href="{{ route('tax.index') }}"><i class="mdi  mdi-table-large" ></i>  &nbsp;&nbsp; Tax  </a></li>
                <li class="nav-item {{check_pages_for_department(Auth::user()->employee->department_id,['Philhealth'])}}"> <a class="nav-link" href="{{ route('philhealth.index') }}"><i class="mdi  mdi-table-large" ></i>  &nbsp;&nbsp; Philhealth  </a></li>
                <li class="nav-item {{check_pages_for_department(Auth::user()->employee->department_id,['Pagibig'])}}"> <a class="nav-link" href="{{ route('pagibig.index') }}"><i class="mdi  mdi-table-large" ></i>  &nbsp;&nbsp; Pag-Ibig  </a></li>
				@endif 
              </ul>
            </div>
          </li>
			
			<li class="nav-item  {{check_pages_for_department(Auth::user()->employee->department_id,['DailyTimeRecord'])}}">
				<a class="nav-link" href="{{ route('dtr.reports') }}">
				  <i class="mdi mdi-file-document menu-icon"></i>
				  <span class="menu-title">Reports</span>
				</a>
			 </li>
		@endif 	
        </ul>
      </nav>