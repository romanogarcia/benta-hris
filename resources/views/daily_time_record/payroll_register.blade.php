<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
	<title>{{ $title }}</title>
	<style type="text/css">
        /* @import url('https://fonts.googleapis.com/css?family=Roboto&display=swap'); */
        body, body * {
            font-family: 'Roboto', sans-serif;
        }
		body {
			margin-bottom:30px;
			margin-top:0px;
		}
	</style>	
</head>
<body>
	<table width="100%">
		<tr width="100%">
			<td width="100%" style="text-align:left;">
			@if($company->company_logo != '')
			<p><img src="{{ public_path(get_logo()) }}" alt="LOGO" height="100px" style="margin-left: -40px;"></p>
			@endif
			</td>
		</tr>	
		<tr  width="100%">
			<td width="100%" style=""><h2>{{ $title }}</h2></td>
		</tr>
	</table>
	<table width="100%">
		<tr width="100%">
			<th>Employee</th>
			<th>Batch</th>
			<th>Payroll</th>
			<th>From</th>
			<th>To</th>
			<th>Payroll Date</th>
			<th>Paid</th>
		</tr>
		@if(!empty($data_array))
			<?php foreach($data_array as $row){ ?>
			<tr width="100%">
				<td><?php echo ucwords($row->first_name).' '. ucwords($row->last_name)?></td>
				<td>{{$row->batch}}</td>
				<td>{{$row->payroll}}</td>
				<td>{{$row->period_from}}</td>
				<td>{{$row->period_to}}</td>
				<td>{{$row->payroll_date}}</td>
				<td>{{number_format($row->netpay, 2)}}</td>
			</tr>
			<?php } ?>
		@endif
	</table>	
	<script type="text/php">
		if ( isset($pdf) ) {

			$pdf->page_text(750, 555, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 10, array(0, 0, 0));
		}
	</script> 

</body>
</html>