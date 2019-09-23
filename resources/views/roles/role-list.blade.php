@extends('layouts.master')
@section('title', 'Role Management')
@section('customcss')
<style>
  .table th, .table td{
    padding: 2px 2px 2px 10px;
  }
	    .onoffswitch {
        position: relative; width: 50px;
        -webkit-user-select:none; -moz-user-select:none; -ms-user-select: none;
    }
    .onoffswitch-checkbox {
        display: none;
    }
    .onoffswitch-label {
        display: block; overflow: hidden; cursor: pointer;
        border: 1px solid #999999; border-radius: 1px;
    }
    .onoffswitch-inner {
        display: block; width: 200%; margin-left: -100%;
        transition: margin 0.3s ease-in 0s;
    }
    .onoffswitch-inner:before, .onoffswitch-inner:after {
        display: block;
		float: left;
		width: 50%; 
		height: 1px;
		padding: 0;
        font-size: 10px;
    }
    .onoffswitch-inner:before {
        content: " ";
        padding-left: 5px;
        
		background-color: #000;
    }
    .onoffswitch-inner:after {
        content: " ";
        padding-right: 5px;
		background-color: #f2f2f2;
        
    }
    .onoffswitch-switch {
        display: block; 
		width: 20px; 
		margin: -10px;
        background: #FFFFFF;
        position: absolute; top: 0; bottom: 0;
        right: 50px;
        border: 2px solid #999999; border-radius: 50%;
        transition: all 0.3s ease-in 0s; 
    }
    .onoffswitch-checkbox:checked + .onoffswitch-label .onoffswitch-inner {
        margin-left: 0;
    }
    .onoffswitch-checkbox:checked + .onoffswitch-label .onoffswitch-switch {
        right: 0px; 
    }
	.permissiondiv {
		width: 100%;
		clear: both;
		display: block;
		padding: 10px;
		font-size: 14px;
	}
	.permissiondiv {
		margin: 5px 0px;
	}
	.permissiondiv > .permission-label {
		margin-top: -20px;
		margin-left: 80px;
	}
	h3 {
		font-size: 16px;
		font-weight: normal;
		margin: 15px 0px;
		text-transform:uppercase;
	}
	h4 {
		font-size: 14px;
		font-weight: normal;
		
		text-transform:uppercase;
	}
	li{
		list-style:none;
	}
	ul > li > label > .perfull {
		margin-left: 20px;
		font-size: 14px;
	}
	.active{
		color:#000;
	}
	.inactive{
		color:#0a0a0b80;
	}
	ul > li  >label > input[type="checkbox"]{
		 -webkit-transform: scale(1.3);
	}
	ul > li  >label{
		cursor:pointer;
	}
	#search_result_container > .btn {
		float: right;
	}
</style>
@endsection

