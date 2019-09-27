@extends('layouts.master')
@section('title', 'BIR 1601C')
@section('customcss')
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/timepicker/jquery.timepicker.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/multiselect/fSelect.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('report-assets/css/report.css') }}">
    <style>
        div[id^=multiple-select-container-] .dropdown-select{
            z-index : 1000;
        }
        div[id^=multiple-select-container-] .dropdown-select ul.list-group{
            max-height : 300px;
        }
        .fs-wrap {
            width: 100%;
            height: 2.575rem;
            font-size: 0.875rem;
            line-height: 2.0;
        }
        .fs-wrap .fs-label-wrap{
            border-radius: 0.2rem;
        }
    </style>
@endsection
@section('content')

<div class="content-wrapper">
    <div class="content">
        <div class="card">
            <div class="card-header">
            BIR 1601C
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

                <form method="GET" action="" class="report-g_form">
                @csrf
                    
                    <div class="row">
                        <div class="col-4">
                            <small>
                                For BIR BCS/
                                <div>
                                    Use Only Item:
                                </div>
                            </small>
                        </div>
                        <div class="col-3">
                            <img src="{{asset('report-assets/img/bir-logo.png')}}" alt="BIR Logo" class="report-bir-logo">
                            <small>
                                <div class="text-center">
                                    <div>Republic of the Philippiens</div>
                                    <div>Department of Finance</div>
                                    <div>Bureau of Internal Revenue</div>
                                </div>
                            </small>
                        </div>
                        <div class="col-4">
                        </div>
                    </div>

                </form>
                
            </div>
        </div>
    </div>
</div>

@endsection
@section('customjs')
<script src="{{asset('plugins/timepicker/jquery.timepicker.min.js')}}"></script>
<!--<script src="{{asset('plugins/multiselect/js/multiple-select.js')}}"></script>-->
<script src="{{asset('plugins/multiselect/fSelect.js')}}"></script>

<script type="text/javascript">
	
</script>


@endsection