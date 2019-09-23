@extends('layouts.master')
@section('title', 'Payroll')
@section('content')
<div class="content-wrapper">
  <div class="content">
  <div class="col-lg-6">
    <div class="card">
    <table class="table table-bordered">
      <thead>
        <th colspan="2">Payroll Information:</th>
      </thead>
      <tbody>
        <tr>
          <td>Billing number:</td>
          <td><strong>{{Request::segment(2)}}</strong></td>
        </tr>
        <tr>
          <td>Period:</td>
          <td><strong>{{$period}}</strong></td>
        </tr>
      </tbody>
    </table>
    </div>
  </div>
  <hr>
    <div class="card">
      <div class="card-header">
        Records:
      </div>
      <div class="card-body">
        <table class="table table-responsive-md">
          <thead>
            <th>Employee number</th>
            <th>Name</th>
            <th>Total Hours</th>
            <th>Gross</th>
            <th>Deduction</th>
            <th>Basic Pay</th>
            <th>Net Pay</th>
          </thead>
          <tbody>
            @if($payrolls)
              @foreach($payrolls as $p)
                  <tr>
                    <td><a href="{{ route('payroll.summary',['billing_number'=>Request::segment(2),'employee_id'=>Illuminate\Support\Facades\Crypt::encryptString($p->employee_id)]) }}">{{$p->employee_number}}</a></td>
                    <td>{{$p->name}}</td>
                    <td>{{$p->total_hours}}</td>
                    <td>{{$p->gross}}</td>
                    <td>{{$p->total_deduction}}</td>
                    <td>{{$p->basic_pay}}</td>
                    <td>{{$p->netpay}}</td>
                  </tr>
              @endforeach
            @endif
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection