@extends('layouts.master')
@section('title', 'Attendance')
@section('content')
    <div class="content-wrapper">
        <div class="content">
            <div class="card">
                <div class="card-header">
                    Attendance Today
                </div>
                <div class="card-body">
                    @if($total!=0)
                        @include('includes.perpage')
                        <br><br><br><br>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                <th>Name</th>
                                <th>Time-In</th>
                                <th>time-Out</th>
                                <th>Total</th>
                                </thead>
                                <tbody>
                                @foreach($rows as $attendance)
                                    <tr>
                                        <td>{{$attendance->name}}</td>
                                        <td>{{$attendance->time_in}}</td>
                                        <td>{{$attendance->time_out}}</td>
                                        <td>{{$attendance->total}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!!  $entries !!}
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="float-lg-right">
                                    {{$rows->links()}}
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning">THERE ARE NO CURRENT RECORDS FOR TODAY. PLEASE CHECK AGAIN LATER</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection