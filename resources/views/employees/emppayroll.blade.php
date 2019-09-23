<!DOCTYPE html>
<html>
<head>
	<title>PAYROLL</title>
	
</head>
<body>
<table style="width:100%">
	<tr>
		<td >
			<!--<img src="{{url('images/bentach-big-1-1.png')}}" alt="logo" style="height: 70px;width: auto;"> -->
    		<img src="/images/bentach-big-1-1.png" alt="logo" style="height: 70px;width: auto;">
		</td>
	</tr>
	<tr>
		<td >
			Bentacos, OCC, Julia Vargas Avenue, 1605 Pasig City
		</td>
	</tr>
	<tr><td></td></tr>
	<tr><td></td></tr>
	<tr>
		<td>{{($employee->first_name!='')?$employee->first_name:"".' '.($employee->last_name!='')?$employee->last_name:""}} </td>
	</tr>
	<tr>
		<td>{{($employee->address!='')?$employee->address:""}} </td>
	</tr>
	<tr>
		<td>{{($employee->city!='')?$employee->city:""}} </td>
	</tr>
	<tr>
		<td>{{($employee->zipcode!='')?$employee->zipcode:""}} </td>
	</tr>
	<tr>
		<td>Philippines</td>
	</tr>
	
	<tr style="height:60px;"><td></td></tr>
	<tr>
		<td>
			<br><br><br>
			<table width="100%">
				<tr ><td ><b>PAYROLL</b></td><td><b>Test Template Payroll</b></td></tr>
			</table> 
		</td>
	</tr>
	<tr style="height:25px;"><td></td></tr>
	<tr >
		<td>
			<br>
			<table width="100%" border="1" style="border-collapse: collapse;" cellpadding = "5">
				<tr ><td width="50%"><b>Billing-Number</b></td><td width="50%"><b>Billing-Period</b></td></tr>
				<tr><td width="50%">BITS-61</td><td width="50%">01.01.2019 to 31.12.2018</td></tr>
			</table> 
		</td>
	</tr>
	
	<tr style="height:25px;"><td></td></tr>
	
	<tr border="1" style="border:1px solid #000;">
		<td>
			<br>
			<table width="100%" border="1" style="border-collapse: collapse;" cellpadding = "5">
				<tr >
					<td width="50%"><b>Description</b></td>
					<td width="10%"><b>Quantity</b></td>
					<td width="10%"><b>Base</b></td>
					<td width="15%"><b>PHP</b></td>
					<td width="15%"><b>Total PHP</b></td>
				</tr>
				<tr>
					<td>{{($employee->job_title!='')?$employee->job_title:""}}</td>
					<td>1</td>
					<td>10’000.00</td>
					<td>10’000.00</td>
					<td></td>
				</tr>
				<tr>
					<td>Food Allowance</td>
					<td>1</td>
					<td>{{($employee->food_allowance!='')?$employee->food_allowance:""}}</td>
					<td>{{($employee->food_allowance!='')?$employee->food_allowance:""}}</td>
					<td></td>
				</tr>
				<tr>
					<td>Transportation Allowance</td>
					<td>1</td>
					<td>{{($employee->transportation_allowance!='')?$employee->transportation_allowance:""}}</td>
					<td>{{($employee->transportation_allowance!='')?$employee->transportation_allowance:""}}</td>
					<td></td>
				</tr>
				<tr>
					<td>Total Gross wage und Allowance</td>
					<td></td>
					<td></td>
					<td></td>
					<td>14’650.00</td>
				</tr>
				<tr>
					<td>HMO</td>
					<td></td>
					<td></td>
					<td>- 413.92</td>
					<td></td>
				</tr>
				<tr>
					<td>Total Deduction</td>
					<td></td>
					<td></td>
					<td></td>
					<td>- 413.92</td>
				</tr>
				<tr>
					<td><b>Payout</b></td>
					<td></td>
					<td></td>
					<td></td>
					<td><b>14’236.08</b></td>
				</tr>
			</table> 
		</td>
	</tr>
	
	<tr style="height:25px;"><td></td></tr>
	<tr><td><br>The Salary will sent at : BANCO DE ORO, Account: 5267270034554523</td></tr>
	
</table>	
</body>
</html>	