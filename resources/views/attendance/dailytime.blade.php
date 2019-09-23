@extends('layouts.app')
@section('title', 'Attendance')
@section('content')
<div class="container">
    <h1 class="text-center">DAILY TIME RECORD</h1>
    <h1 class="timedisplay" id="txt" style="font-size:150px;text-align:center"></h1>
    <div class="container d-flex justify-content-center">
        <div class="btn-group " role="group" aria-label="Basic example">
            <button id="timein" type="button" class="btn btn-primary btn-lg">TIME-IN</button>
            <button id="timeout" type="button" class="btn btn-dark btn-lg">TIME-OUT</button>
        </div>
    </div>
    <div class="container d-flex justify-content-center">
    <div class="form-group py-5">
        <input class="form-control" placeholder="ENTER EMPLOYEE ID" id="employeeID">
    </div>
    </div>
    <div class="container text-center">
      <h2 id="resp"></h2>
    </div>
</div>
<script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
<script>
(function(){
  startTime();
})();
  function startTime() {
  var today = new Date();
  var h = today.getHours();
  var m = today.getMinutes();
  var s = today.getSeconds();
  m = checkTime(m);
  s = checkTime(s);
  document.getElementById('txt').innerHTML =
  h + ":" + m + ":" + s;
  var t = setTimeout(startTime, 500);
  }
  function checkTime(i) {
    if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
    return i;
  }
  $(document).ready(function(){
    $('#timein').click(function(){
      proc('timein');
    })
    $('#timeout').click(function(){
      proc('timeout');
    })
  });

  function proc(e)
  {
    var emp = $('#employeeID').val();
        $.ajax({
          url: '<?php echo route('proccessdtr') ?>',
          type: 'post',
          data: {_token:"<?= csrf_token() ?>",employeeID:emp,method:e},
          success: function(data){
            var resp = JSON.parse(data);
            if(resp.error==''){
              $('#resp').html(resp.message + " " + resp.time);
            } else {
              $('#resp').html(resp.error);
            }
            $('#employeeID').val('');
          },
          error: function(data){

          }
        })
  }
</script>
@endsection
