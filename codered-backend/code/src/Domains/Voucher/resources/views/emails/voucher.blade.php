<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h1>Hi, {{ $admin->name }},</h1>
    <p>The vouchers file is attached.</p>
    
    <h2 style="display: block; text-align: center; font-size: 15px; font-weight: normal; color: #1F1F1F">© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</h2>
</body>
</html>