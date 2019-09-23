<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
	  <div class="navbar-brand-wrapper d-flex justify-content-center navbar-menu-icon-sm">
        <div class="navbar-brand-inner-wrapper d-flex justify-content-between align-items-center w-100" style="padding: 17px 0px 0;">  
           <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
			  <span class="mdi mdi-menu"></span>
			</button>
        </div>  
      </div>	
      <div class="navbar-brand-wrapper d-flex justify-content-center" style="border: 0px solid transparent; position: relative; z-index: 2000; box-shadow: 0 4px 6px -6px rgba(0,0,0,0.2);">
        <div class="navbar-brand-inner-wrapper d-flex justify-content-between align-items-center w-100">  
         
          <a class="navbar-brand brand-logo-mini" href="{{ route('home') }}">@if(get_mobile_logo()) <img src="{{ @asset(get_mobile_logo()) }}" alt="company logo"/> @endif</a>
          <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="mdi mdi-menu"></span>
          </button>
		      <a class="navbar-brand brand-logo" href="{{ route('home') }}">@if(get_logo()) <img src="{{ @asset(get_logo()) }}" alt="company logo"/> @endif</a>	
        </div>  
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end" style="border-bottom: 1px solid transparent; box-shadow: 0 4px 6px -6px rgba(0,0,0,0.1);">
        <ul class="navbar-nav navbar-nav-right">
          <li class="nav-item nav-profile" title="Current Time/Date" style="cursor: pointer;">
            <span style="color: #333;" id="top_nav-current_realtime_date"></span>
          </li>
          <li class="nav-item nav-profile dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
              <img src="{{get_profile_picture()}}" alt="profile"/>
              <span class="nav-profile-name">
                @if(isset(Auth::user()->username))
                  {{ Auth::user()->username }}
				 @elseif(!isset(Auth::user()->username) && isset(Auth::user()->name)) 
				  {{ Auth::user()->name }}
                @endif 
                </span>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
              @if(!Auth::user()->time_in_out_deactivate)
              <form method="post" action="{{ route('proccessTimein') }}" >
                  @csrf
                  <button type="submit" class="btn btn-success ml-3" style="margin-left:9px !important;width:140px"><i class="mdi mdi-login"></i> TIME-IN &nbsp; </button>
                  
               </form>
               <div class=" ml-2 mt-2 ">
               		<button class="btn btn-danger" data-toggle="modal" data-target="#exampleModalCenter"><i class="mdi mdi-logout"></i>TIME-OUT</button>
            	</div>
            	@endif
              <a class="dropdown-item" href="{{ url('myprofile') }}">
                <i class="mdi mdi-settings text-primary"></i>
                Settings
              </a>

              
              <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="mdi mdi-logout text-primary"></i>
                {{ __('Logout') }}
               
              </a>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                  @csrf
              </form>

            </div>
          </li>
        </ul>
      </div>
    </nav>
    
    <!-- Modal -->
    <div class="modal" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalCenterTitle">Confirm Timeout?</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
           Are you sure. You want to Time-out ?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <form method="post" action="{{ route('proccessTimeout') }}">
            @csrf
            <input type="hidden" value="{{ Session::get('attendance_id') }}" name="att_id">
            <button type="submit" class="btn btn-danger">TIMEOUT</button>
            </form>
          </div>
        </div>
      </div>
    </div>
