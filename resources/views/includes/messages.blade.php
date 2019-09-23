@if ($message = Session::get('success'))
<div class="d-flex justify-content-center align-items-center">
<div class="alert alert-success center-block col-lg-6" role="alert">
    <p>{{$message}}</p>
</div>
</div>
@endif
@if ($message = Session::get('error'))
<div class="d-flex justify-content-center align-items-center">
<div class="alert alert-danger center-block col-lg-6" role="alert">
    <p>{{$message}}</p>
</div>
</div>
@endif
