  <div class="row">
    <div class="col-md-3 grid-margin stretch-card">
      <a href="{{ route('emp.list') }}" alt="employees-list" style="text-decoration:none" >
      <div class="card">
        <div class="card-body bg-primary">
          <h4 class="card-title">{{ $data['total_employees'] }}</h4>
          <div class="media">
            <i class="mdi mdi-account-multiple icon-md text-info d-flex align-self-start mr-3 icon-employees"></i>
            <div class="media-body">
              <p class="card-text">TOTAL EMPLOYEES</p>
            </div>
          </div>
        </div>
      </div>
      </a>
    </div>

    <div class="col-md-3 grid-margin stretch-card">
      <a href="{{ route('att.list') }}" style="text-decoration:none" >
      <div class="card">
        <div class="card-body bg-success">
          <h4 class="card-title">{{ $data['attendance_today'] }}%</h4>
          <div class="media">
            <i class="mdi mdi-account-multiple-plus icon-md text-info d-flex align-self-start mr-3 icon-employees"></i>
            <div class="media-body">
              <p class="card-text">ATTENDANCE TODAY</p>
            </div>
          </div>
        </div>
      </div>
      </a>
    </div>

    <div class="col-md-3 grid-margin stretch-card">
      <a href="{{ route('dtr.absences') }}" style="text-decoration:none">
      <div class="card">
        <div class="card-body bg-danger">
          <h4 class="card-title">{{ $data['absents_today'] }}%</h4>
          <div class="media">
            <i class="mdi mdi-account-multiple-minus icon-md text-info d-flex align-self-start mr-3 icon-employees"></i>
            <div class="media-body">
              <p class="card-text">ABSENTS TODAY</p>
            </div>
          </div>
        </div>
      </div>
      </a>
    </div>
    <div class="col-md-3 grid-margin stretch-card">
      <a href="{{ route('leave.leavesearch')}}?scheds=leaves" style="text-decoration:none">
      <div class="card">
        <div class="card-body bg-twitter">
          <h4 class="card-title">{{ $data['employee_schedule_vacation'] }}</h4>
          <div class="media">
            <i class="mdi mdi-airplane-takeoff icon-md text-info d-flex align-self-start mr-3 icon-employees"></i>
            <div class="media-body">
              <p class="card-text">EMPLOYEES SCHEDULE VACATION</p>
            </div>
          </div>
        </div>
      </div>
      </a>
    </div>
  </div><!-- /row -->
