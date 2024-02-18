<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="https://fonts.googleapis.com/css?family=Lato:300" rel="stylesheet">
    <style>
        .url-login {
            display: inline-block;
            font-weight: 400;
            color: white;
            text-align: center;
            vertical-align: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
            border: 1px solid transparent;
            padding: .375rem .75rem;
            font-size: 1rem;
            line-height: 1.5;
            border-radius: .25rem;
            transition: color .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out;
            background-color: #e83c30;
            padding-left: 6rem !important;
            padding-right: 6rem !important;
            padding-top: .5rem !important;
            padding-bottom: .5rem !important;
            text-decoration: none;
        }
    </style>
</head>
<body style="font-family: 'Lato', sans-serif; background-color: #ecf0f1;">
<div style="margin: 0 auto;">

    <div style=" padding: 0px 24px 27px 57px; clear: both;">
        <div style="text-align:left;">
            <img src="{{ asset('assets/imgs/Red logo.png') }}" style="height: 40px;  margin:27px auto 0px auto">
        </div>
        <div style="width: 100%; display: block">

            <h2 style="white-space: pre-line; line-height: 1.8; color:#2c3e50;margin: 0px auto 20px auto;">
                {{-- {{ $head }} --}}
            </h2>
            <p style="color:#2c3e50;">

                <p style="color:#2c3e50;">
                    <strong>Email Address:</strong>
                    <span> {{$user['email']}} </span>
                </p>
                <p style="color:#2c3e50;">
                    <strong>Password:</strong>
                    <span> {{ $password }}</span>
                </p>
                <p>
                    <b>
                        @if($user['type'] == \App\Domains\User\Enum\UserType::USER)
                            <a class="url-login" href="{{ config('user.user_website') . '/login' }}">Login</a>
                        @else
                            <a class="url-login" href="{{ config('user.instructor_dashboard') . '/login' }}">Login</a>
                        @endif
                    </b>
                </p>

            </p>
        </div>

        <h2 style="display: block; text-align: left; font-size: 15px; font-weight: normal; color: #2c3e50">
            You can always create a new password whenever you want from your "Account Settings"
        </h2>

        <h2 style="display: block; text-align: left; font-size: 15px; font-weight: normal; color: #2c3e50">
            If you have any issues signing in to your account, please reach out to us at
            <a href="mailto:Learnersupport@eccouncil.org">Learnersupport@eccouncil.org</a>
        </h2>
        <h2 style="display: block; text-align: left; font-size: 15px; font-weight: normal; color: #2c3e50">
            Thank you, <br>
            Hannah <br>
            Customer Relationship Executive
        </h2>

    </div>
</div>
</body>
</html>
