  <div class="row">
    <div class="col-md-3 grid-margin stretch-card">
      <div class="card">
        <div class="card-body bg-primary">
          <h4 class="card-title">{{ $data['total_employees'] }}</h4>
          <div class="media">
            <i class="mdi mdi-account-multiple icon-md text-info d-flex align-self-start mr-3 icon-employees"></i>
            <div class="media-body">
              <a href="{{ route('employees.index') }}" alt="employees-list" ><p class="card-text">TOTAL EMPLOYEES</p></a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-3 grid-margin stretch-card">
      <div class="card">
        <div class="card-body bg-success">
          <h4 class="card-title">{{ $data['attendance_today'] }}%</h4>
          <div class="media">
            <i class="mdi mdi-account-multiple-plus icon-md text-info d-flex align-self-start mr-3 icon-employees"></i>
            <div class="media-body">
              <a href="#" ><p class="card-text">ATTENDANCE TODAY</p></a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-3 grid-margin stretch-card">
      <div class="card">
        <div class="card-body bg-danger">
          <h4 class="card-title">{{ $data['absents_today'] }}%</h4>
          <div class="media">
            <i class="mdi mdi-account-multiple-minus icon-md text-info d-flex align-self-start mr-3 icon-employees"></i>
            <div class="media-body">
              <a href="#" ><p class="card-text">ABSENTS TODAY</p></a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-3 grid-margin stretch-card">
      <div class="card">
        <div class="card-body bg-twitter">
          <h4 class="card-title">{{ $data['employee_schedule_vacation'] }}</h4>
          <div class="media">
            <i class="mdi mdi-airplane-takeoff icon-md text-info d-flex align-self-start mr-3 icon-employees"></i>
            <div class="media-body">
              <a href="#" ><p class="card-text">EMPLOYEES SCHEDULE VACATION</p></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div><!-- /row -->
