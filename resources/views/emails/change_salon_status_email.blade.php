<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="x-apple-disable-message-reformatting" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title></title>
    <style type="text/css" rel="stylesheet" media="all">
        @import url("https://fonts.googleapis.com/css?family=Nunito+Sans:400,700&amp;display=swap");
    </style>

    <style type="text/css" rel="stylesheet" media="all">
        body {
            width: 100% !important;
            height: 100vh;
            margin: 0;
            -webkit-text-size-adjust: none;
        }
        body {
            font-family: "Nunito Sans", Helvetica, Arial, sans-serif;
        }
        body {
            background-color: #F2F4F6;
            color: #51545E;
            z-index: -1;
        }

        html,
        body {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        span {
            color: #5ccffb;
        }
        @media (max-width: 1138px) {

        }
        @media (max-width: 768px) {
            body {
                height: auto;
            }
            .mb-m-0{
                margin: 0 !important;
            }
            img{
                padding-top: 10px;
            }
        }
    </style>
</head>

<body style="
      width: 100% !important;
      background: #f0f6ff;
      -webkit-text-size-adjust: none; font-family: &quot;Nunito Sans&quot;, Helvetica, Arial, sans-serif;
        margin: 0;">

<section style="margin: auto 10%;padding: 15px 0;" class="mb-m-0">
    <div style="margin: 0 auto;
        position: relative;
        text-align: center;">

        <div style="text-align: center;margin-bottom: 15px;">
            <img src="{{env('CLIENT_URL')}}/assets/images/web/logo.jpg">
        </div>
        <div style="background: white;padding: 5% 8%;border-top: 4px solid #635ebe;
            border-bottom: 4px solid #635ebe70;">
            <h4 style="margin: 20px 0;font-size: 23px;">Hi {{ $details['salon']->name }}, </h4>
            @if($details['salon']->publish == 0)
                <h2 style="margin: 0 0 8px 0;font-size: 35px;">Oops, your salon has been Rejected</h2>
                <div style="margin: 40px 0 4px;text-align: center;">
                    We just rejected your {{ $details['salon']->name }} salon. You can contact our team
                    <a href="{{ env('MAIL_FROM_ADDRESS') }}" target="_blank">{{env('APP_NAME')}}</a>
                </div>
            @elseif($details['salon']->publish != 0)
                <h2 style="margin: 0 0 8px 0;font-size: 35px;" >Congratulations, your salon has been Activated</h2>

                <div style="margin: 40px 0 4px;text-align: center;">
                    We just accepted your {{ $details['salon']->name }} salon. You can proceed with us.
                    <a href="{{ env('CLIENT_URL') }}" target="_blank">{{env('APP_NAME')}}</a>
                </div>
            @endif

            <br>

            <h4 style="font-size: 20px;">The {{env('APP_NAME')}} Team</h4>

        </div>
    </div>

</section>

</body>

</html>
