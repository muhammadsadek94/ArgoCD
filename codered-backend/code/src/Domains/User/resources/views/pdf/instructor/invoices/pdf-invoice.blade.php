<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
    <link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Royalty Statement</title>
    <style type="text/css">
        * {
            margin: 0;
            padding: 0;
            text-indent: 0;
        }

        h1 {
            color: #2F333A;
            font-family: 'Roboto', sans-serif;
            font-style: normal;
            font-weight: bold;
            text-decoration: none;
            font-size: 19.5pt;
        }

        .s1 {
            font-family: 'Roboto', sans-serif;
        }

        .s2 {
            color: #231F20;
            font-family: 'Roboto', sans-serif;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 13.5pt;
        }

        .s3 {
            color: #231F20;
            font-family: 'Roboto', sans-serif;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 15.5pt;
        }

        .s4 {
            color: #2F333A;
            font-family: 'Roboto', sans-serif;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 9.5pt;
        }

        .s5 {
            color: #2F333A;
            font-family: 'Roboto', sans-serif;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 10pt;
        }

        .s6 {
            color: #FFF;
            font-family: 'Roboto', sans-serif;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 12pt;
        }

        .s7 {
            color: #231F20;
            font-family: 'Roboto', sans-serif;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 10pt;
        }

        h2 {
            color: #231F20;
            font-family: 'Roboto', sans-serif;
            font-style: normal;
            font-weight: bold;
            text-decoration: none;
            font-size: 13pt;
        }

        .s8 {
            color: #2F333A;
            font-family: 'Roboto', sans-serif;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 13pt;
        }

        p {
            color: #8A8C8E;
            font-family: 'Roboto', sans-serif;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 8.5pt;
            margin: 0pt;
        }

        table,
        tbody {
            vertical-align: top;
            overflow: visible;
        }
    </style>
</head>

