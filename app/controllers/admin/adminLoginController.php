<?php

namespace app\controllers\admin;

use app\models\adminLogin;
use system\library\Controller;
use system\library\Cookies;

class adminLoginController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        $username        = input('username');
        $password     = input('password');
        $adminLogin         = new adminLogin();
        Cookies::delete('client_logged_data');
        $checkLogin = $adminLogin->checkLogin($username, $password);
        if ($checkLogin) {
            Cookies::add('client_logged_data', json_encode($checkLogin), 10);
            $loggedInId = $checkLogin->user_id;
            $newToken = getToken($length = 200, $type = 'string');
            $token = $adminLogin->setToken($newToken, $loggedInId);
            $responce['status'] = "ok";
            $responce['message'] = "You are successfully logged in";
            $responce['logged_in_user'] = $checkLogin;
            $responce['logged_in_token'] = $newToken;
            responce(json_encode($responce), 202);
        } else {
            $responce['status'] = "bad";
            $responce['message'] = "Email or password are not correct,Try again";
            responce(json_encode($responce), 404);
        }
    }
    public function validateToken()
    {
        $token = input('token');
        $adminLogin         = new adminLogin();
        $validateToken     = $adminLogin->validateToken($token);
        if ($validateToken) {
            $responce['status'] = 'ok';
            $responce['validateToken'] = $validateToken;
            $responce['message'] = "The Token is valid";
            responce(json_encode($responce), 202);
        } else {
            $responce['status'] = 'bad';
            $responce['validateToken'] = $validateToken;
            $responce['message'] = "The Token is not valid";
            responce(json_encode($responce), 404);
        }
    }
};