@section('content')
<div class="content-wrapper">
  <div class="row">
    <div class="col-sm-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-header">
          Role List: <b>{{ucwords($department->name)}}</b>
        </div>
        <div class="card-body">
          
		<form action="{{route('role.modulepermission')}}" method="post">
			{{csrf_field()}}
          <div class="table-responsive" id="search_result_container">
			  <input type="hidden" value="{{$department->id}}" name="department_id">
			  <ul>
			  @foreach($pageroles as $pagerole)
				 <?php $child_parents =get_child_menu_role($pagerole->module_id);?>
				@if($child_parents != false)
					@if(count($child_parents)>0)
				  <li><h3>{{$pagerole->module_name}}</h3></li>
					 @foreach($child_parents as $child_parent)
				  		<?php $childpermissions=permission_get_child_menu($child_parent->id);?>
					  <ul>
						  <li><h4>{{$child_parent->module_name}}</h4>
							  <ul>
								   <?php $permissions=explode('|',$child_parent->permissions);?>
								  <?php $i=1;?>
									@foreach($permissions as $permission)
								  	<?php $modulename=str_replace(' ','',$child_parent->module_name);?>
								  <?php $per=strtolower($permission);?>
								  	 <?php $modulepermission =module_permission($department->id,$child_parent->id);?>
								  @if(($modulepermission != false) && (count($modulepermission)>0) && $modulepermission[0]->full)
								  	@if($permission=="full")
								  <li>
											<label><input type="checkbox" class="fullaccess" id="{{$modulename}}_full" name="{{$modulename}}[]" value="{{$permission}}" checked>	  
									  <span class="per{{$modulename}} perfull active">Open {{ucfirst($child_parent->module_name)}} Page</span></label>
								  </li>
								  	@else 
								  <li><label>
									  <div class="permissiondiv per{{$modulename}} per{{$permission}} active">
										  <div class="onoffswitch">
											<input type="checkbox" class="{{$modulename}} childcheckbox onoffswitch-checkbox" name="{{$modulename}}[]" id="{{$modulename}}{{$permission}}" value="{{$permission}}" checked>
											<label class="onoffswitch-label" for="{{$modulename}}{{$permission}}">
												<span class="onoffswitch-inner"></span>
												<span class="onoffswitch-switch"></span>
											</label>
										</div>
										<div class="permission-label">
										  <span class="per{{$modulename}} perfull">{{ucfirst(strtolower($permission))}} {{ucfirst(rtrim($child_parent->module_name,"s"))}}</span>
										 </div>
									</div></label>
								  </li>
									@endif
								  @elseif(($modulepermission != false) && (count($modulepermission)>0) && $modulepermission[0]->$per)
								  	@if($permission=="full")
								  <li><label>
											<input type="checkbox" class="fullaccess" id="{{$modulename}}_full" name="{{$modulename}}[]" value="{{$permission}}">	  
									  <span class="per{{$modulename}} perfull inactive">Open {{ucfirst($child_parent->module_name)}} Page</span>
									  </label></li>
								  	@elseif(($modulepermission[0]->$per) && ($i==1))
								  <li><label>
											<input type="checkbox" class="fullaccess" id="{{$modulename}}_full" name="{{$modulename}}[]" value="{{$permission}}" checked>	  
									  <span class="per{{$modulename}} perfull active">Open {{ucfirst($child_parent->module_name)}} Page</span>
									  </label></li>
								  @else
								  <li><label>
									  <div class="permissiondiv per{{$modulename}} per{{$permission}} active">
									  	<div class="onoffswitch">
											<input type="checkbox" class="{{$modulename}} childcheckbox onoffswitch-checkbox" name="{{$modulename}}[]" id="{{$modulename}}{{$permission}}" value="{{$permission}}" checked>
											<label class="onoffswitch-label" for="{{$modulename}}{{$permission}}">
												<span class="onoffswitch-inner"></span>
												<span class="onoffswitch-switch"></span>
											</label>
										</div>
										<div class="permission-label">
										  <span class="per{{$modulename}} perfull">{{ucfirst(strtolower($permission))}} {{ucfirst(rtrim($child_parent->module_name,"s"))}}</span>
										</div>
									  </div>
									  </label>
								</li>
									@endif
								  @else
								  	@if(($permission=="full") || ($i==1))
								  <li><label>
											<input type="checkbox" class="fullaccess" id="{{$modulename}}_full" name="{{$modulename}}[]" value="{{$permission}}">	  
									  <span class="per{{$modulename}} perfull inactive">Open {{ucfirst($child_parent->module_name)}} Page</span>
									  </label>
								  </li>
								  	@else 
								  <li>
									 <label>
									   <div class="permissiondiv per{{$modulename}} per{{$permission}} inactive">
										  <div class="onoffswitch">
											<input type="checkbox" class="{{$modulename}} childcheckbox onoffswitch-checkbox" name="{{$modulename}}[]" id="{{$modulename}}{{$permission}}" value="{{$permission}}">
											<label class="onoffswitch-label" for="{{$modulename}}{{$permission}}">
												<span class="onoffswitch-inner"></span>
												<span class="onoffswitch-switch"></span>
											</label>
										</div>
						 				 <div class="permission-label">
										  	<span class="per{{$modulename}} perfull">{{ucfirst(strtolower($permission))}} {{ucfirst(rtrim($child_parent->module_name,"s"))}}</span>
										  </div>
									  </div>
									  </label>
								  </li>
									@endif
								  @endif
								  <?php $i++;?>
									@endforeach
								  <?php $child_child_parents =get_child_child_menu_role($child_parent->module_id);?>
								  @if($child_child_parents != false)
								  	@if(count($child_child_parents)>0)
								  <ul>
								  		@foreach($child_child_parents as $child_child_parent)
									  <ul>
									   <li><h3>{{$child_child_parent->module_name}}</h3></li>
										  <ul>
										  	 <?php $permissions=explode('|',$child_child_parent->permissions);?>
											  <?php $i=1;?>
											  @foreach($permissions as $permission)
											  <?php $modulename=str_replace(' ','',$child_child_parent->module_name);?>
								  <?php $per=strtolower($permission);?>
								  	 <?php $modulepermission =module_permission($department->id,$child_child_parent->id);?>
											  @if(($modulepermission != false) && (count($modulepermission)>0) && $modulepermission[0]->full)
								  	@if($permission=="full")
								  <li>
											<label><input type="checkbox" class="fullaccess" id="{{$modulename}}_full" name="{{$modulename}}[]" value="{{$permission}}" checked>	  
									  <span class="per{{$modulename}} perfull active">Open {{ucfirst($child_child_parent->module_name)}} Page</span></label>
								  </li>
								  	@else 
								  <li><label>
									  <div class="permissiondiv per{{$modulename}} per{{$permission}} active">
										  <div class="onoffswitch">
											<input type="checkbox" class="{{$modulename}} childcheckbox onoffswitch-checkbox" name="{{$modulename}}[]" id="{{$modulename}}{{$permission}}" value="{{$permission}}" checked>
											<label class="onoffswitch-label" for="{{$modulename}}{{$permission}}">
												<span class="onoffswitch-inner"></span>
												<span class="onoffswitch-switch"></span>
											</label>
										</div>
										<div class="permission-label">
										  <span class="per{{$modulename}} perfull">{{ucfirst(strtolower($permission))}} {{ucfirst(rtrim($child_child_parent->module_name,"s"))}}</span>
										 </div>
									</div></label>
								  </li>
									@endif
											  
									@elseif(($modulepermission != false) && (count($modulepermission)>0) && $modulepermission[0]->$per)
							@if($permission=="full")
							<li><label>
											<input type="checkbox" class="fullaccess" id="{{$modulename}}_full" name="{{$modulename}}[]" value="{{$permission}}" checked>	  
									  <span class="per{{$modulename}} perfull inactive">Open {{ucfirst($child_child_parent->module_name)}} Page</span>
								</label>
								  </li>
						@elseif(($modulepermission[0]->$per) && ($i==1))
								  <li><label>
											<input type="checkbox" class="fullaccess" id="{{$modulename}}_full" name="{{$modulename}}[]" value="{{$permission}}" checked>	  
									  <span class="per{{$modulename}} perfull active">Open {{ucfirst($child_child_parent->module_name)}} Page</span>
									  </label></li>
						
							@else
							<li><label>
								<div class="permissiondiv per{{$modulename}} per{{$permission}} active">
								<div class="onoffswitch">
									<input type="checkbox" class="{{$modulename}} childcheckbox onoffswitch-checkbox" name="{{$modulename}}[]" id="{{$modulename}}{{$permission}}" value="{{$permission}}" checked>
									<label class="onoffswitch-label" for="{{$modulename}}{{$permission}}">
										<span class="onoffswitch-inner"></span>
										<span class="onoffswitch-switch"></span>
									</label>
								</div>
								<div class="permission-label">
										  <span class="per{{$modulename}} perfull">{{ucfirst(strtolower($permission))}} {{ucfirst(rtrim($child_child_parent->module_name,"s"))}}</span>
									</div>
								</div></label>
						</li>
							@endif
											  @else
							@if(($permission=="full") || ($i==1))
							<li><label>
											<input type="checkbox" class="fullaccess" id="{{$modulename}}_full" name="{{$modulename}}[]" value="{{$permission}}">	  
									  <span class="per{{$modulename}} perfull inactive">Open {{ucfirst($child_child_parent->module_name)}} Page</span>
								</label>
								  </li>
							@else
							<li><label>
								<div class="permissiondiv per{{$modulename}} per{{$permission}} inactive">
								<div class="onoffswitch">
									<input type="checkbox" class="{{$modulename}} childcheckbox onoffswitch-checkbox" name="{{$modulename}}[]" id="{{$modulename}}{{$permission}}" value="{{$permission}}">
									<label class="onoffswitch-label" for="{{$modulename}}{{$permission}}">
										<span class="onoffswitch-inner"></span>
										<span class="onoffswitch-switch"></span>
									</label>
								</div>
									<div class="permission-label">
											  <span class="per{{$modulename}} perfull">{{ucfirst(strtolower($permission))}} {{ucfirst(rtrim($child_child_parent->module_name,"s"))}}</span>
										</div>
									</div></label>
									</li>
							@endif
						@endif
											  <?php $i++;?>
											  @endforeach
										  </ul>
									  </ul>
								  			
								  		@endforeach
								  </ul>
								  	@endif
								  @endif
								  
							  </ul>
						  </li>
					  </ul>
				 	 @endforeach
				  	@else
				  <li><h3>{{$pagerole->module_name}}</h3></li>
				 	@endif
				  @elseif($pagerole->parent==0)
				  <li><h3>{{$pagerole->module_name}}</h3>
				  	<ul>
					<?php $permissions=explode('|',$pagerole->permissions);?>
					<?php $modulepermission =module_permission($department->id,$pagerole->id);?>
						<?php $i=1;?>
					@foreach($permissions as $permission)
						  <?php $per=strtolower($permission);?>
						<?php $modulename=str_replace(' ','',$pagerole->module_name);?>
						 @if(($modulepermission != false) && (count($modulepermission)>0) && $modulepermission[0]->full)
							@if($permission=="full")
							<li><label>
								<input type="checkbox" class="fullaccess" id="{{$modulename}}_full" name="{{$modulename}}[]" value="{{$permission}}" checked>	  
								<span class="per{{$modulename}} perfull active">Open {{ucfirst($pagerole->module_name)}} Page</span>
								</label>
								  </li>
							@else
							<li><label>
								<div class="permissiondiv per{{$modulename}} per{{$permission}} active">
								<div class="onoffswitch">
									<input type="checkbox" class="{{$modulename}} childcheckbox onoffswitch-checkbox" name="{{$modulename}}[]" id="{{$modulename}}{{$permission}}" value="{{$permission}}" checked>
									<label class="onoffswitch-label" for="{{$modulename}}{{$permission}}">
										<span class="onoffswitch-inner"></span>
										<span class="onoffswitch-switch"></span>
									</label>
								</div>
								<div class="permission-label">
										  <span class="per{{$modulename}} perfull">{{ucfirst(strtolower($permission))}} {{ucfirst(rtrim($pagerole->module_name,"s"))}}</span>
									</div>
								</div></label>
							</li>
							@endif
			
						 @elseif(($modulepermission != false) && (count($modulepermission)>0) && $modulepermission[0]->$per)
							@if($permission=="full")
							<li><label>
											<input type="checkbox" class="fullaccess" id="{{$modulename}}_full" name="{{$modulename}}[]" value="{{$permission}}" checked>	  
									  <span class="per{{$modulename}} perfull inactive">Open {{ucfirst($pagerole->module_name)}} Page</span>
								</label>
								  </li>
						@elseif(($modulepermission[0]->$per) && ($i==1))
								  <li><label>
											<input type="checkbox" class="fullaccess" id="{{$modulename}}_full" name="{{$modulename}}[]" value="{{$permission}}" checked>	  
									  <span class="per{{$modulename}} perfull active">Open {{ucfirst($pagerole->module_name)}} Page</span>
									  </label></li>
						
							@else
							<li><label>
								<div class="permissiondiv per{{$modulename}} per{{$permission}} active">
								<div class="onoffswitch">
									<input type="checkbox" class="{{$modulename}} childcheckbox onoffswitch-checkbox" name="{{$modulename}}[]" id="{{$modulename}}{{$permission}}" value="{{$permission}}" checked>
									<label class="onoffswitch-label" for="{{$modulename}}{{$permission}}">
										<span class="onoffswitch-inner"></span>
										<span class="onoffswitch-switch"></span>
									</label>
								</div>
								<div class="permission-label">
										  <span class="per{{$modulename}} perfull">{{ucfirst(strtolower($permission))}} {{ucfirst(rtrim($pagerole->module_name,"s"))}}</span>
									</div>
								</div></label>
						</li>
							@endif
						@else
							@if(($permission=="full") || ($i==1))
							<li><label>
											<input type="checkbox" class="fullaccess" id="{{$modulename}}_full" name="{{$modulename}}[]" value="{{$permission}}">	  
									  <span class="per{{$modulename}} perfull inactive">Open {{ucfirst($pagerole->module_name)}} Page</span>
								</label>
								  </li>
							@else
							<li><label>
								<div class="permissiondiv per{{$modulename}} per{{$permission}} inactive">
								<div class="onoffswitch">
									<input type="checkbox" class="{{$modulename}} childcheckbox onoffswitch-checkbox" name="{{$modulename}}[]" id="{{$modulename}}{{$permission}}" value="{{$permission}}">
									<label class="onoffswitch-label" for="{{$modulename}}{{$permission}}">
										<span class="onoffswitch-inner"></span>
										<span class="onoffswitch-switch"></span>
									</label>
								</div>
									<div class="permission-label">
											  <span class="per{{$modulename}} perfull">{{ucfirst(strtolower($permission))}} {{ucfirst(rtrim($pagerole->module_name,"s"))}}</span>
										</div>
									</div></label>
									</li>
							@endif
						@endif
						<?php $i++;?>
					@endforeach
						@if($pagerole->module_name=='Reports')
						<?php $reports=array('report1'=>'Employee Attendance Report','report2'=>'Employee Tardiness Report','report3'=>'Employee Absenses Report','report4'=>'Payroll Report','report5'=>'Philippines: BIR 1601C Report','report6'=>'Philippines: Payroll BPI Payroll Report','report7'=>'Philippines: Payroll BDO Payroll Report','report8'=>'Philippines: Payroll HSBC Payroll Report','report9'=>'Philippines: Payroll Maybank Payroll Report','report10'=>'Philippines: Pag-IBIG HQP-PFF-053 Report','report11'=>'Philippines: Pag-IBIG Soft Copy Report','report12'=>'Philippines: Philhealth Er2 Report','report13'=>'Philippines: Philhealth RF-1 Report','report14'=>'Philippines: SSS R-1a Report','report15'=>'Philippines: SSS R-3 Report','report16'=>'Philippines: HMO Report','report17'=>'Payroll Register Report','Inventory Asset Report');?>
						<ul>
						@foreach($reports as $key=>$report)
							<?php $classreport=str_replace(' ','',$report);?>
							<?php $check=checkreports($report,Auth::user()->Employee->department_id);?>
							@if($check)
							<li>
								<label>
									<div class="permissiondiv per{{$modulename}} per{{$modulename}}{{$key}} active">
										<div class="onoffswitch">
											<input type="checkbox" class="{{$modulename}} childcheckboxreport onoffswitch-checkbox" name="dailyreport[]" id="{{$modulename}}{{$key}}" value="{{$report}}" checked>
											<label class="onoffswitch-label" for="{{$modulename}}{{$key}}">
												<span class="onoffswitch-inner"></span>
												<span class="onoffswitch-switch"></span>
											</label>
										</div>
										<div class="permission-label">
											<span class="per{{$modulename}} perfull">{{ucfirst(strtolower($report))}} {{ucfirst(rtrim($pagerole->module_name,"s"))}}</span>
										</div>
									</div>
								</label>
							</li>
							@else
							<li>
								<label>
									<div class="permissiondiv per{{$modulename}} per{{$modulename}}{{$key}} inactive">
										<div class="onoffswitch">
											<input type="checkbox" class="{{$modulename}} childcheckboxreport onoffswitch-checkbox" name="dailyreport[]" id="{{$modulename}}{{$key}}" value="{{$report}}">
											<label class="onoffswitch-label" for="{{$modulename}}{{$key}}">
												<span class="onoffswitch-inner"></span>
												<span class="onoffswitch-switch"></span>
											</label>
										</div>
										<div class="permission-label">
											<span class="per{{$modulename}} perfull">{{ucfirst(strtolower($report))}} {{ucfirst(rtrim($pagerole->module_name,"s"))}}</span>
										</div>
									</div>
								</label>
							</li>
							@endif
						@endforeach
						</ul>
						@endif
					</ul>
				  </li>
				@endif
			  @endforeach
			  </ul>
			  <input type="submit" value="Save" class="btn btn-info">
          </div>
		</form>
        </div>
      </div>
    </div><!-- /col-lg-12 -->
  </div>
