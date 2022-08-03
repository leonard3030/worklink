<?php

namespace app\controllers\api;

use system\library\Controller;
use Carbon\Carbon;
use Mailgun\Mailgun;

class NotificationController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    public function initSendEmail($data)
    {
        $mgClient = Mailgun::create("577d555bf2d426ea251f9e5346935cf0-7cd1ac2b-788d26b3");
        $domain = "mg.triprwanda.rw";
        return $mgClient->messages()->send($domain, $data);
    }
    public function sendVerifyEmail($email, $token)
    {
        $link = _env('APP_URL', "/") . "account/activation/token/" . $token;
        $subject = "Verify your Account";
        $body = '<head>
                <title>Verify Account</title>
                <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
                <meta content="width=device-width" name="viewport">
                <style>
                    @import url("https://fonts.googleapis.com/css2?family=Lato:wght@100;300;400;700;900&display=swap");
                    * {
                        font-family: "Lato", sans-serif;
                        outline: none;
                    }
                    p{
                        color: #6e6e6e;
                        font-size: 17px;
                    }
                </style>
                <style media="screen and (max-width: 680px)">
                    @media screen and (max-width: 680px) {
                        .page-center {
                            padding-left: 50px !important;
                            padding-right: 50px !important;
                        }
                    }
                </style>
            </head>

            <body style="background-color: #f4f4f5;">
                <table cellpadding="0" cellspacing="0" style="width: 100%; height: 100%; background-color: #f4f4f5; text-align: center;">
                    <tbody>
                        <tr>
                            <td style="text-align: center;">
                                <table align="center" cellpadding="0" cellspacing="0" id="body" style="background-color: #fff; width: 100%; max-width: 680px; height: 500px;">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <table align="center" cellpadding="0" cellspacing="0" class="page-center" style="text-align: left;
                                                padding-bottom: 88px;
                                                width: 100%;
                                                padding-left: 80px;
                                                padding-right: 80px;">
                                                    <tbody>
                                                        <tr>
                                                            <td style="padding-top: 53px;">
                                                                <a href="#" style="width: 257px;
                                                                                    background: url(https://cagura.rw/assets/images/logo.png);
                                                                                    display: block;
                                                                                    height: 74px;
                                                                                    background-repeat: no-repeat;
                                                                                    background-position: -29px;"></a>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2" style="padding-top: 40px; -ms-text-size-adjust: 100%; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: 100%; color: #000000; font-size: 48px; font-smoothing: always; font-style: normal; font-weight: 600; letter-spacing: -2.6px; line-height: 52px; mso-line-height-rule: exactly; text-decoration: none;">
                                                                Verify your account
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td style="padding-top: 40px;-ms-text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: 100%; color: #9095a2;font-size: 16px; font-smoothing: always; font-style: normal; font-weight: 400; letter-spacing: -0.18px; line-height: 24px; mso-line-height-rule: exactly; text-decoration: none; vertical-align: top; width: 100%;">
                                                                You are receiving this e-mail because you create account on cagura. we need to know if this is your real account.
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding-top: 24px; -ms-text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: 100%; color: #9095a2; font-size: 16px; font-smoothing: always; font-style: normal; font-weight: 400; letter-spacing: -0.18px; line-height: 24px; mso-line-height-rule: exactly; text-decoration: none; vertical-align: top; width: 100%;">
                                                                Please tap the button below to verify your account.
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <a href="' . $link . '" style="
                                                                margin-top: 36px;
                                                                -webkit-font-smoothing: antialiased;
                                                                color: #ffffff;
                                                                font-size: 12px;
                                                                font-style: normal;
                                                                font-weight: 600;
                                                                letter-spacing: 0.7px;
                                                                line-height: 48px;
                                                                text-decoration: none;
                                                                vertical-align: top;
                                                                width: 220px;
                                                                background-color: #8cc73f;
                                                                border-radius: 5px;
                                                                display: block;
                                                                text-align: center;
                                                                text-transform: uppercase;" target="_blank">
                                                                    ACTIVATE
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                        <td>
                                                            <p>If button on the top not working copy and open this link: ' . $link . '</p>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                            </td>
                        </tr>
                    </tbody>
                </table>
                </td>
                </tr>
                </tbody>
                </table>
            </body>';

        return $this->initSendEmail([
            'from'    => 'Cagura Account <noreply@cagura.rw>',
            'to'      => '<' . $email . '>',
            'subject' => $subject,
            'html'    =>  $body
        ]);
    }
    public function sendResetEmail($data, $email, $token)
    {
        $link = _env('APP_URL', "/") . "account/reset/password/" . $token;
        $subject = "Reset Password";
        $body = '
                <head>
                <title>Reset Password</title>
                <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
                <meta content="width=device-width" name="viewport">
                <style>
                    @import url("https://fonts.googleapis.com/css2?family=Lato:wght@100;300;400;700;900&display=swap");
                    * {
                        font-family: "Lato", sans-serif;
                        outline: none;
                    }
                    p{
                        color: #6e6e6e;
                        font-size: 17px;
                    }
                </style>
                <style media="screen and (max-width: 680px)">
                    @media screen and (max-width: 680px) {
                        .page-center {
                            padding-left: 50px !important;
                            padding-right: 50px !important;
                        }
                    }
                </style>
            </head>

            <body style="background-color: #f4f4f5;">
                <table cellpadding="0" cellspacing="0" style="width: 100%; height: 100%; background-color: #f4f4f5; text-align: center;">
                    <tbody>
                        <tr>
                            <td style="text-align: center;">
                                <table align="center" cellpadding="0" cellspacing="0" id="body" style="background-color: #fff; width: 100%; max-width: 680px; height: 500px;">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <table align="center" cellpadding="0" cellspacing="0" class="page-center" style="text-align: left;
                                                padding-bottom: 88px;
                                                width: 100%;
                                                padding-left: 80px;
                                                padding-right: 80px;">
                                                    <tbody>
                                                        <tr>
                                                        <td style="padding-top: 53px;">
                                                        <a href="#" style="width: 257px;
                                                                            background: url(https://cagura.rw/assets/images/logo.png);
                                                                            display: block;
                                                                            height: 74px;
                                                                            background-repeat: no-repeat;
                                                                            background-position: -29px;"></a>
                                                        </a>
                                                    </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2" style="padding-top: 40px; -ms-text-size-adjust: 100%; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: 100%; color: #000000; font-size: 48px; font-smoothing: always; font-style: normal; font-weight: 600; letter-spacing: -2.6px; line-height: 52px; mso-line-height-rule: exactly; text-decoration: none;">Reset your password</td>
                                                        </tr>

                                                        <tr>
                                                            <td style="padding-top: 40px;-ms-text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: 100%; color: #9095a2;font-size: 16px; font-smoothing: always; font-style: normal; font-weight: 400; letter-spacing: -0.18px; line-height: 24px; mso-line-height-rule: exactly; text-decoration: none; vertical-align: top; width: 100%;">
                                                                You are receiving this e-mail because you requested a password reset for your Cagura account.
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding-top: 24px; -ms-text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: 100%; color: #9095a2; font-size: 16px; font-smoothing: always; font-style: normal; font-weight: 400; letter-spacing: -0.18px; line-height: 24px; mso-line-height-rule: exactly; text-decoration: none; vertical-align: top; width: 100%;">
                                                                Please tap the button below to choose a new password.
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <a href="' . $link . '" style="
                                                                margin-top: 36px;
                                                                -webkit-font-smoothing: antialiased;
                                                                color: #ffffff;
                                                                font-size: 12px;
                                                                font-style: normal;
                                                                font-weight: 600;
                                                                letter-spacing: 0.7px;
                                                                line-height: 48px;
                                                                text-decoration: none;
                                                                vertical-align: top;
                                                                width: 220px;
                                                                background-color: #8cc73f;
                                                                border-radius: 5px;
                                                                display: block;
                                                                text-align: center;
                                                                text-transform: uppercase;" target="_blank">
                                                        Reset Password
                                                    </a>
                                                            </td>
                                                        </tr>
                                                        <td>
                                                        <p>If button on the top not working copy and open this link: ' . $link . '</p>
                                                       </td>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                            </td>
                        </tr>
                    </tbody>
                </table>
                </td>
                </tr>
                </tbody>
                </table>
            </body>
                    ';


        return $this->initSendEmail([
            'from'    => 'Cagura Account <noreply@cagura.rw>',
            'to'      => $data->first_name . ' ' . $data->last_name . ' <' . $email . '>',
            'subject' => $subject,
            'html'    =>  $body
        ]);
    }
    public function sendContactMessage()
    {
        $message = input('message');
        $from_email = input('email');
        $today = Carbon::now('+2:00');
        $date = Carbon::parse($today)->isoFormat('MMMM Do YYYY, h:mm A');
        $email = 'ajahwin@gmail.com';
        $subject = "Cagura client message: " . $date;
        $body = '<head>
                <title>Cagura shop message</title>
                <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
                <meta content="width=device-width" name="viewport">
                <style>
                    @import url("https://fonts.googleapis.com/css2?family=Lato:wght@100;300;400;700;900&display=swap");
                    * {
                        font-family: "Lato", sans-serif;
                        outline: none;
                    }
                    p{
                        color: #6e6e6e;
                        font-size: 17px;
                    }
                </style>
                <style media="screen and (max-width: 680px)">
                    @media screen and (max-width: 680px) {
                        .page-center {
                            padding-left: 50px !important;
                            padding-right: 50px !important;
                        }
                    }
                </style>
            </head>

            <body style="background-color: #f4f4f5;">
                <table cellpadding="0" cellspacing="0" style="width: 100%; height: 100%; background-color: #f4f4f5; text-align: center;">
                    <tbody>
                        <tr>
                            <td style="text-align: center;">
                                <table align="center" cellpadding="0" cellspacing="0" id="body" style="background-color: #fff; width: 100%; max-width: 680px; height: 500px;">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <table align="center" cellpadding="0" cellspacing="0" class="page-center" style="text-align: left;
                                                padding-bottom: 88px;
                                                width: 100%;
                                                padding-left: 80px;
                                                padding-right: 80px;">
                                                    <tbody>
                                                        <tr>
                                                            <td style="padding-top: 53px;">
                                                                <a href="#" style="width: 257px;
                                                                                    background: url(https://cagura.rw/assets/images/logo.png);
                                                                                    display: block;
                                                                                    height: 74px;
                                                                                    background-repeat: no-repeat;
                                                                                    background-position: -29px;"></a>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2" style="padding-top: 40px; -ms-text-size-adjust: 100%; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: 100%; color: #000000; font-size: 48px; font-smoothing: always; font-style: normal; font-weight: 600; letter-spacing: -2.6px; line-height: 52px; mso-line-height-rule: exactly; text-decoration: none;">
                                                               Message From ' . $from_email . '
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td style="padding-top: 40px;-ms-text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: 100%; color: #9095a2;font-size: 16px; font-smoothing: always; font-style: normal; font-weight: 400; letter-spacing: -0.18px; line-height: 24px; mso-line-height-rule: exactly; text-decoration: none; vertical-align: top; width: 100%;">
                                                               ' . $message . '
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                           
                                                        </tr>
                                                        <tr>
                                                            
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                            </td>
                        </tr>
                    </tbody>
                </table>
                </td>
                </tr>
                </tbody>
                </table>
            </body>';

        if ($this->initSendEmail([
            'from'    => 'Cagura Messages <noreply@cagura.rw>',
            'to'      => '<' . $email . '>',
            'subject' => $subject,
            'html'    =>  $body
        ])) {
            $response = [
                'status' => 'ok',
                'message' => 'Message sent, Thank for your feedback',
            ];
            return responce(json_encode($response), 201);
        }

        $response = [
            'status' => 'bad',
            'message' => 'Error while receiving your message, Try again!!!',
        ];
        return responce(json_encode($response), 400);
    }
};