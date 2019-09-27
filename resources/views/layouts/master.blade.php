<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="shortcut icon" href="{{@asset(get_favicon()) }}" /> 
  <title>@yield('title')</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <link rel="stylesheet" href="{{ asset('css/vendors/mdi/css/materialdesignicons.min.css') }}">
  <link rel="stylesheet" href="{{ asset('css/vendors/base/vendor.bundle.base.css') }}">
  <link rel="stylesheet" href="{{ asset('css/vendors/datatables.net-bs4/dataTables.bootstrap4.css') }}">
  <link rel="stylesheet" href="{{ asset('css/style.css') }}"> 
  <link rel="stylesheet" href="{{ asset('css/custom.css') }}">  

  <!-- Plugins -->
  <link rel="stylesheet" type="text/css" href="{{asset('plugins/datepicker/daterangepicker.css')}}" />
  <link rel="stylesheet" type="text/css" href="{{asset('plugins/datepicker/bootstrap-datepicker.min.css')}}" />
  <link rel="stylesheet" href="{{ asset('css/dropify.min.css') }}"> 
  <!-- <link rel="stylesheet" href="{{ asset('css/jquery.toast.min.css') }}">  -->
  @yield('customcss')

</head>
<body class="sidebar-fixed">  
	<script type="text/javascript">var date_format = "";
		var base_url = "{{URL::to('/')}}";
	</script>
	<div class="container-scroller">
	
    @if (Request::segment(1) != 'login'
      && Request::segment(1) != 'register'
      && Request::segment(1) != ''
      && Request::segment(2) != 'reset'
      && Request::segment(1) != 'email'
      )
      @include('layouts.topnav')
      <div class="container-fluid page-body-wrapper">
      @include('layouts.sidebar')
      <div class="main-panel">
        @yield('content')
        @include('layouts.footer')
      </div>
    @else
      <div class="container-fluid page-body-wrapper full-page-wrapper">
		 
      @yield('content')
    @endif
        
    </div>
  </div>
  <div id="server-current_date_container_key" data-server_time="{{get_server_datetime()}}"></div>
  <div id="server-date_format_container_key" data-date_format="{{get_date_format()}}"></div>
  <script src="{{ asset('js/vendors/base/vendor.bundle.base.js') }}"></script>
  <!-- <script src="{{ asset('js/js/jquery.toast.min.js') }}"></script> -->
  <script src="{{ asset('js/vendors/chart.js/Chart.min.js') }}"></script>
  <script src="{{ asset('js/vendors/datatables.net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('js/vendors/datatables.net-bs4/dataTables.bootstrap4.js') }}"></script>
  <script src="{{ asset('js/js/off-canvas.js') }}"></script>
  <!--<script src="{{ asset('js/js/hoverable-collapse.js') }}"></script>-->
  <script src="{{ asset('js/js/template.js') }}"></script>
  <script src="{{ asset('js/js/dashboard.js') }}"></script>
  <script src="{{ asset('js/js/data-table.js') }}"></script>
  <script src="{{ asset('js/js/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('js/js/dataTables.bootstrap4.js') }}"></script>
  <script src="{{ asset('js/js/dropify.min.js') }}"></script>
  <script src="{{asset('plugins/datepicker/moment.min.js')}}"></script>
  <script src="{{asset('plugins/datepicker/daterangepicker.min.js')}}"></script>
  <script src="{{asset('plugins/datepicker/bootstrap-datepicker.min.js')}}"></script>

  <script type="text/javascript">
    window.addEventListener("pageshow", function(event){
      if (event.originalEvent.persisted) {
        
            window.location.reload();
        }
    });
  </script>
  <!-- <script src="{{ asset('/js/js/toastDemo.js') }}"></script> -->
  <!-- <script src="{{ asset('/js//js/desktop-notification.js') }}"></script> -->
	<script type="text/javascript">
         jQuery(document).ready(function(){
			 var session_time=$('#sessiontime').val()
			var url= window.location.pathname;
			url.substring(url.lastIndexOf("/") + 1);
			if(url!="/login"){
				 setInterval(function(){
					 jQuery.ajax({
						 url: "{{ route('user.logout') }}",
						 type: 'GET',
						 data: {
							"_token": "{{ csrf_token() }}",
						},
						 success:function(response){
							 window.location="/login"; 
							 return false;
						 },
						 error:function(data){
							alert("something wrong")
						 }
					 });
				 },session_time);
			}
         });
    </script>
 
  <script type="text/javascript">
    $(document).ready(function (){

      load_date_inputs();
     // $('input#employee_number').css('background', '#003366');
      function load_date_inputs(){
        var date_input = $("input.is_datefield");
        date_input.attr('type', 'text');
        date_input.attr('readonly','true');
	  
        $('input[type=text].is_datefield').css('background', '#FFF');

        var date_format = '{{get_date_format()}}';
        var date_format_json = [];
        for(var x=0; x < date_format.length; x++){
          if(date_format[x] != '-'){
            if(date_format[x] == 'Y' || date_format[x] == 'y'){
              date_format_json.push({x:'yyyy'});
            }else if(date_format[x] == 'm' || date_format[x] == 'M'){
              date_format_json.push({x:'mm'});
            }else if(date_format[x] == 'd' || date_format[x] == 'D'){
              date_format_json.push({x:'dd'});
            }
          }
        }

        
        
        date_input.each(function (){
          var current_input = $(this);
          var date_value = current_input.val();
          var picker_value = '';
			var is_placeholder = $(this).attr('data-placeholder');
          if(date_value != ''){
            var date = new Date(date_value);
            var day = date.getDate();
            var month = date.getMonth() + 1;
            var year = date.getFullYear();
            if(day < 10)
              day = '0'+day;
            if(month < 10)
              month = '0'+month;

            var date_array = {
              'dd'   : day,
              'mm'   : month,
              'yyyy' : year,
            };
            var picker_format = '';
            for(var item in date_format_json){
              for(var item_ in date_format_json[item]){
                var formatted_letter = date_format_json[item][item_];
                picker_format += formatted_letter+'-';
                
                if(formatted_letter == 'mm')
                  picker_value = picker_value+date_array.mm+'-';
                else if(formatted_letter == 'dd')
                  picker_value = picker_value+date_array.dd+'-';
                else
                  picker_value = picker_value+date_array.yyyy+'-';

              }
            }
            
            picker_value = picker_value.slice(0, -1);

          }else{

            var picker_format = '';
            for(var item in date_format_json){
              for(var item_ in date_format_json[item]){
                var formatted_letter = date_format_json[item][item_];
                picker_format += formatted_letter+'-';
              }
            }
          }

          
          picker_format = picker_format.slice(0, -1);
			if(is_placeholder=='' || is_placeholder==undefined){
				date_input.attr('placeholder', picker_format.toUpperCase());
			}	
          
          // alert(picker_format+' '+picker_value);
          current_input.val(picker_value);
          // alert(picker_value);

          current_input.datepicker({
            'format'  : picker_format, //dd for day, mm for month, yyyy for year
			'orientation' : 'left bottom',
          });
        });

        


      }
    });
  </script>
	 @yield('customjs')	
</body>
</html>