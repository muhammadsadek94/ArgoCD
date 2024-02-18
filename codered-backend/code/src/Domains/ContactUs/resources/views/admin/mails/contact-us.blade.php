<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="https://fonts.googleapis.com/css?family=Lato:300" rel="stylesheet">
</head>
<body style="font-family: 'Lato', sans-serif; background-color: #01a99c;">
<div style="max-width: 600px; margin: 0 auto;">
    <div style="text-align:center;">
        <img src="{{ url('imgs/logo-white.png') }}" style="height: 100px;  margin:27px auto;">
    </div>
    <div style=" border: 1px solid #ddd; padding: 16px 24px 27px 24px; clear: both;">
        <div style="width: 100%; display: block">

            <h2 style="white-space: pre-line; line-height: 1.8; color:white;margin: 0px auto 20px auto;text-align:center">
                {{$subject}}
            </h2>
            <p>
                {{$message}}
            </p>
        </div>

        <h2 style="display: block; text-align: center; font-size: 15px; font-weight: normal; color: #fff">© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</h2>
    </div>
</div>
</body>
</html>
