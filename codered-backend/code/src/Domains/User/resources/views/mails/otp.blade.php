<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<link href="https://fonts.googleapis.com/css?family=Lato:300" rel="stylesheet">
</head>
<body style="font-family: 'Lato', sans-serif; background-color: #ecf0f1;">
<div style="margin: 0 auto;">

	<div style=" padding: 0px 24px 27px 57px; clear: both;">
		<div style="text-align:left;">
			<img src="{{ asset('assets/imgs/Red logo.png') }}" style="height: 40px;  margin:27px auto 0px auto">
		</div>
		<div style="width: 100%; display: block">

			<h2 style="white-space: pre-line; line-height: 1.8; color:#2c3e50;margin: 0px auto 40px auto;">
				Please find the verification code here: {{ $code }}
			</h2>
			<p style="color:#2c3e50;">
				Do not share this OTP with anyone. {{ config('app.name') }} takes your account security very seriously. {{ config('app.name') }} Customer
				Service will never ask you to disclose or verify your {{ config('app.name') }} password, OTP, credit card, or banking
				account number. If you receive a suspicious email with a link to update your account information, do not
				click on the link.
			</p>
		</div>

		<h2 style="display: block; text-align: left; font-size: 15px; font-weight: normal; color:#2c3e50">
			&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</h2>
	</div>
</div>
</body>
</html>
