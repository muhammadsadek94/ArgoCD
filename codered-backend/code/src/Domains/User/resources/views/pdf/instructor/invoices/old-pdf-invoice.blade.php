<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"
        integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">
    </script>
    <style>
        /*
  Common invoice styles. These styles will work in a browser or using the HTML
  to PDF anvil endpoint.
*/

        @font-face {
            font-family: "Roboto-Regular";
            src: url(/admin/fonts/Roboto-Regular.ttf);
        }

        @font-face {
            font-family: "Roboto-Medium";
            src: url(/admin/fonts/Roboto-Medium.ttf);
        }

        @font-face {
            font-family: "Roboto-Semibold";
            src: url(/admin/fonts/Roboto-Bold.ttf);
        }

        @font-face {
            font-family: "Roboto-Bold";
            src: url(/admin/fonts/Roboto-Black.ttf);
        }

        @font-face {
            font-family: "Medium";
            src: url(/fonts/cerebrisans-medium.eot?c01e4a7dd60f3988a32c1b87e2b05cf0);
            src: local("Cerebri-sans Medium"), url(/fonts/cerebrisans-medium.woff?abe53acee44a549766b43bd32c22c9ce) format("woff");
            font-weight: 500;
        }

        @font-face {
            font-family: "Semibold";
            src: url(/fonts/cerebrisans-semibold.eot?2a1663dd85b07223f419fc0e9b5abb53);
            src: local("Cerebri-sans Semibold"), url(/fonts/cerebrisans-semibold.woff?16e9c1c603cf10ff7ea81296e40c8c1f) format("woff");
            font-weight: 600;
        }

        @font-face {
            font-family: "Bold";
            src: url(/fonts/cerebrisans-bold.eot?43a5f55cc9a045dada7f83636531d112);
            src: local("Cerebri-sans Bold"), url(/fonts/cerebrisans-bold.woff?73d4b8359e1109cdef80c83fac87864f) format("woff");
            font-weight: 700;
        }

        body {
            font-size: 16px;
            padding-right: 64px;
            padding-left: 64px;
            padding-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table tr td {
            padding: 0;
        }

        table tr td:last-child {
            text-align: right;
        }

        .bold {
            font-weight: bold;
            font-family: "Roboto-Bold";
        }

        .medium {
            font-family: "Medium";
            font-weight: 500;
        }


        .right {
            text-align: right;
        }

        .large {
            font-size: 1.75em;
        }

        .total {
            font-weight: bold;
            color: #ff0000;
        }

        .logo-container {
            margin: 20px 0 70px 0;
        }

        .invoice-info-container {
            font-size: 0.875em;
        }

        .invoice-info-container td {
            padding: 4px 0;
        }

        .client-name {
            font-size: 1.5em;
            vertical-align: top;
        }

        .line-items-container {
            margin: 70px 0;
            font-size: 0.875em;
        }

        .line-items-container th {
            text-align: left;
            color: #999;
            border-bottom: 2px solid #ddd;
            padding: 10px 0 15px 0;
            font-size: 0.75em;
            text-transform: uppercase;
        }

        .line-items-container th:last-child {
            text-align: right;
        }

        .line-items-container td {
            padding: 15px 0;
        }

        .line-items-container tbody tr:first-child td {
            padding-top: 25px;
        }

        .line-items-container.has-bottom-border tbody tr:last-child td {
            padding-bottom: 25px;
            border-bottom: 2px solid #ddd;
        }

        .line-items-container.has-bottom-border {
            margin-bottom: 0;
        }

        .line-items-container th.heading-quantity {
            width: 50px;
        }

        .line-items-container th.heading-price {
            text-align: right;
            width: 100px;
        }

        .line-items-container th.heading-subtotal {
            width: 100px;
        }

        .payment-info {
            width: 38%;
            font-size: 0.75em;
            line-height: 1.5;
        }

        .footer {
            margin-top: 100px;
        }

        .footer-thanks {
            font-size: 1.125em;
        }

        .footer-thanks img {
            display: inline-block;
            position: relative;
            top: 1px;
            width: 16px;
            margin-right: 4px;
        }

        .footer-info {
            float: right;
            margin-top: 5px;
            font-size: 0.75em;
            color: #ccc;
        }

        .footer-info span {
            padding: 0 5px;
            color: black;
        }

        .footer-info span:last-child {
            padding-right: 0;
        }


        .page-container {
            display: none;
        }

        tr.spaceUnder>td {
            height: 40px !important;
        }


        .d-font {
            font-size: 11px
        }
    </style>

</head>

<body class="bold">
    <div class="page-container">
        Page
        <span class="page"></span>
        of
        <span class="pages"></span>
    </div>

    <table class="invoice-info-container">
        <thead>
            <tr>
                <td>
                    <img style="width:160px" src="{{ public_path('assets/imgs/EC-Council-logo.png') }}" class=" "
                        alt="">
                </td>
                <td style="font-size:40px; color:#404040; margin-top:10px;" class=" bold ">
                    Royalty statement
                </td>
                <td style="font-size:40px; color:red" class=" bold">
                </td>
                <td style="font-size:40px; color:red" class=" bold">
                </td>
            </tr>
        </thead>
    </table>


    <table class="invoice-info-container">
        <thead>
            <tr>
                <td style="font-size:20px; color:white" class=" fw-bolder">
                    EC-Council
                </td>
                <td style="font-size:20px; color:white" class=" fw-bolder">
                    Royalty statement
                </td>
                <td style="font-size:40px; color:red" class=" fw-bolder">
                </td>
                <td style="font-size:40px; color:red" class=" fw-bolder">
                </td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="d-font bold">
                    EC-Council International Ltd
                </td>
                <td class=" bold">
                </td>
                <td style="font-size: 15px; color: #BB4343;" class=" bold">
                    Date:
                </td>
                <td class="d-font bold">
                    {{ Carbon\Carbon::parse($payout->created_at)->format('M d, Y') }}
                </td>
            </tr>
            <tr>
                <td class=" d-font bold">
                    1202 Capital Square Centre 5-9
                </td>
                <td class=" bold">
                </td>
                <td style="font-size: 15px; color: #BB4343;" class=" bold">
                    Period:
                </td>
                <td class="d-font bold">
                    {{ $payout->period }}
                </td>
            </tr>
            <tr>
                <td class=" d-font bold">
                    989987, Jardines Bazaar, Causeway B
                </td>
                <td class=" bold">
                </td>
                <td style="font-size: 15px; color: #BB4343; height:20px" class=" bold">
                    Course name:
                </td>
                <td class=" d-font bold">
                    {{ $course->name ? $course->name : $course->internal_name }}
                </td>
            </tr>

            <tr>
                <td class="d-font bold">
                    Hong Kong, Hong Kong SAR China
                </td>
                <td class=" bold">
                </td>
                <td class=" bold">
                </td>
                <td>
                </td>
            </tr>

        </tbody>
    </table>

    <!-- Aa992015 -->
    <table class="" style="margin-top: 32px;">
        <thead>
            <tr class="mb-3">
                <td style="font-size: 15px;  color: #BB4343;" class=" bold">
                    Bill to :
                </td>
                <td style="font-size:40px; margin-top:10px;" class=" bold">
                </td>
                <td style="font-size:40px; color:#BB4343" class=" bold">
                </td>
                <td style="font-size:40px; color:#BB4343" class=" bold">
                </td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="d-font bold">
                    {{ $user->first_name }}
                </td>
                <td class=" bold">
                </td>
                <td style="font-size: 20px; color: #f84e4e;" class=" bold">

                </td>
                <td>

                </td>
            </tr>
            <tr>
                <td class="d-font bold">
                    {{ @$user->city->name_en }}
                </td>
                <td class=" bold">
                </td>
                <td style="font-size: 20px; color: #f84e4e;" class=" bold">

                </td>
                <td>

                </td>
            </tr>
            <tr>
                <td class="d-font bold">
                    {{ @$user->country->name_en }}
                </td>
                <td class=" bold">
                </td>
                <td style="font-size: 20px; color: #f84e4e;" class=" bold">

                </td>
                <td>

                </td>
            </tr>
            <tr>
                <td class=" bold">

                </td>
                <td class=" bold">
                </td>
                <td class=" bold">
                </td>
                <td>
                </td>
            </tr>

        </tbody>
    </table>



    <table
        style=" margin-top:50px; padding-right:2px; padding-left:2px; border-left: 3px solid black; border-right: 3px solid black; border-top: 3px solid black; border-bottom: 3px solid black;">
        <tbody>
            <tr style="height: 50px;">
                <td class="d-font bold">
                    <span style="color:white; font-size:20px">1</span>Course Name
                </td>
                <td class="large"> </td>
                <td class="d-font bold">
                    Royalties
                </td>
            </tr>
            <tr style="height: 50px;">
                <td style="width:88%" class="d-font bold">
                    <span style="color:white; font-size:17px">1</span> {{ \Str::limit($course->internal_name, 66) }}
                </td>
                <td class="large"> </td>
                <td style="" class="d-font bold"> $ {{ $royalty }} </td>
            </tr>
        </tbody>
    </table>

    <table
        style=" margin-top:20px; padding-right:2px; padding-left:2px; border-left: 3px solid black; border-right: 3px solid black; border-top: 3px solid black; border-bottom: 3px solid black;">
        <tbody>
            @if ($royalty)
                <tr style="    line-height: 1.2mm;" class="spaceUnder">
                    <td style="width:90%" class="d-font bold">
                        <span style="color:white; font-size:20px">1</span> Total Royalties Earned in this Period <span>
                    </td>
                    <td class="d-font bold">$ {{ $royalty }}</td>
                </tr>
            @endif
            @if ($payout->amount)
                <tr class="spaceUnder">
                    <td class="d-font bold">
                        <span style="color:white; font-size:20px">1</span> Active Subscription Royalties
                    </td>
                    <td class="d-font bold">$ {{ $payout->amount }}</td>
                </tr>
            @endif
            @if ($advances)
                <tr class="spaceUnder">
                    <td class="d-font bold">
                        <span style="color:white; font-size:20px">1</span> Outstanding Advances
                    </td>
                    <td class="d-font bold ">$-{{ $advances }}</td>
                </tr>
            @endif

            @if ($royalties_carried_out)
                <tr class="spaceUnder">
                    <td class="d-font bold">
                        <span style="color:white; font-size:20px">1</span> Royalties carried forward from last quarter
                    </td>
                    <td class="d-font bold ">$ {{ $royalties_carried_out }}</td>
                </tr>
            @endif

            <tr class="spaceUnder">
                <td class="d-font bold">
                    <span style="color:white; font-size:20px">1</span> New Balance
                </td>
                <td class="bold d-font ">$ {{ $royalty - $advances + (int)$royalties_carried_out }}</td>
            </tr>
        </tbody>
    </table>
    <table
        style=" margin-top:20px; padding-right:2px; padding-left:2px; border-left: 3px solid black; border-right: 3px solid black; border-top: 3px solid black; border-bottom: 3px solid black;">
        <tbody>
            <tr>
                <td style="width:86%" class="bold">
                    <span style="color:white; font-size:20px">1</span>TOTAL ROYALTIES PAYABLE
                </td>
                <td class="large"> </td>
                <td class="large total">${{ $payabal + $payout->amount }}</td>
            </tr>
        </tbody>
    </table>
    <table
        style=" margin-top:20px; padding-right:2px; padding-left:2px; border-left: 3px solid black; border-right: 3px solid black; border-top: 3px solid black; border-bottom: 3px solid black;">

        <tbody>
            <tr>
                <td class="bold">
                    <span style="color:white; font-size:20px">1</span> Note: Any Royalties below $100 will be carried
                    forward to the next period
                </td>
            </tr>
        </tbody>
    </table>
</body>
