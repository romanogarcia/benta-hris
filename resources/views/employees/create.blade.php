@extends('layouts.master')
@section('title', 'Employees')
@section('content')
<div class="content-wrapper">

  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <div class="container-fluid bg-info py-2">
            <h4 class="card-title mb-0 text-white">Personal Information</h4>
          </div><br/>

          <form method="POST" action="{{ route('employees.store') }}" enctype="multipart/form-data" autocomplete="off">
            @csrf
                       
            <div class="row">
              <div class="col-md-4 col-sm-6">
                            
                <div class="form-group">
					 <label for="employee_number">Employee Image</label>
                  <input type="file" id="input-file-now" class="dropify @error('employee_image') is-invalid @enderror" name="employee_image" value="{{ old('employee_image') }}" accept='image/*'/>
                  @error('employee_image')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                  <!-- @if(isset($isImage)) -->
                  <!-- <img src="{{ asset('images/faces/' . $employee->employee_image) }}" alt="profile" class="img-lg mb-3 img-thumbnail"> -->
                  <!-- @else -->
                    <!-- @endif -->
					<input type="hidden" value="{{ Session::get('last_image') }}" />
                </div>
				 <div class="form-group">
				  	  @error('employee_image')
                        <span class="invalid-feedback" role="alert" style="display:block !important;">
                            <strong>{{ $message }}</strong>
                        </span>
                      @enderror
				  </div> 
              </div>
				
				<div class="col-md-4 col-sm-6">
				 <div class="form-group">
					 <label for="work_agreement">Work Agreement</label>
                  		<input type="file" id="work_agreement" class="dropify1 @error('work_agreement') is-invalid @enderror" name="work_agreement" />
				</div>
				 <div class="form-group">		 
                  @error('work_agreement')
                    <span class="invalid-feedback" role="alert"  style="display:block !important;">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
              </div>	
              <div class="col-md-4 col-sm-6">
				   <div class="form-group">
					 <label for="employee_cv">Employee CV</label>
                  		<input type="file" id="employee_cv" class="dropify2 @error('employee_cv') is-invalid @enderror" name="employee_cv"   />
					</div>
				 <div class="form-group">	   
                  @error('employee_cv')
                    <span class="invalid-feedback" role="alert"  style="display:block !important;">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
              </div>	
             
            </div><!-- /row -->
			<div class="row">
			 	<div class="col-md-4 col-sm-6">
                 <div class="form-group">
                  <label for="employee_number">Assigned Employee Number</label>
                    <input id="employee_number" type="text" class="form-control @error('employee_number') is-invalid @enderror form-control-sm" name="employee_number" value="00{{ $employee_number }}" style="letter-spacing:2px; font-weight: bold; background: #003366; color:white;" >
                    @error('employee_number')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                </div>
                <div class="form-group">
                  </div>
              </div>  
				<div class="col-md-4 col-sm-6">
					  <div class="form-group">
						  <label for="account_type">Account Type</label>
						  <select class="form-control form-control-sm" name="account_type">
							<option value="" disabled>Select Account Type</option>
							<option value="1" {{("1" == old('account_type') ? 'selected' : '' ) }}>Admin</option>
							<option value="0" {{("0" == old('account_type') ? 'selected' : '' ) }}>Employee</option>
						  </select>
					  </div>
				 </div>	 
			</div>
            <div class="row">
              <div class="col-md-4 col-sm-6">
                <div class="form-group">
                  <label for="last_name">Last Name <span class="required_field">*</span></label>
                    <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror form-control-sm" placeholder="Last Name" name="last_name" value="{{ old('last_name') }}" autocomplete off>
                    @error('last_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
              </div>

              <div class="col-md-4 col-sm-6">
                <div class="form-group">
                  <label for="first_name">First Name <span class="required_field">*</span></label>
                    <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror form-control-sm" placeholder="First Name" name="first_name" value="{{ old('first_name') }}" autocomplete="off">
                      @error('first_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                  </div>
              </div>

              <div class="col-md-4 col-sm-6">
                <div class="form-group">
                  <label for="middle_name">Middle Name</label>
                    <input id="middle_name" type="text" class="form-control @error('middle_name') is-invalid @enderror form-control-sm" placeholder="Middle Name" name="middle_name" value="{{ old('middle_name') }}" autocomplete="off">
                    @error('middle_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
              </div>
            </div><!-- /row -->
            
            <div class="row">
              <div class="col-md-4 col-sm-6">
                <div class="form-group">
                  <label for="gender">Gender</label> 
                  <select class="form-control form-control-sm" name="gender">
                    <option value="">Select Gender</option>
                    <option value="M" {{("M" == old('gender') ? 'selected' : '' ) }}>Male</option>
                    <option value="F" {{("F" == old('gender') ? 'selected' : '' ) }}>Female</option>
                  </select>
                </div>
              </div>

              <div class="col-md-4 col-sm-6">
                <div class="form-group">
                  <label for="birthdate">Birth Date</label>
                    <input type="text" data-date="" data-date-format="DD MMMM YYYY" value="{{ old('birthdate') }}"  class="form-control form-control-sm is_datefield" name="birthdate">
                    @error('birthdate')
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                </div>
              </div>

              <div class="col-md-4 col-sm-6">
                <div class="form-group">
                  <label for="civil_status">Civil Status</label>
                  <select class="form-control form-control-sm" name="civil_status">                
                    <option value="">Select Civil Status</option>                    
                    <option value="Single" {{("Single" == old('civil_status') ? 'selected' : '' ) }}>Single</option>
                    <option value="Married" {{("Married" == old('civil_status') ? 'selected' : '' ) }}>Married</option>
                    <option value="Separated" {{("Separated" == old('civil_status') ? 'selected' : '' ) }}>Separated</option>
                    <option value="Divorce" {{("Divorce" == old('civil_status') ? 'selected' : '' ) }}>Divorce</option>
                  </select>
                </div>
              </div>
            </div><!-- /row -->

            <div class="row">
              <div class="col-md-4 col-sm-6">
                <div class="form-group">
                  <label for="address">Address</label>
                    <input id="address" type="text" class="form-control @error('address') is-invalid @enderror form-control-sm" placeholder="Address" name="address" value="{{ old('address') }}" autocomplete="off">
                    @error('address')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
              </div>
              <div class="col-md-4 col-sm-6">
                <div class="form-group">
                  <label for="extra_address">Extra Address</label>
                    <input id="extra_address" type="text" class="form-control @error('extra_address') is-invalid @enderror form-control-sm" placeholder="Extra Address" name="extra_address" value="{{ old('extra_address') }}" autocomplete="off">
                    @error('extra_address')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
              </div>
              
              <div class="col-md-4 col-sm-6">
                <div class="form-group">
                  <label for="city">City</label>
                    <input id="city" type="text" class="form-control @error('city') is-invalid @enderror form-control-sm" placeholder="City" name="city" value="{{ old('city') }}" autocomplete="off">
                    @error('city')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
              </div>
              <div class="col-md-4 col-sm-6">
                <div class="form-group">
                  <label for="state">State/Province</label>
                    <input id="state" type="text" class="form-control @error('state') is-invalid @enderror form-control-sm" placeholder="State/Province" name="state" value="{{ old('state') }}" autocomplete="off">
                    @error('state')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
              </div>
              <div class="col-md-4 col-sm-6">
                <div class="form-group">
                  <label for="country">Country</label>
                  <select class="form-control form-control-sm  @error('country') is-invalid @enderror" name="country">                
                    <option value="">Select Country</option>   
                    @foreach($countries as $country)
                      <option value="{{$country->id}}" @if($country->id == old('country')) selected @endif>{{ucfirst($country->country_name)}}</option>
                    @endforeach                 
                  </select>
				    @error('country')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                    @enderror	
                </div>
              </div>
              <div class="col-md-4 col-sm-6">
                <div class="form-group">
                  <label for="zipcode">Zip Code</label>
                    <input id="zipcode" type="text" class="form-control @error('zipcode') is-invalid @enderror form-control-sm" placeholder="Zip Code" name="zipcode" value="{{ old('zipcode') }}" autocomplete="off">
                    @error('zipcode')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
              </div>
              
              
            </div>

            <div class="row">
              <div class="col-md-4 col-sm-6">
                <div class="form-group">
                  <label for="email">Email <span class="required_field">*</span></label>
                    <input id="email" type="text" class="form-control @error('email') is-invalid @enderror form-control-sm" placeholder="Email"  name="email" value="{{ old('email') }}" autocomplete="off">
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
              </div>
              <div class="col-md-4 col-sm-6">
                <div class="form-group">
                  <label for="personal_phone">Phone</label>
                    <input id="personal_phone" type="text" class="form-control @error('personal_phone') is-invalid @enderror form-control-sm" placeholder="Phone" name="personal_phone" value="{{ old('personal_phone') }}" autocomplete="off">
                    @error('personal_phone')
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                </div>
              </div>
            </div><!-- /row -->

          <div class="container-fluid bg-info py-2">
            <h4 class="card-title mb-0 text-white">Company Information <span class="accordian" target="company_information_block">&#x25B2;</span></h4>
			  
          </div><br/><br/>
			  <div class="company_information_block">
          <div class="row" >
              <div class="col-md-4 col-sm-6">
                <div class="form-group">
                  <label for="position">Position</label>
                    <input id="position" type="text" class="form-control @error('position') is-invalid @enderror form-control-sm" placeholder="Position" name="position" value="{{ old('position') }}" autocomplete="off">
                    @error('position')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
              </div>
              
              <div class="col-md-4 col-sm-6">
                <div class="form-group">
                  <label for="employment_status">Employment Status</label>
                    <select class="form-control form-control-sm" name="employment_status_id">
                      <option value="">Select Employment Status</option>                    
                      @foreach(tableDropdown('employment_statuses') as $key => $value)
                        <option value="{{$key}}" {{($key == old('employment_status_id') ? 'selected' : '' ) }} >{{$value}}</option>
                      @endforeach
                    </select>
                    @error('employment_status_id')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                </div>
              </div>

              <div class="col-md-4 col-sm-6">
                <div class="form-group">
                  <label for="date_hired">Date Hired</label>
                  <input type="text" data-date="" data-date-format="DD MMMM YYYY" value="{{ old('date_hired') }}" class="form-control form-control-sm is_datefield" name="date_hired" readonly>
                  @error('date_hired')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
              </div>
			</div>	  
			  <!-- /row -->
 
            <div class="row">
              <div class="col-md-4 col-sm-6">
                <div class="form-group">
                  <label for="basic_salary">Basic Salary Per Day</label>
                    <input id="basic_salary"  inputmode="numeric" onkeyup="send(event)" type="text" class="form-control @error('basic_salary') is-invalid @enderror form-control-sm" name="basic_salary" value="{{ old('basic_salary') }}" autocomplete="off" placeholder="0.00" style="letter-spacing: 1px;" step="any"> <label for="employee_number">Employee Image</label>
                    @error('basic_salary')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
              </div>
              <div class="col-md-4 col-sm-6">
                <div class="form-group">
                  <label for="tax_status">Tax Status</label>
                  <select class="form-control form-control-sm" name="tax_status">
                    <option value="">Select Tax Status</option>
                    <option value="SME1" {{('SME1' == old('tax_status') ? 'selected' : '' ) }}>SME1 - Single / Married</option>
                    <option value="SME2" {{('SME2' == old('tax_status') ? 'selected' : '' ) }}>SME2 - Single2 / Married2</option>
                    <option value="SME3" {{('SME3' == old('tax_status') ? 'selected' : '' ) }}>SME3 - Single3 / Married3</option>
                    <option value="SME4" {{('SME4' == old('tax_status') ? 'selected' : '' ) }}>SME4 - Single4 / Married4</option>
                  </select>
                  @error('tax_status')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
              </div>
              <div class="col-md-4 col-sm-6">
                <div class="form-group">
                  <label for="sss_contribution">Department</label>
                    <select class="form-control @error('department_id') is-invalid @enderror form-control-sm" name="department_id">
                        <option value="">Select Department</option>
                      @foreach(tableDropdown('departments') as $key => $value)
                        <option value="{{$key}}" {{($key == old('department_id') ? 'selected' : '' ) }}>{{$value}}</option>
                      @endforeach
                    </select>
                    @error('department_id')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                </div>
              </div>
            </div><!-- /row -->
          
            <div class="row">
              <div class="col-md-4 col-sm-6">
                <div class="form-group">
                  <label for="personal_allowance">Personal Allowance Per Day</label>
                    <input id="personal_allowance" type="number" class="form-control @error('personal_allowance') is-invalid @enderror form-control-sm" placeholder="₱ 100.00" name="personal_allowance" value="{{ old('personal_allowance') }}" autocomplete="off" data-type="currency" style="letter-spacing: 1px;" step="any" >
                    @error('personal_allowance')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
              </div>
              <div class="col-md-4 col-sm-6">
                <div class="form-group">
                  <label for="transportation_allowance">Transportation Allowance Per Day</label>
                    <input id="transportation_allowance" type="number" class="form-control @error('transportation_allowance') is-invalid @enderror form-control-sm" placeholder="₱ 100.00" name="transportation_allowance" value="{{ old('transportation_allowance') }}" autocomplete="off" data-type="currency" style="letter-spacing: 1px;" step="any">
                    @error('transportation_allowance')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
              </div>
              <div class="col-md-4 col-sm-6">
                <div class="form-group">
                  <label for="food_allowance">Food Allowance Per Day</label>
                    <input id="food_allowance" type="number" class="form-control @error('food_allowance') is-invalid @enderror form-control-sm" placeholder="₱ 100.00" name="food_allowance" value="{{ old('food_allowance') }}" autocomplete="off" data-type="currency" style="letter-spacing: 1px;" step="any">
                    @error('food_allowance')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
              </div>
            </div><!-- /row -->
            <div class="row">
              <div class="col-md-4 col-sm-6">
                <div class="form-group">
                  <label for="payment_schedule">Payment Schedule</label>
                  <select class="form-control @error('payment_schedule') is-invalid @enderror form-control-sm" name="payment_schedule">
                    <option value="">Select Payment Schedule</option>
                    <option value="monthly" {{('monthly' == old('payment_schedule') ? 'selected' : '' ) }}>Monthly</option>
                    <option value="semi-monthly" {{('semi-monthly' == old('payment_schedule') ? 'selected' : '' ) }}>Semi-monthly</option>
                  </select>
                  @error('payment_schedule')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>
            <div class="col-md-4 col-sm-6">
                <div class="form-group">
                  <label for="hmo">HMO</label>
                  <input id="hmo" type="number" class="form-control @error('hmo') is-invalid @enderror form-control-sm" name="hmo" value="{{ old('hmo') }}" autocomplete="off" placeholder="₱ 1,000.00" style="letter-spacing: 1px;" step="any">
                    @error('hmo')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
              </div>
            </div>
            
            <div class="row">
              <div class="col-md-4 col-sm-6">
                <div class="form-group">
                  <label for="vacation_leave">Number of Vacation Leave</label>
                    <input id="vacation_leave" type="number" class="form-control @error('vacation_leave') is-invalid @enderror form-control-sm" placeholder="Number of Vacation Leave" name="vacation_leave" value="{{ old('vacation_leave') }}" autocomplete="off">
                    @error('vacation_leave')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
              </div>
              <div class="col-md-4 col-sm-6">
                <div class="form-group">
                  <label for="sick_leave">Number of Sick Leave</label>
                    <input id="sick_leave" type="number" class="form-control @error('sick_leave') is-invalid @enderror form-control-sm" placeholder="Number of Sick Leave" name="sick_leave" value="{{ old('sick_leave') }}" autocomplete="off">
                    @error('sick_leave')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
              </div>
              <div class="col-md-4 col-sm-6">
                <div class="form-group">
                  <label for=""></label>
                </div>
              </div>
            </div><!-- /row -->
            </div>
            <div class="container-fluid bg-info py-2">
              <h4 class="card-title mb-0 text-white">Government Information <span class="accordian" target="government_information_block">&#x25B2;</span></h4>
            </div><br/><br/>
			<div class="government_information_block">
            <div class="row">              
              <div class="col-md-4 col-sm-6">
                <div class="form-group">
                  <label for="sss_number">SSS No.</label>
                  <input id="sss_number" type="text" class="form-control @error('sss_number') is-invalid @enderror form-control-sm" placeholder="SSS No." name="sss_number" value="{{ old('sss_number') }}" autocomplete="off">
                  @error('sss_number')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

              <div class="col-md-4 col-sm-6">
                <div class="form-group">
                  <label for="tin_number">TIN No.</label>
                  <input id="tin_number" type="text" class="form-control @error('tin_number') is-invalid @enderror form-control-sm" placeholder="TIN No." name="tin_number" value="{{ old('tin_number') }}" autocomplete="off">
                  @error('tin_number')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
              </div>

              <div class="col-md-4 col-sm-6">
                <div class="form-group">
                  <label for="pagibig_number">Pag-Ibig No.</label>
                    <input id="pagibig_number" type="text" step="any" class="form-control @error('pagibig_number') is-invalid @enderror form-control-sm" placeholder="Pag Ibig No." name="pagibig_number" value="{{ old('pagibig_number') }}" autocomplete="off">
                    @error('pagibig_number')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
              </div>
            </div><!-- /row -->

            <div class="row">              
              <div class="col-md-4 col-sm-6">
                <div class="form-group">
                  <label for="philhealth_number">Phil Health No.</label>
                  <input id="philhealth_number" type="text" class="form-control @error('philhealth_number') is-invalid @enderror form-control-sm" placeholder="Phil Health No." name="philhealth_number" value="{{ old('philhealth_number') }}" autocomplete="off">
                  @error('philhealth_number')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

              <div class="col-md-4 col-sm-6">
              <div class="form-group">
                <label for="number_of_dependents">Number of Dependents</label>
                <input id="number_of_dependents" type="text" class="form-control @error('number_of_dependents') is-invalid @enderror form-control-sm" placeholder="No. of dependents" name="number_of_dependents" value="{{ old('number_of_dependents') }}" autocomplete="off">
                @error('number_of_dependents')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
              </div>
              </div>

              <div class="col-md-4 col-sm-6">
              
              </div>
            </div><!-- /row -->

            <div class="row">              
              <div class="col-md-4 col-sm-6">
                <div class="form-group">
                  <label for="dependent1">1. Dependent Name</label>
                  <input id="dependent1" type="text" class="form-control @error('dependent1') is-invalid @enderror form-control-sm" placeholder="Name" name="dependent1" value="{{ old('dependent1') }}" autocomplete="off">
                  @error('dependent1')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

              <div class="col-md-4 col-sm-6">
              <div class="form-group">
                <label for="dependent1_bday">Birthday</label>
                <input type="text" data-date="" data-date-format="DD MMMM YYYY" value="{{ old('dependent1_bday') }}" class="form-control form-control-sm is_datefield" name="dependent1_bday">
                @error('dependent1_bday')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
              </div>

              <div class="col-md-4 col-sm-6">
              <div class="form-group">
                <label for="dependent1_rel">Relation</label>
                <input id="dependent1_rel" type="text" class="form-control @error('dependent1_rel') is-invalid @enderror form-control-sm" placeholder="Relation" name="dependent1_rel" value="{{ old('dependent1_rel') }}" autocomplete="off">
                @error('dependent1_rel')
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
              </div>
            </div><!-- /row -->

            <div class="row">              
              <div class="col-md-4 col-sm-6">
                <div class="form-group">
                  <label for="dependent2">2. Dependent Name</label>
                  <input id="dependent2" type="text" class="form-control @error('dependent2') is-invalid @enderror form-control-sm" placeholder="Name" name="dependent2" value="{{ old('dependent2') }}" autocomplete="off">
                  @error('dependent2')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

              <div class="col-md-4 col-sm-6">
              <div class="form-group">
                <label for="dependent2_bday">Birthday</label>
                <input type="text" data-date="" data-date-format="DD MMMM YYYY" value="{{ old('dependent2_bday') }}" class="form-control form-control-sm is_datefield" name="dependent2_bday">
                @error('dependent2_bday')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
              </div>

              <div class="col-md-4 col-sm-6">
              <div class="form-group">
                <label for="dependent2_rel">Relation</label>
                <input id="dependent2_rel" type="text" class="form-control @error('dependent2_rel') is-invalid @enderror form-control-sm" placeholder="Relation" name="dependent2_rel" value="{{ old('dependent2_rel') }}" autocomplete="off">
                @error('dependent2_rel')
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
              </div>
            </div><!-- /row -->

            <div class="row">              
              <div class="col-md-4 col-sm-6">
                <div class="form-group">
                  <label for="dependent3">3. Dependent Name</label>
                  <input id="dependent3" type="text" class="form-control @error('dependent3') is-invalid @enderror form-control-sm" placeholder="Name" name="dependent3" value="{{ old('dependent3') }}" autocomplete="off">
                  @error('dependent3')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
              </div>

              <div class="col-md-4 col-sm-6">
              <div class="form-group">
                <label for="dependent3_bday">Birthday</label>
                <input type="text" data-date="" data-date-format="DD MMMM YYYY" value="{{ old('dependent3_bday') }}" class="form-control form-control-sm is_datefield" name="dependent3_bday">
                @error('dependent3_bday')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
              </div>

              <div class="col-md-4 col-sm-6">
              <div class="form-group">
                <label for="dependent3_rel">Relation</label>
                <input id="dependent3_rel" type="text" class="form-control @error('dependent3_rel') is-invalid @enderror form-control-sm" placeholder="Relation" name="dependent3_rel" value="{{ old('dependent3_rel') }}" autocomplete="off">
                @error('dependent3_rel')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
              </div>
            </div><!-- /row -->

            <div class="row">              
              <div class="col-md-4 col-sm-6">
                <div class="form-group">
                  <label for="dependent4">4. Dependent Name</label>
                  <input id="dependent4" type="text" class="form-control @error('dependent4') is-invalid @enderror form-control-sm" placeholder="Name" name="dependent4" value="{{ old('dependent4') }}" autocomplete="off">
                  @error('dependent4')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
              </div>

              <div class="col-md-4 col-sm-6">
              <div class="form-group">
                <label for="dependent4_bday">Birthday</label>
                <input type="text" data-date="" data-date-format="DD MMMM YYYY" value="{{ old('dependent4_bday') }}" class="form-control form-control-sm is_datefield" name="dependent4_bday">
                @error('dependent4_bday')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
              </div>

              <div class="col-md-4 col-sm-6">
                <div class="form-group">
                <label for="dependent4_rel">Relation</label>
                <input id="dependent4_rel" type="text" class="form-control @error('dependent4_rel') is-invalid @enderror form-control-sm" placeholder="Relation" name="dependent4_rel" value="{{ old('dependent4_rel') }}" autocomplete="off">
                @error('dependent4_rel')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
              </div>
              
            </div><!-- /row -->
			</div>
            <div class="container-fluid bg-info py-2">
              <h4 class="card-title mb-0 text-white">Contact Information <span class="accordian" target="contact_information_block">&#x25B2;</span></h4>
            </div><br/><br/>
			<div class= "contact_information_block" >
            <div class="row">
              <div class="col-md-4 col-sm-6">
                <div class="form-group">
                  <label for="contact_emergency_name">Person to call, In Case of emergency</label>
                  <input id="contact_emergency_name" type="text" class="form-control @error('contact_emergency_name') is-invalid @enderror form-control-sm" placeholder="" name="contact_emergency_name" value="{{ old('contact_emergency_name') }}" autocomplete="off">
                  @error('contact_emergency_name')
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

              <div class="col-md-4 col-sm-6">
                <div class="form-group">
                  <label for="contact_emergency_rel">Relationship</label>
                  <input id="contact_emergency_rel" type="text" class="form-control @error('contact_emergency_rel') is-invalid @enderror form-control-sm" placeholder="Relationship" name="contact_emergency_rel" value="{{ old('contact_emergency_rel') }}" autocomplete="off">
                  @error('contact_emergency_rel')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
              </div>

              <div class="col-md-4 col-sm-6">
                
                <div class="form-group">
                  <label for="contact_emergency_phone">Contact Phone</label>
                  <input id="contact_emergency_phone" type="text" class="form-control @error('contact_emergency_phone') is-invalid @enderror form-control-sm" placeholder="Phone" name="contact_emergency_phone" value="{{ old('contact_emergency_phone') }}" autocomplete="off">
                  @error('contact_emergency_phone')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>
            </div><!-- /row -->
           
            <div class="row">              
              <div class="col-md-4 col-sm-6"><div class="form-group">
                  <label for="contact_emergency_addr">Contact Address</label>
                    <textarea name="contact_emergency_addr" class="form-control" id="contact_emergency_addr" rows="4" spellcheck="true" style="background: rgb(255, 255, 255) none repeat scroll 0% 0%; z-index: auto; position: relative; line-height: 14px; font-size: 14px; transition: none 0s ease 0s;">{{ old('contact_emergency_addr') }}</textarea>
                    @error('contact_emergency_addr')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
              </div>

              <div class="col-md-4 col-sm-6">
                
              </div>

              <div class="col-md-4 col-sm-6">
               
              </div>
                <div class="col-md-4 col-sm-6">
                <input class="currency-format" inputmode="numeric" onkeyup="send(event)" placeholder="0.00" type="hidden">
              </div>
            </div><!-- /row -->
			</div>

            <div class="container-fluid bg-info py-2">
              <h4 class="card-title mb-0 text-white">Bank Information <span class="accordian" target="bank_information_block">&#x25B2;</span></h4> 
            </div><br/><br/>
			<div class="bank_information_block"> 
            <div class="row">              
              <div class="col-md-4 col-sm-6">
                <div class="form-group">
                  <label for="bank_name">Bank Name</label>
                  <input id="bank_name" type="text" class="form-control @error('bank_name') is-invalid @enderror form-control-sm" placeholder="Bank Name" name="bank_name" value="{{ old('bank_name') }}" autocomplete="off">
                  @error('bank_name')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

              <div class="col-md-4 col-sm-6">
                <div class="form-group">
                  <label for="account_number">Account Number</label>
                  <input id="account_number" type="text" class="form-control @error('account_number') is-invalid @enderror form-control-sm" placeholder="Account Number" name="account_number" value="{{ old('account_number') }}" autocomplete="off">
                  @error('account_number')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
              </div>
              <div class="col-md-4 col-sm-6">
                <div class="form-group">
                  <label for="bank_member_no">Member No.</label>
                  <input id="bank_member_no" type="text" class="form-control @error('bank_member_no') is-invalid @enderror form-control-sm" placeholder="Member No." name="bank_member_no" value="{{ old('bank_member_no') }}" autocomplete="off">
                  @error('bank_member_no')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
              </div>

              <div class="col-md-4 col-sm-6">
                <div class="form-group">
                  <label for="bank_address">Address</label>
                  <input id="bank_address" type="text" class="form-control @error('bank_address') is-invalid @enderror form-control-sm" placeholder="Address" name="bank_address" value="{{ old('bank_address') }}" autocomplete="off">
                  @error('bank_address')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
              </div>

              <div class="col-md-4 col-sm-6">
                <div class="form-group">
                  <label for="bank_extra_address">Extra Address</label>
                  <input id="bank_extra_address" type="text" class="form-control @error('bank_extra_address') is-invalid @enderror form-control-sm" placeholder="Extra Address" name="bank_extra_address" value="{{ old('bank_extra_address') }}" autocomplete="off">
                  @error('bank_extra_address')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
              </div>

              <div class="col-md-4 col-sm-6">
                <div class="form-group">
                  <label for="bank_zipcode">Zipcode</label>
                  <input id="bank_zipcode" type="text" class="form-control @error('bank_zipcode') is-invalid @enderror form-control-sm" placeholder="Zipcode" name="bank_zipcode" value="{{ old('bank_zipcode') }}" autocomplete="off">
                  @error('bank_zipcode')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
              </div>

              <div class="col-md-4 col-sm-6">
                <div class="form-group">
                  <label for="bank_city">City</label>
                  <input id="bank_city" type="text" class="form-control @error('bank_city') is-invalid @enderror form-control-sm" placeholder="City" name="bank_city" value="{{ old('bank_city') }}" autocomplete="off">
                  @error('bank_city')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
              </div>

              <div class="col-md-4 col-sm-6">
                <div class="form-group">
                  <label for="bank_state">State</label>
                  <input id="bank_state" type="text" class="form-control @error('bank_state') is-invalid @enderror form-control-sm" placeholder="State" name="bank_state" value="{{ old('bank_state') }}" autocomplete="off">
                  @error('bank_state')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
              </div>
              
              <div class="col-md-4 col-sm-6">
                <div class="form-group">
                  <label for="bank_country">Country</label>
                  <select class="form-control form-control-sm  @error('bank_country') is-invalid @enderror" name="bank_country">                
                    <option value="">Select Country</option>   
                    @foreach($countries as $country)
                      <option value="{{$country->id}}" @if($country->id == old('bank_country_id')) selected @endif>{{ucfirst($country->country_name)}}</option>
                    @endforeach                 
                  </select>
                  @error('bank_country')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
              </div>

              <div class="col-md-4 col-sm-6">
                <div class="form-group">
                  <label for="bank_iban">IBAN</label>
                  <input id="bank_iban" type="text" class="form-control @error('bank_iban') is-invalid @enderror form-control-sm" placeholder="IBAN" name="bank_iban" value="{{ old('bank_iban') }}" autocomplete="off">
                  @error('bank_iban')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
              </div>

              <div class="col-md-4 col-sm-6">
                <div class="form-group">
                  <label for="bank_bic">BIC</label>
                  <input id="bank_bic" type="text" class="form-control @error('bank_bic') is-invalid @enderror form-control-sm" placeholder="BIC" name="bank_bic" value="{{ old('bank_bic') }}" autocomplete="off">
                  @error('bank_bic')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
              </div>

              <div class="col-md-4 col-sm-6">
                <div class="form-group">
                  <label for="bank_clearing_no">Clearing No.</label>
                  <input id="bank_clearing_no" type="text" class="form-control @error('bank_clearing_no') is-invalid @enderror form-control-sm" placeholder="Clearing No." name="bank_clearing_no" value="{{ old('clearing_no') }}" autocomplete="off">
                  @error('bank_clearing_no')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
              </div>
              

            </div>
			</div>
            <br>
            <br>
            <br>

            <div class="row">
              <div class="col-md-3 offset-md-4">
                <div class="row">
<!--                   <div class="col"> -->
<!--                     <a href="{{ route('employees.index') }}" class="btn btn-danger btn-sm btn-block"><i class="mdi mdi-backspace p-2"></i> CANCEL </a>  -->
<!--                   </div> -->
                  <div class="col">
                    <button type="submit" class="btn btn-success btn-sm btn-block"><i class="mdi mdi-account-plus p-2"></i> SUBMIT </button> 
                  </div>
                </div>
              </div>
            </div>
           
          </form>    
        </div>
      </div>
    </div>
  </div> 
</div>
@endsection

@section('customjs')
<script src="{{ asset('js/js/simple-mask-money.js') }}"></script>

<script type="text/javascript">
// Default configuration  
// const options = {
//   allowNegative: false,
//   negativeSignAfter: false,
//   prefix: '',
//   suffix: '',
//   fixed: true,
//   fractionDigits: 2,
//   decimalSeparator: '.',
//   thousandsSeparator: ',',
//   cursor: 'move'
// };

// // set mask on your input you can pass a querySelector or your input element and options
// let input = SimpleMaskMoney.setMask('.basic-salary', options);

// // Your send method
// send = (e) => {
//   if (e.key !== "Enter") return;
//   // This method return value of your input in format number to save in your database
//   console.log( input.formatToNumber() );
// }


// // Jquery Dependency

// $("input[data-type='currency']").on({
//     keyup: function() {
//       formatCurrency($(this));
//     },
//     blur: function() { 
//       formatCurrency($(this), "blur");
//     }
// });


// function formatNumber(n) {
//   // format number 1000000 to 1,234,567
//   return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
// }


// function formatCurrency(input, blur) {
//   // appends $ to value, validates decimal side
//   // and puts cursor back in right position.
  
//   // get input value
//   var input_val = input.val();
  
//   // don't validate empty input
//   if (input_val === "") { return; }
  
//   // original length
//   var original_len = input_val.length;

//   // initial caret position 
//   var caret_pos = input.prop("selectionStart");
    
//   // check for decimal
//   if (input_val.indexOf(".") >= 0) {

//     // get position of first decimal
//     // this prevents multiple decimals from
//     // being entered
//     var decimal_pos = input_val.indexOf(".");

//     // split number by decimal point
//     var left_side = input_val.substring(0, decimal_pos);
//     var right_side = input_val.substring(decimal_pos);

//     // add commas to left side of number
//     left_side = formatNumber(left_side);

//     // validate right side
//     right_side = formatNumber(right_side);
    
//     // On blur make sure 2 numbers after decimal
//     if (blur === "blur") {
//       right_side += "00";
//     }
    
//     // Limit decimal to only 2 digits
//     right_side = right_side.substring(0, 2);

//     // join number by .
//     input_val = "$" + left_side + "." + right_side;

//   } else {
//     // no decimal entered
//     // add commas to number
//     // remove all non-digits
//     input_val = formatNumber(input_val);
//     input_val = "$" + input_val;
    
//     // final formatting
//     if (blur === "blur") {
//       input_val += ".00";
//     }
//   }
  
//   // send updated string to input
//   input.val(input_val);

//   // put caret back in the right position
//   var updated_len = input_val.length;
//   caret_pos = updated_len - original_len + caret_pos;
//   input[0].setSelectionRange(caret_pos, caret_pos);
// }



$(document).ready(function(){
	$('span.accordian').click(function() {
		var target = $(this).attr('target');
		$('.'+target).slideToggle("fast");
		$(this).toggleClass('active');
		if($(this).hasClass('active')){
			$(this).html('&#x25BC;');
		}
		else{
			$(this).html('&#x25B2;');
		}
	});
 // $('.dropify').attr("data-default-file", "{{ asset('images/default-user.png') }}");
  $('.dropify').attr("data-default-file", "{{ (Session::get('last_image'))?Session::get('last_image'):asset('images/default-user.png') }}");
  $('.dropify1').attr("data-default-file", "{{ asset('images/doc.png') }}");
  $('.dropify2').attr("data-default-file", "{{ asset('images/doc.png') }}");	
  // Basic
  $('.dropify').dropify();
  $('.dropify1').dropify();
  $('.dropify2').dropify();

  // Translated
  $('.dropify-fr').dropify({
      messages: {
      default: 'Drag and drop a file here or click',
      replace: 'Drag and drop or click to replace',
      remove:  'Remove',
      error:   'Ooops, something wrong happended.'
      }
  });

  // Used events
  var drEvent = $('#input-file-events').dropify();

  drEvent.on('dropify.beforeClear', function(event, element){
      return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
  });

  drEvent.on('dropify.afterClear', function(event, element){
      alert('File deleted');
  });

  drEvent.on('dropify.errors', function(event, element){
      console.log('Has Errors');
  });

  var drDestroy = $('#input-file-to-destroy').dropify();
  drDestroy = drDestroy.data('dropify')
  $('#toggleDropify').on('click', function(e){
      e.preventDefault();
      if (drDestroy.isDropified()) {
          drDestroy.destroy();
      } else {
          drDestroy.init();
      }
  })

	  
	});
</script>
@endsection