</div>
@endsection
@section('customjs')
<script type="text/javascript">
	
	$(document).ready(function(){
		$('.fullaccess').change(function(){
			var $fullid=$(this).attr('id');
			var $str=$fullid.split("_");
			var $value=$str[0];
			 if($(this).prop("checked") == true){
				$("."+$value).prop("checked", true);
				 $('li > label > .per'+$value).removeClass("inactive").addClass("active");
			 }
			else if($(this).prop("checked") == false){
				$("."+$value).prop("checked", false);
				$('li > label > .per'+$value).removeClass("active").addClass("inactive");
			}
		})
		$(".childcheckbox").change(function() {
			
			var $class=$(this).attr('class')
			var $str=$class.split(" ");
			var $value=$str[0];
			var $per=$(this).val();
			if($(this).prop('checked')==true){
				$('li > label > .per'+$value+'.per'+$per).removeClass("inactive").addClass("active");
			}
			else{
				$('li > label > .per'+$value+'.per'+$per).removeClass("active").addClass("inactive");
			}
			if ($("."+$value+":checked").length == $("."+$value).length) {
			  	$("#"+$value+'_full').prop("checked", true);
			  	$('li > label > .per'+$value).removeClass("inactive").addClass("active");
			} 
			else {
			 	$("#"+$value+'_full').prop("checked", false);
				$('li > label > .per'+$value+'.perfull').removeClass("active").addClass("inactive");
			}
		})
		$(".childcheckboxreport").change(function() {
			var $class=$(this).attr('class')
			var $str=$class.split(" ");
			var $value=$str[0];
			var $per=$(this).attr('id');
			if($(this).prop('checked')==true){
				$('li > label > .per'+$value+'.per'+$per).removeClass("inactive").addClass("active");
			}
			else{
				$('li > label > .per'+$value+'.per'+$per).removeClass("active").addClass("inactive");
			}
			if ($('.'+$value+':not(":checked")').length == $("."+$value).length) {
			 	$("#"+$value+'_full').prop("checked", false);
				$('li > label > .per'+$value+'.perfull').removeClass("active").addClass("inactive");
			}
			else{
				$("#"+$value+'_full').prop("checked", true);
				$('li > label > .per'+$value+'.perfull').removeClass("inactive").addClass("active");
			}
		})
	})
</script>
@endsection