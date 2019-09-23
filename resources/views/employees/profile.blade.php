@extends('layouts.master')
@section('title', 'Employees')
@section('content')
<div class="content-wrapper">
	<div class="row">
		<div class="col-12">
		  <div class="card">
		    <div class="card-body">
		      <div class="row">
		        <div class="col-lg-4">
		          <div class="border-bottom text-center pb-4">
		            <img src="{{get_profile_picture($employee->user_id)}}" alt="profile" class="img-lg rounded-circle mb-3">
		            <div class="mb-3">
		              <h3>{{ $employee->first_name }} {{ $employee->last_name }}</h3>
		              <div class="d-flex align-items-center justify-content-center">
		                <h5 class="mb-0 mr-2 text-muted">{{ $employee->position }}</h5>
		              </div>
		            </div>
		          </div>	         
		          <div class="py-4">
		            <p class="clearfix">
		              <span class="float-left">
		                Employee Number
		              </span>
		              <span class="float-right text-muted">
		                {{ $employee->employee_number }}
		              </span>
		            </p>
		            <p class="clearfix">
		              <span class="float-left">
		                Position
		              </span>
		              <span class="float-right text-muted">
		                {{ $employee->position }}
		              </span>
		            </p>
		            <p class="clearfix">
		              <span class="float-left">
		                Employment Status
		              </span>
		              <span class="float-right text-muted">
					  	@if($employee->employment_status_id != null)
						  {{ $employee->employment_status->name }}
						@endif                
		              </span>
		            </p>
		            <p class="clearfix">
		              <span class="float-left">
		                Department
		              </span>
		              <span class="float-right text-muted">
						@if($employee->department_id != null)
							{{ $employee->department->name }}
						@endif 
		              </span>
		            </p>
		            <p class="clearfix">
		              <span class="float-left">
		                Phone
		              </span>
		              <span class="float-right text-muted">
		                {{ $employee->home_phone }}
		              </span>
		            </p>
		            <p class="clearfix">
		              <span class="float-left">
		                e-mail
		              </span>
		              <span class="float-right text-muted">
		                {{ $employee->email }}
		              </span>
		            </p>
		            <p class="clearfix">
		              <span class="float-left">
		                Birthday
		              </span>
		              <span class="float-right text-muted">
		                {{ date(get_date_format(), strtotime($employee->birthdate)) }}
		              </span>
		            </p>
		            <p class="clearfix">
		              <span class="float-left">
		                Address
		              </span>
		              <span class="float-right text-muted">
		                {{ $employee->address }}
		              </span>
		            </p>
		          </div>
		          <a href="{{ URL::to('employees/' . $employee->id . '/edit') }}" class="btn btn-primary btn-block mb-2">Edit</a>
		        </div>
		        <div class="col-lg-8">
		          	<div class="d-block d-md-flex justify-content-between mt-4 mt-md-0">

						<div class="card">
			                <div class="card-body">
			                  <ul class="nav nav-tabs" role="tablist">
			                    <li class="nav-item">
			                      <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home-1" role="tab" aria-controls="home-1" aria-selected="true">Compensation Information</a>
			                    </li>
			                    <li class="nav-item">
			                      <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile-1" role="tab" aria-controls="profile-1" aria-selected="false">Government Information</a>
			                    </li>
			                    <li class="nav-item">
			                      <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact-1" role="tab" aria-controls="contact-1" aria-selected="false">Contact Information</a>
			                    </li>
			                  </ul>
			                  <div class="tab-content">
			                    <div class="tab-pane fade active show" id="home-1" role="tabpanel" aria-labelledby="home-tab">
									<div class="table-responsive">
					                    <table class="table table-hover table-bordered">
					                        <tr>
					                          <td>Basic Salary</td>
					                          <td style="letter-spacing: 1px;">{{ number_format($employee->basic_salary,2) }}</td>
					                        </tr>
					                        <tr>
					                          <td>Transportation Allowance /day</td>
					                          <td style="letter-spacing: 1px;">{{ number_format($employee->transportation_allowance,2) }}</td>
					                        </tr>
					                        <tr>
					                          <td>Meal Allowance /day</td>
					                          <td style="letter-spacing: 1px;">{{ number_format($employee->food_allowance,2) }}</td>
					                        </tr>
					                    </table>
					                 </div>			                    
			                    </div>
			                    <div class="tab-pane fade" id="profile-1" role="tabpanel" aria-labelledby="profile-tab">
									<div class="table-responsive">
					                    <table class="table table-hover table-bordered table-striped">
					                        <tr>
					                          <td>SSS No.</td>
					                          <td> {{ $employee->sss_number }} </td>
					                        </tr>
					                        <tr>
					                          <td>TIN No.</td>
					                          <td> {{ $employee->tin_number }} </td>
					                        </tr>
					                        <tr>
					                          <td>Pag Ibig No.</td>
					                          <td> {{ $employee->pagibig_number }} </td>
					                        </tr>
					                        <tr>
					                          <td>Phil Health No.</td>
					                          <td> {{ $employee->philhealth_number }} </td>
					                        </tr>
					                    </table>
					                 </div>				                     
			                     
			                    </div>
			                    <div class="tab-pane fade" id="contact-1" role="tabpanel" aria-labelledby="contact-tab">
									<div class="table-responsive">
					                    <table class="table table-hover table-bordered table-striped">
					                        <tr>
					                          <td>{{ $employee->contact_emergency_name }}</td>
					                          <td>{{ $employee->contact_emergency_rel }}</td>
					                          <td>{{ $employee->contact_emergency_phone }}</td>
					                        </tr>
					                        <tr>
					                          <td>ADDRESS</td>
					                          <td>2702 Tycoon Building, Pasig City, Philippines</td>
					                          <td></td>
					                        </tr>
					                    </table>
					                 </div>	
			                    </div>
			                  </div><!-- /tab-content -->
			                </div>
			                
			            </div><!-- /card -->
		          	</div>
		        </div><!-- /col-lg-8 -->

		      </div>
		    </div>
		  </div>
		</div>
	</div>
</div>
@endsection