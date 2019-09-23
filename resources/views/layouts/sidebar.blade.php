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
<?php $parents =get_parents_menu();?>
      <nav class="sidebar sidebar-offcanvas" id="sidebar" style="z-index: 1000;">
        <ul class="nav">

		@if($parents!=false)
			@if(count($parents)>0)
			@foreach($parents as $parent)
				@if($parent->module_link!="")
				<?php $permissions=explode('|',$parent->permissions);?>
			<?php $check=0;?>
					@foreach($permissions as $permission)
						@if($check==0)
							<?php $checkpermission=check_permission(Auth::user()->employee->department_id,$parent->page_name,$permission);?>
							@if($checkpermission)
								<?php $check=1;?>
							@endif
						@endif
					@endforeach
					@if($check==1)
						<li class="nav-item">
							<a class="nav-link" href="{{url($parent->module_link)}}">
							 {!!$parent->menu_icon!!}
							  <span class="menu-title">{{$parent->module_name}}</span>
							</a>
						</li>
					@endif
				@else
					<?php $module_name=str_replace(' ', '', $parent->module_name);?>
					<li class="nav-item">
						<a class="nav-link colla" data-toggle="collapse" href="#{{$module_name}}" aria-expanded="false" aria-controls="{{$module_name}}">
						  {!!$parent->menu_icon!!}
						  <span class="menu-title">{{$parent->module_name}}</span>
						  <i class="menu-arrow"></i>
						</a>
						<div class="collapse" id="{{$module_name}}">
							<?php $child_parents =get_child_menu($parent->id);?>
							
							@if($child_parents != false)
							@if(count($child_parents)>0)
							<ul class="nav flex-column sub-menu">
							@foreach($child_parents as $child_parent)
								<?php $check=0;?>
								<?php $permissions=explode('|',$child_parent->permissions);?>
								@foreach($permissions as $permission)
									@if($check==0)
										<?php $checkpermission=check_permission(Auth::user()->employee->department_id,$child_parent->page_name,$permission);?>
										@if($checkpermission)
											<?php $check=1;?>
										@endif
									@endif
								@endforeach
								@if($check)
								<li class="nav-item">
								<a class="nav-link" href="{{url($child_parent->module_link)}}">{!!$child_parent->menu_icon!!} &nbsp;&nbsp; {{$child_parent->module_name}} </a></li>
								@endif
							
							@endforeach
								</ul>
								@endif
							@endif
						</div>
					  </li>
				@endif
			@endforeach	
			@endif
		@endif

        </ul>
      </nav>