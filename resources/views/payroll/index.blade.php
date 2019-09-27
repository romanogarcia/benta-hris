@extends('layouts.master')
@section('title', 'Bulk Payroll')
@section('content')
<div class="content-wrapper">
  <div class="content">
    <div class="card">
      <div class="card-header">
        Bulk Payroll
      </div>
        <div class="card-body">
          @if ($message = Session::get('success'))
            <div class="alert alert-success" role="alert">
                <i class="mdi mdi-alert-circle"></i>
                <strong>{{ $message }}</strong>
            </div>
          @elseif($message = Session::get('error'))
            <div class="alert alert-danger" role="alert">
                <i class="mdi mdi-alert-circle"></i>
                <strong>{{ $message }}</strong>
            </div>
          @endif
          <form method="post" action="{{ route('payroll.generate') }}">
          @csrf
          
          <div class="row">
            <div class="col-lg">
              <label for="">PAYROLL PERIOD </label>
            </div>
          </div>

            <div class="row">
              <div class="col-md-5">
                <div class="form-group">
                  <label>FROM</label>
                  <input class="form-control form-control-sm is_datefield @error('from') is-invalid @enderror" name="from" id="from" type="text" value="{{ old('from') }}" required>
					@error('from')
                            <div class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
              </div>
              <div class="col-md-5 offset-md-1">
                <div class="form-group">
                  <label>TO </label>
                  <input class="form-control form-control-sm is_datefield @error('to') is-invalid @enderror" name="to" id="to" type="text" value="{{ old('to') }}" required>
					@error('to')
                            <div class="invalid-feedback">{{$message}}</div>
                    @enderror
					
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg pt-2 text-right">
                <button type="submit" class="btn btn-primary mt-4">Generate</button>
              </div>
            </div>
        </form>
        {{-- <hr>
        <div class="card">
        <div class="card-header">
          New Generated Payroll:            
        </div>
        <div class="card-body">
        <table class="table table-hover">
            <thead>
              <th>Billing number:</th>
              <th>Payroll Period:</th>
              <th>Generated on:</th>
            </thead>
            <tbody>
            @if($payrolls != null)
            @php $i = 0; @endphp
              @foreach($payrolls as $payroll)
                @if(date('Y-m-d', strtotime($payroll->created_at)) == date('Y-m-d'))
                <tr class="alert alert-success">
                  <td><a class="badge badge-primary" target="_blank" href="{{ route('payroll.show',[$payroll->billing_number]) }}">{{$payroll->billing_number}}</a></td>
                  <td>{{$payroll->period}}</td>
                  <td>Today</td>
                </tr>
                @else
                @php
                  $i++;
                  if($i==1){
                    echo '<div class="alert alert-warning"> New generated payroll appears here.</div>';
                  }
                @endphp
                @endif
              @endforeach
            @else 
            <div class="alert alert-warning"> New generated payroll appears here.</div>
            @endif
            </tbody>
          </table>
        </div>
        <div class="card-header">
            Previously Generated Payroll periods:          
          </div>
          <div class="card-body">
          <table class="table table-hover">
            <thead>
              <th>Billing number:</th>
              <th>Payroll Period:</th>
              <th>Generated on:</th>
            </thead>
            <tbody>
            @if($payrolls != null)
              @foreach($payrolls as $payroll)
                @if( strtotime(date('Y-m-d',strtotime($payroll->created_at))) < strtotime(date('Y-m-d')) )
                <tr>
                  <td><a class="badge badge-primary" target="_blank" href="{{route('payroll.show',[$payroll->billing_number])}}">{{$payroll->billing_number}}</a></td>
                  <td>{{$payroll->period}}</td>
                  <td>{{$payroll->created_at}}</td>
                </tr>
                @endif
              @endforeach
            @else 
            <div class="alert alert-warning"> No previous record found </div>
            @endif
            </tbody>
            @if($payrolls)
            {{$payrolls->links()}}
            @endif
          </table>
          </div>
        </div> --}}
      </div>
    </div>
  </div>
</div>
@endsection

@section('customjs')
<script>
    // var asiaTime = new Date().toLocaleString("en-US", {timeZone: "Asia/Manila"});
    // var date = new Date(asiaTime);
    // $('#from')[0].valueAsNumber = new Date(date.getFullYear(), date.getMonth(), 2); // 1
    // $('#to')[0].valueAsNumber = new Date(date.getFullYear(), date.getMonth() + 1, 1); // 0
    
    // $(function(){
    //   var dtToday = new Date();
    //   var month = dtToday.getMonth() + 1;
    //   var day = dtToday.getDate();
    //   var year = dtToday.getFullYear();
    //   if(month < 10)
    //       month = '0' + month.toString();
    //   if(day < 10)
    //       day = '0' + day.toString();
    //   var maxDate = year + '-' + month + '-' + day;    
    //   $('#from').attr('max', maxDate);
    //   $('#to').attr('max', maxDate);
    // });
</script>
@endsection