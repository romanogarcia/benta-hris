@extends('layouts.master')

@section('title', 'Add SSS Table Form')

@section('content')
    <div class="content-wrapper">
        <div class="card">
            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <li>
                            <i class="mdi mdi-alert-circle"></i>
                            <strong>{{ $error }}</strong>
                        </li>
                    @endforeach
                </div>
            @endif
            <div class="card-header">Add SSS table</div>
            <div class="card-body">
                <form method="post" action="{{ route('sss.store') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label for="salary_min">Minimum Compensation Range</label>
                                <input class="form-control" id="salary_min" type="decimal" placeholder="Minimum compensation range" name="salary_min" value="">
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label for="salary_max">Maximum Compensation Range</label>
                                <input class="form-control" id="salary_max" type="decimal" placeholder="Maximum compensation range" name="salary_max" value="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="class col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label for="salary">Salary</label>
                                <input class="form-control" id="salary" type="decimal" placeholder="Salary" name="salary" value="">
                            </div>
                        </div>
                        <div class="class col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label for="salary">EC</label>
                                <input class="form-control" id="ec" type="decimal" placeholder="EC" name="ec" value="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label for="ee">Employee Share</label>
                                <input class="form-control" id="ee" type="number" placeholder="EE" name="ee" value="">
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label for="er">Employer Share</label>
                                <input class="form-control" id="er" type="number" placeholder="ER" name="er" value="">
                            </div>
                        </div>
                    </div>
					<div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                   			 <button class="btn btn-success float-right full-width-xs" type="submit"><i class="mdi mdi-check"></i> UPDATE</button>
						</div>	
					</div>	
				</form>
            </div>
          
        </div>
    </div>
@endsection