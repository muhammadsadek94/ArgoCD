<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>{{ $payout->secondary_id }}</title>

	<style>
        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }

        a {
            color: #5D6975;
            text-decoration: underline;
        }

        body {
            position: relative;
            /*width: 21cm;*/
            /*height: 29.7cm;*/
            margin: 0 auto;
            color: #001028;
            background: #FFFFFF;
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        header {
            padding: 10px 0;
            margin-bottom: 30px;
        }

        #logo {
            text-align: center;
            margin-bottom: 10px;
        }

        #logo img {
            width: 120px;
        }

        h1 {
            border-top: 1px solid #5D6975;
            border-bottom: 1px solid #5D6975;
            color: #5D6975;
            font-size: 2.4em;
            line-height: 1.4em;
            font-weight: normal;
            text-align: center;
            margin: 0 0 20px 0;
            background: url({{ public_path('assets/pdf/invoices/dimension.png') }});
        }

        #project {
            float: left;
			text-align: left;
        }

        #project span {
            color: #5D6975;
            text-align: right;
            width: 52px;
            margin-right: 10px;
            display: inline-block;
            font-size: 0.8em;
        }

        #company {
            width: 200px;
            float: right;
            text-align: right;
        }

        #project div,
        #company div {
            white-space: nowrap;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 20px;
        }

        table tr:nth-child(2n-1) td {
            background: #F5F5F5;
        }

        table th,
        table td {
            text-align: center;
        }

        table th {
            padding: 5px 20px;
            color: #5D6975;
            border-bottom: 1px solid #C1CED9;
            white-space: nowrap;
            font-weight: normal;
        }

        table .service,table td, table .price {
            padding: 20px;
            text-align: left;
        }

        table td.service, table td.unit, table td.total {
            font-size: 1.2em;
            text-align: left;

        }

        table td.grand {
            border-top: 1px solid #5D6975;;
        }




	</style>
</head>
<body>
<header class="clearfix">
	<div id="logo">
		<img src="{{ public_path('assets/imgs/logo.png') }}">
	</div>
	<h1>INVOICE {{ $payout->secondary_id }}</h1>
	<div id="company" class="clearfix">
		<div>EC-Council Learning</div>
		<div>Plot No: S1
			<br/> TIE Phase-1, Balanagar
			<br> Hyderabad 500037
		</div>
		<div>+91-40-499-49100</div>
		<div><a href="mailto:Learnersupport@eccouncil.org">Learnersupport@eccouncil.org</a></div>
	</div>
	<div id="project">
		<div><span>Payout ID</span> {{ $payout->secondary_id }} </div>
		<div><span>CLIENT</span> {{ $user->full_name }}</div>
		<div><span>EMAIL</span> <a href="mailto:{{ $user->email }}">{{ $user->email }}</a></div>
		<div><span>DATE</span> {{ $payout->created_at }}</div>
	</div>
</header>
<main>
	<table style="width: 100%;">
		<thead>
		<tr>
			<th class="service">Details</th>
			<th class="price">Amount</th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td class="service">
                {{ ($payout->name) }}
                <br>
                {{ $payout->period }}
            </td>
			<td class="unit">${{ $payout->amount }}</td>
		</tr>

		{{--		<tr>--}}
		{{--			<td colspan="4">SUBTOTAL</td>--}}
		{{--			<td class="total">$5,200.00</td>--}}
		{{--		</tr>--}}
		{{--		<tr>--}}
		{{--			<td colspan="4">TAX 25%</td>--}}
		{{--			<td class="total">$1,300.00</td>--}}
		{{--		</tr>--}}
		<tr>
			<td colspan="2" class="grand total">GRAND TOTAL</td>
			<td  class="grand total">${{ $payout->amount }}</td>
		</tr>
		</tbody>
	</table>

</main>

</body>
</html>