<body>
    <table style="margin-left: 18px">
        <thead>
            <tr>
                <td style="width:320pt;">
                    <img style="width:160px" src="{{ public_path('assets/imgs/EC-Council-logo.png') }}" class=" "
                        alt="">
                </td>
                <td style="float: right; padding-top: 16px; padding-left: 20px">
                    <h2 style="text-indent: 0pt;float: right; text-align: left; font-size:22px">Royalty Statement</h2>
                </td>
            </tr>
        </thead>
    </table>


    <p style="text-indent: 0pt;text-align: left;"><br /></p>

    <table style="border-collapse:collapse;margin-left: 18px" cellspacing="0">
        <tbody>
            <tr>
                <td class="s1"
                    style="padding-top: 4pt;text-indent: 0pt;text-align: left; width:120px; font-weight: bold">
                    Date:
                </td>
                <td class="s1" style="padding-top: 4pt;text-indent: 0pt;text-align: left; font-weight: bold;">
                    {{ Carbon\Carbon::parse($payout->created_at)->format('M d, Y') }}
                </td>
            </tr>
            <tr>
                <td class="s1"
                    style="padding-top: 4pt;text-indent: 0pt;text-align: left; width:120px; font-weight: bold">
                    Period:
                </td>
                <td class="s1" style="padding-top: 4pt;text-indent: 0pt;text-align: left; font-weight: bold;">
                    {{ $payout->period }}
                </td>
            </tr>
            <tr>
                <td class="s1"
                    style="padding-top: 4pt;text-indent: 0pt;text-align: left; width:120px; font-weight: bold">
                    Course Name:
                </td>
                <td class="s1" style="padding-top: 4pt;text-indent: 0pt;text-align: left; font-weight: bold;">
                    {{ $course->name ? $course->name : $course->internal_name }}
                </td>
            </tr>
        </tbody>
    </table>

    <p style="text-indent: 0pt;text-align: left;"><br /></p>
    <p style="text-indent: 0pt;text-align: left;"><br /></p>

    <div style="background:#E3E3E3; padding:15px 20px 15px 20px">
        <table style="" cellspacing="0">
            <tr style="height:43pt;">
                <td style="width:240pt; border-right: 3px solid #e83c30">
                    <h2 class="s1" style="padding-left: 23pt;text-indent: 0pt;text-align: left; font-weight: 200;">
                        Bill to:
                    </h2>
                    <p style="color: #E3E3E3; font-size:5px">asd</p>

                    <h2 class="s1" style="padding-left: 31pt;text-indent: 100pt;text-align: left;">
                        {{ $user->full_name }}
                    </h2>
                    <p class="s1" style="padding-top: 2pt;padding-left: 31pt;text-indent: 0pt;text-align: left;">
                        {{ $user->instructor_profile?->billing_address }}
                    </p>
                    <p class="s1" style="padding-top: 2pt;padding-left: 31pt;text-indent: 0pt;text-align: left;">
                        {{ $user->city?->name_en }}
                        {{ $user->country?->name_en }}
                    </p>
                </td>
                <td style="width:200pt; padding-left: 50px;">
                    <p class="s5" style="padding-left: 31pt;text-indent: 0pt;line-height: 129%;text-align: left;">
                        EC-Council
                        International Ltd, 1202 Capital Square Centre 5-9</p>
                    <p class="s5" style="padding-left: 31pt;text-indent: 0pt;line-height: 129%;text-align: left;">
                        989987, Jardines
                        Bazaar, Causeway B Hong Kong, Hong Kong SAR China</p>
                </td>
            </tr>
        </table>
    </div>

    <p class="s1" style="padding-top: 4pt;text-indent: 0pt;line-height: 133%;text-align: left;">
    </p>

    <p style="text-indent: 0pt;text-align: left;"><br /></p>

    <p style="text-indent: 0pt;text-align: left;"><br /></p>
    <p style="text-indent: 0pt;text-align: left;"><br /></p>
    <table style="border-collapse:collapse" cellspacing="0">
        <tr style="height:60pt">
            <td style="width:410pt; padding:15px 0px 15px 25px" bgcolor="#2F333A">
                <p class="s6" style="padding-top: 11px;padding-left: 23px;text-indent: 0pt;text-align: left;">
                    Royalty
                    Breakdown</p>
            </td>
            <td style="width:100pt; padding:15px 0px 15px 0px" bgcolor="#2F333A">
                <p class="s6" style="padding-top: 11pt;padding-right: 25pt;text-indent: 0pt;text-align: right;">
                    Royalties</p>
            </td>
        </tr>
        <tr style="height:60pt">
            <td style="width:410pt; padding:15px 0px 15px 25px">
                <p class="s7" style="padding-top: 11px;padding-left: 23px;text-indent: 0pt;text-align: left;">
                    Total Royalties Earned in this Period
                </p>
            </td>
            <td style="width:100pt; padding:15px 0px 15px 0px">
                <p class="s7" style="padding-top: 11pt;padding-right: 25pt;text-indent: 0pt;text-align: right;">
                    $ {{ $royalty }}
                </p>
            </td>
        </tr>
        <tr style="height:60pt">
            <td style="width:410pt; padding:15px 0px 15px 25px" bgcolor="#E3E3E3">
                <p class="s7" style="padding-top: 11px;padding-left: 23px;text-indent: 0pt;text-align: left;">
                    Outstanding Advances
                </p>
            </td>
            <td style="width:100pt; padding:15px 0px 15px 0px" bgcolor="#E3E3E3">
                <p class="s7" style="padding-top: 11pt;padding-right: 25pt;text-indent: 0pt;text-align: right;">
                    -${{ $advances }}
                </p>
            </td>
        </tr>
        <tr style="height:60pt">
            <td style="width:410pt; padding:15px 0px 15px 25px">
                <p class="s7" style="padding-top: 11px;padding-left: 23px;text-indent: 0pt;text-align: left;">
                    Royalties carried forward from last quarter
                </p>
            </td>
            <td style="width:100pt; padding:15px 0px 15px 0px">
                <p class="s7" style="padding-top: 11pt;padding-right: 25pt;text-indent: 0pt;text-align: right;">
                    $ {{ $royalties_carried_out }}
                </p>
            </td>
        </tr>
        <tr style="height:31pt">
            <td style="width:410pt;padding:15px 0px 15px 25px;border-bottom-style:solid;border-bottom-width:3pt;border-bottom-color:#ED1B34"
                bgcolor="#E3E3E3">
                <p class="s7" style="padding-top: 8pt;padding-left: 23pt;text-indent: 0pt;text-align: left;">New
                    Balance
                </p>
            </td>
            <td style="width:100pt;padding:15px 0px 15px 0px;border-bottom-style:solid;border-bottom-width:3pt;border-bottom-color:#ED1B34"
                bgcolor="#E3E3E3">
                <p class="s7" style="padding-top: 11pt;padding-right: 25pt;text-indent: 0pt;text-align: right;">
                    $ {{ $royalty - $advances + (int)$royalties_carried_out }}
                </p>
            </td>
        </tr>
        <tr style="height:60pt;">
            <td style="width:410pt; padding:25px 0px 15px 35px">
                <p class="s7"
                    style="padding-top: 11px;padding-left: 23px;text-indent: 0pt;text-align: left; font-weight: bold; font-size: 18px">
                    Total Royalties Payable</p>
            </td>
            <td style="width:100pt; padding:25px 0px 15px 0px">
                <p class="s7"
                    style="padding-top: 11pt;padding-right: 25pt;text-indent: 0pt;text-align: right; font-weight: bold; font-size: 18px">
                    ${{ $payabal + $payout->amount }}
                </p>
            </td>
        </tr>
    </table>
    <p style="text-indent: 0pt;text-align: left;"><br /></p>
    <p style="text-indent: 0pt;text-align: left;"><br /></p>
    <p style="text-indent: 0pt;text-align: left;"><br /></p>
    <p style="text-indent: 0pt;text-align: left;"><br /></p>
    <p style="text-indent: 0pt;text-align: left;"><br /></p>

    <p class="s7"
        style="padding-top: 4pt;padding-left: 28pt;text-indent: 0pt;text-align: left; font-weight: bold; font-size: 16px">
        Note:
    </p>
    <p style="padding-top: 1pt;padding-left: 28pt;text-indent: 0pt;text-align: left;">Any Royalties below $100 will be
        carried forward to the next period.
    </p>

    <p style="text-indent: 0pt;text-align: left;"><br /></p>
    <p style="text-indent: 0pt;text-align: left;"><br /></p>
    <p style="text-indent: 0pt;text-align: left;"><br /></p>
    <p style="text-indent: 0pt;text-align: left;"><br /></p>
    <p style="text-indent: 0pt;text-align: left;"><br /></p>
    <p style="text-indent: 0pt;text-align: left;"><br /></p>
    <p style="text-indent: 0pt;text-align: left;"><br /></p>
    <p style="text-indent: 0pt;text-align: left;"><br /></p>
    <p style="text-indent: 0pt;text-align: left;"><br /></p>
    <p style="text-indent: 0pt;text-align: left;"><br /></p>
    <p style="text-indent: 0pt;text-align: left;"><br /></p>
    <p style="text-indent: 0pt;text-align: left;"><br /></p>
    <p style="text-indent: 0pt;text-align: left;"><br /></p>
    <p style="text-indent: 0pt;text-align: left;"><br /></p>
    <p style="padding-top: 8pt;text-indent: 0pt;text-align: center;">
        This is a computer generated statement. No signature required.
    </p>
</body>

</html>
