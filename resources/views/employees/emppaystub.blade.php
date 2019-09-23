<!DOCTYPE html>
<html>
<head>
	<title>PAYSTUB</title>
	
</head>
<body>
	
	
<h1>PAYSTUB</h1>
<table style="width:100%">
	<tr>
		<td>
			<table  width="100%" >
				<tr>
					<td style="width:50%;"><b>BUSINESS NAME</b></td>
					<td rowspan="6" style="width:50%;text-align:right;" valign="top"><!--<img src="{{url('images/bentach-big-1-1.png')}}" alt="logo" style="height: 70px;width: auto;"> --><img src="/images/bentach-big-1-1.png" alt="logo" style="height: 70px;width: auto;"></td>
				</tr>
				<tr><td>{{$employee->address}}</td></tr>
				<tr><td>Address Line 2</td></tr>
				<tr><td>{{$employee->city}}, {{$employee->state}}, {{$employee->zipcode}}</td></tr>
				<tr><td>Phone</td></tr>
				<tr><td>Web / {{ $employee->email}}</td></tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table  width="100%" cellpadding="7">
				<tr>
					<td style="background:#535353;color:#fff;" width="25%">Employee Name</td>
					<td width="20%">{{$employee->first_name.' '.$employee->last_name}}</td>
					<td style="background:#333f50;color:#fff;" width="20%">PAY AND HOURS</td>
					<td style="background:#333f50;color:#fff;" width="15%">HOURS</td>
					<td style="background:#333f50;color:#fff;" width="20%">PYMNT</td>
				</tr>
				<tr>
					<td style="background:#535353;color:#fff;" width="25%">Employee Number</td>
					<td width="20%"  >{{$employee->employee_number}}</td>
					<td width="20%" style="background:#ededed;">Regular Hours</td>
					<td width="15%">0</td>
					<td width="20%">{{$employee->regular_hourly_rate}}</td>
				</tr>
				<tr>
					<td style="background:#535353;color:#fff;" width="25%">Federal Filing Status</td>
					<td width="20%" >{{$employee->federal_filing_status}}</td>
					<td width="20%" style="background:#ededed;">Overtime Hours</td>
					<td width="15%">0</td>
					<td width="20%">{{$employee->overtime_hourly_rate}}</td>
				</tr>
				<tr>
					<td style="background:#535353;color:#fff;" width="25%">Pay Begin Date</td>
					<td width="20%" >{{date('Y-m-d')}}</td>
					<td width="20%" style="background:#ededed;">Holiday Hours</td>
					<td width="15%">0</td>
					<td width="20%">0</td>
				</tr>
				<tr>
					<td style="background:#535353;color:#fff;" width="25%">Pay End Date</td>
					<td width="20%" >{{date('Y-m-d')}}</td>
					<td width="20%" style="background:#ededed;">Vacation Hours</td>
					<td width="15%">0</td>
					<td width="20%">0</td>
				</tr>
				<tr>
					<td style="background:#535353;color:#fff;" width="25%">Tax Allowance</td>
					<td width="20%">{{$employee->tax_allowance}}</td>
					<td width="20%" style="background:#ededed;">Sick Time Hours</td>
					<td width="15%">0</td>
					<td width="20%">0</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table  width="100%" cellpadding="7">
				<tr>
					<td style="background:#333f50;color:#fff;" width="25%">GROSS</td>
					<td style="background:#333f50;color:#fff;" width="10%">CURRENT</td>
					<td style="background:#333f50;color:#fff;" width="10%">YTD</td>
					<td style="background:#333f50;color:#fff;" width="20%">NET</td>
					<td style="background:#333f50;color:#fff;" >CURRENT</td>
					<td style="background:#333f50;color:#fff;" width="20%">YTD</td>
				</tr>
				<tr>
					<td style="background:#ededed;" width="25%">Gross Pay</td>
					<td style="" width="10%">0.00</td>
					<td style="" width="10%">0.00</td>
					<td style="background:#ededed;" width="20%">Net Pay</td>
					<td style="" >0.00</td>
					<td style="" width="20%">0.00</td>
				</tr>
				<tr>
					<td style="background:#ededed;" width="25%">Federal Taxable Gross Pay</td>
					<td style="" width="10%">0.00</td>
					<td style="" width="10%">0.00</td>
					<td width="20%"></td>
					<td style="" ></td>
					<td style="" width="20%"></td>
				</tr>	
			</table>
		</td>
	</tr>	
	<tr>
		<td>
			<table  width="100%" cellpadding="7">
				<tr>
					<td style="background:#333f50;color:#fff;" width="45%" colspan="3">PRE-TAX WITHHOLDINGS</td>
					<td style="background:#333f50;color:#fff;" width="55%" colspan="3">FEDERAL / STATE / PAYROLL TAX</td>
				</tr>
				<tr>
					<td style="background:#ededed;" width="25%">401(K) Contribution</td>
					<td style="" width="10%">{{$employee['401k_contribution']}}</td>
					<td style="" width="10%"></td>
					<td style="background:#ededed;"  width="20%">Federal Tax</td>
					<td style="" >0.00</td>
					<td style="" width="20%"></td>
				</tr>	
				<tr>
					<td style="background:#ededed;" width="25%">Other</td>
					<td style="" width="10%">{{$employee->pretax_withholdings_other}}</td>
					<td style="" width="10%"></td>
					<td style="background:#ededed;"  width="20%">State Tax</td>
					<td style="" >{{$employee->state_tax}}</td>
					<td style="" width="20%"></td>
				</tr>
				<tr>
					<td style="background:#333f50;color:#fff;" width="45%" colspan="3">POST-TAX DEDUCTIONS</td>
					<td style="background:#ededed;"  width="20%">Local Tax</td>
					<td style="" >{{$employee->local_tax}}</td>
					<td style="" width="20%"></td>
				</tr>
				<tr>
					<td style="background:#ededed;" width="25%">Insurance Premiums</td>
					<td style="" width="10%">{{$employee->posttax_deductin_insurance}}</td>
					<td style="" width="10%"></td>
					<td style="background:#ededed;"  width="20%">Social Security</td>
					<td style="" >{{$employee->social_security}}</td>
					<td style="" width="20%"></td>
				</tr>
				<tr>
					<td style="background:#ededed;" width="25%">Other</td>
					<td style="" width="10%">{{$employee->posttax_deductin_other}}</td>
					<td style="" width="10%"></td>
					<td style="background:#ededed;"  width="20%">Medicare</td>
					<td style="" >{{$employee->medicare}}</td>
					<td style="" width="20%"></td>
				</tr>
			</table>
		</td>
	</tr>	
	<tr>
		<td>
			<table width="100%" cellpadding="7">
				<tr>
					<td style="background:#333f50;color:#fff;" width="25%">LEAVE TYPE</td>
					<td style="background:#333f50;color:#fff;" width="20%">HOURS SPENT</td>
					<td style="background:#333f50;color:#fff;" width="20%">HOURS REMAINING</td>
					<td style="background:#333f50;color:#fff;" >DAYS REMAINING</td>
				</tr>
				<tr>
					<td style="background:#ededed;" width="25%">Holidays</td>
					<td style="" width="20%"></td>
					<td style="" width="20%"></td>
					<td style="" ></td>
				</tr>
				<tr>
					<td style="background:#ededed;" width="25%">Vacation</td>
					<td style="" width="20%"></td>
					<td style="" width="20%"></td>
					<td style="" ></td>
				</tr>
				<tr>
					<td style="background:#ededed;" width="25%">Sick Time</td>
					<td style="" width="20%"></td>
					<td style="" width="20%"></td>
					<td style="" ></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>	
			<table width="100%" cellpadding="7">
				<tr>
					<td style="background:#ededed;" width="25%">Messages</td>
					<td ></td>
				</tr>
			</table>	
		</td>
	</tr>
</table>
</body>
</html>