<?php

namespace app\controllers\api;

use app\models\User;
use Google_Client;
use Google_Service_Oauth2;
use system\library\Controller;
use system\library\FacebookApi;
use app\controllers\api\NotificationController;

class UserController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $response = [
            'status' => 'bad',
            'message' => 'You are not allowed to access this link',
        ];
        return responce(json_encode($response), 403);
    }
    public function store()
    {
        $first_name = input('first_name');
        $last_name = input('last_name');
        $phone_number = input('phone_number');
        $email = input('email');
        $password = input('password');
        $account_type = 'cagura';
        $user_type = input('user_type');
        $access_type = 'site';
        $hashed_pass = password_hash($password, PASSWORD_DEFAULT);
        if ($user_type == 'sale' || $user_type == 'client') {
            if (!empty($first_name) && !empty($last_name)) {
                if (validate_phone_number($phone_number)) {
                    if (validate_email($email)) {
                        if (isStrong($password)) {
                            $user = new User();
                            if ($user->userEmailNotExists($email)) {
                                if ($user->userPhoneNotExists($phone_number)) {
                                    if ($id = $user->addNew($first_name, $last_name, "", $phone_number, $email, $account_type, $user_type, $access_type, $hashed_pass)) {
                                        // Send verify email
                                        $token = getToken($length = 20, $type = 'string');
                                        $user->addUserAccess($id, $token, 'create');
                                        $notification = new NotificationController();
                                        if ($notification->sendVerifyEmail($email, $token)) {
                                            $response = [
                                                'status' => 'ok',
                                                'message' => 'Account was created, Go to your email account to verify that is your account, This email may take between 1 second to 3 minutes.',
                                            ];
                                            return responce(json_encode($response), 201);
                                        } else {
                                            $response = [
                                                'status' => 'bad',
                                                'message' => 'Server error, Try again',
                                            ];
                                        }
                                    } else {
                                        $response = [
                                            'status' => 'bad',
                                            'message' => 'Error while creating account, Try again',
                                        ];
                                    }
                                } else {
                                    $response = [
                                        'status' => 'bad',
                                        'message' => 'Phone already exists',
                                    ];
                                }
                            } else {
                                $response = [
                                    'status' => 'bad',
                                    'message' => 'Email already exists',
                                ];
                            }
                        } else {
                            $response = [
                                'status' => 'bad',
                                'message' => 'Password not strong. At least 6 character length, include at least one number and must include at least one letter!',
                            ];
                        }
                    } else {
                        $response = [
                            'status' => 'bad',
                            'message' => 'Email is incorrect',
                        ];
                    }
                } else {
                    $response = [
                        'status' => 'bad',
                        'message' => 'Phone is incorrect',
                    ];
                }
            } else {
                $response = [
                    'status' => 'bad',
                    'message' => 'First or Last names can not be empty',
                ];
            }
        } else {
            $response = [
                'status' => 'bad',
                'message' => "You didn't select if you are sale or client",
            ];
        }
        return responce(json_encode($response), 400);
    }
    public function verifyAccount()
    {
        $token = input('token');
        if ($token) {
            $user = new User();
            if ($data = $user->verifyUser($token, 'create')) {
                $id = $data->user_id;
                $user->activateUser($id);
                $response = [
                    'status' => 'ok',
                    'message' => 'Account was activated, Please login now',
                ];
                return responce(json_encode($response), 200);
            } else {
                $response = [
                    'status' => 'bad',
                    'message' => "Account not activated, try again",
                ];
                return responce(json_encode($response), 400);
            }
        } else {
            $response = [
                'status' => 'bad',
                'message' => "Token Not found, try again",
            ];
            return responce(json_encode($response), 400);
        }
    }
    public function verifyEmail()
    {
        $email = input('email');
        if (validate_email($email)) {
            $user = new User();
            if ($data = $user->verifyUserEmail($email)) {
                $token = getToken($length = 20, $type = 'string');
                $id = $data->id;
                $user->addUserAccess($id, $token, 'reset');
                $notification = new NotificationController();
                if ($notification->sendResetEmail($data, $email, $token)) {
                    $response = [
                        'status' => 'ok',
                        'message' => 'Check your email to reset your password',
                    ];
                    return responce(json_encode($response), 200);
                } else {
                    $response = [
                        'status' => 'bad',
                        'message' => 'Server error, Try again',
                    ];
                    return responce(json_encode($response), 400);
                }
            } else {
                $response = [
                    'status' => 'bad',
                    'message' => 'You email not found in cagura',
                ];
                return responce(json_encode($response), 400);
            }
        } else {
            $response = [
                'status' => 'bad',
                'message' => 'Email is incorrect',
            ];
            return responce(json_encode($response), 400);
        }
    }
    public function resetPassword()
    {
        $token = input('token');
        $password = input('password');
        if (isStrong($password)) {
            $hashed_pass = password_hash($password, PASSWORD_DEFAULT);
            $user = new User();
            if ($data = $user->verifyUser($token, 'reset')) {
                $id = $data->user_id;
                if ($user->updateUserPassword($id, $hashed_pass)) {
                    $response = [
                        'status' => 'ok',
                        'message' => 'Password was now changed, Login now',
                    ];
                    return responce(json_encode($response), 200);
                }
            } else {
                $response = [
                    'status' => 'bad',
                    'message' => 'Error whire verifying your account',
                ];
                return responce(json_encode($response), 400);
            }
        } else {
            $response = [
                'status' => 'bad',
                'message' => 'Password not strong. At least 6 character length, include at least one number and must include at least one letter!',
            ];
            return responce(json_encode($response), 400);
        }
    }
    public function accountLogin()
    {
        $email = input('email');
        $password = input('password');
        $user = new User();
        if ($data = $user->checkAccount($email, $password)) {
            $id = $data->id;
            $token = getToken($length = 500, $type = 'string');
            if ($data->activated) {
                if ($user->addUserAccess($id, $token, 'login')) {
                    $response = [
                        'status' => 'ok',
                        'message' => 'Welcome to your account',
                        'access_token' => $token,
                        'user' => $data,
                    ];
                    return responce(json_encode($response), 200);
                }
            } else {
                $response = [
                    'status' => 'bad',
                    'user' => $data,
                    'message' => 'You account is not activated, Go to your email to activated it',
                ];
                $token = getToken($length = 20, $type = 'string');
                $user->addUserAccess($id, $token, 'create');
                $notification = new NotificationController();
                if ($notification->sendVerifyEmail($email, $token)) {
                    return responce(json_encode($response), 400);
                }
            }
        } else {
            $response = [
                'status' => 'bad',
                'message' => 'Email or Password is not correct',
            ];
            return responce(json_encode($response), 400);
        }
    }
    public function resendVerifyEmail()
    {
        $email = input('email');
        $token = getToken($length = 20, $type = 'string');
        $user = new User();
        if ($info = $user->checkAccountEmail($email, 'cagura')) {
            $id = $info->id;
            $user->addUserAccess($id, $token, 'create');
            $notification = new NotificationController();
            if ($notification->sendVerifyEmail($email, $token)) {
                $response = [
                    'status' => 'ok',
                    'message' => 'Verfication email is now sent, Go to your email to activated it.',
                ];
                return responce(json_encode($response), 200);
            }
        }
        $response = [
            'status' => 'bad',
            'message' => 'Email not sent again.',
        ];
        return responce(json_encode($response), 400);
    }
    public function authGoogle()
    {
        $type = input('type');
        $google_client = new Google_Client();
        if ($type == 'login') {
            $google_client->setClientId('991641544958-vs8hsvu9fc8d01na0vjum3tov86ecmo8.apps.googleusercontent.com');
            $google_client->setClientSecret('Svz_BwKMEe1NM2TzN0jM7yQp');
            $google_client->setRedirectUri('https://cagura.rw/account/auth/login/google');
            $google_client->addScope('email');
            $google_client->addScope('profile');
            $url = $google_client->createAuthUrl();
            $response = [
                'status' => 'ok',
                'message' => 'Auth url created',
                'url' => $url,
            ];
            return responce(json_encode($response), 200);
        } else if ($type == 'register') {
            $google_client->setClientId('991641544958-vs8hsvu9fc8d01na0vjum3tov86ecmo8.apps.googleusercontent.com');
            $google_client->setClientSecret('Svz_BwKMEe1NM2TzN0jM7yQp');
            $google_client->setRedirectUri('https://cagura.rw/account/auth/register/google');
            $google_client->addScope('email');
            $google_client->addScope('profile');
            $url = $google_client->createAuthUrl();
            $response = [
                'status' => 'ok',
                'message' => 'Auth url created',
                'url' => $url,
            ];
            return responce(json_encode($response), 200);
        }
    }
    public function authLoginConfirmGoogle()
    {
        $code = input('code');
        $google_client = new Google_Client();
        $google_client->setClientId('991641544958-vs8hsvu9fc8d01na0vjum3tov86ecmo8.apps.googleusercontent.com');
        $google_client->setClientSecret('Svz_BwKMEe1NM2TzN0jM7yQp');
        $google_client->setRedirectUri('https://cagura.rw/account/auth/login/google');
        $google_client->addScope('email');
        $google_client->addScope('profile');
        $token = $google_client->fetchAccessTokenWithAuthCode($code);
        $google_client->setAccessToken($token['access_token']);
        $google_service = new Google_Service_Oauth2($google_client);
        $data = $google_service->userinfo->get();
        // login
        if ($data->verifiedEmail) {
            $user = new User();
            if ($info = $user->checkAccountEmail($data->email, 'google')) {
                $id = $info->id;
                $token = getToken($length = 500, $type = 'string');
                if ($user->addUserAccess($id, $token, 'login')) {
                    $response = [
                        'status' => 'ok',
                        'message' => 'Welcome to your account',
                        'access_token' => $token,
                        'user' => $info,
                    ];
                    return responce(json_encode($response), 200);
                }
            } else {
                $response = [
                    'status' => 'bad',
                    'message' => 'Your Google account not found in cagura, create one!',
                ];
                return responce(json_encode($response), 400);
            }
        } else {
            $response = [
                'status' => 'bad',
                'message' => 'Your Google email not verifyed',
            ];
            return responce(json_encode($response), 400);
        }
    }
    public function authRegisterConfirmGoogle()
    {
        $code = input('code');
        $google_client = new Google_Client();
        $google_client->setClientId('991641544958-vs8hsvu9fc8d01na0vjum3tov86ecmo8.apps.googleusercontent.com');
        $google_client->setClientSecret('Svz_BwKMEe1NM2TzN0jM7yQp');
        $google_client->setRedirectUri('https://cagura.rw/account/auth/register/google');
        $google_client->addScope('email');
        $google_client->addScope('profile');
        $token = $google_client->fetchAccessTokenWithAuthCode($code);
        $google_client->setAccessToken($token['access_token']);
        $google_service = new Google_Service_Oauth2($google_client);
        $data = $google_service->userinfo->get();
        // register
        if ($data->verifiedEmail) {
            $user = new User();
            if ($info = $user->checkAccountEmail($data->email, 'google')) {
                $id = $info->id;
                $token = getToken($length = 500, $type = 'string');
                if ($user->addUserAccess($id, $token, 'login')) {
                    $response = [
                        'status' => 'ok',
                        'message' => 'You already have an account in cagura',
                        'access_token' => $token,
                        'user' => $info,
                    ];
                    return responce(json_encode($response), 200);
                }
            } else {
                // Register now
                $first_name = $data->givenName;
                $last_name = $data->familyName;
                $email = $data->email;
                $picture_url = $data->picture;
                $id = $data->id;
                $account_type = 'google';
                $user_type = input('user_type');
                $access_type = 'site';
                $profile_name = $id . '_google.png';
                if ($user->userEmailNotExists($email)) {
                    download($picture_url, 'assets/uploaded/', $profile_name);
                    if ($id = $user->addNew($first_name, $last_name, $profile_name, "", $email, $account_type, $user_type, $access_type, "google")) {
                        $token = getToken($length = 500, $type = 'string');
                        if ($user->addUserAccess($id, $token, 'login')) {
                            $response = [
                                'status' => 'ok',
                                'message' => 'Now your account is created',
                                'access_token' => $token,
                                'user' =>  $user->checkAccountEmail($email, 'google'),
                            ];
                            return responce(json_encode($response), 200);
                        }
                    };
                } else {
                    $response = [
                        'status' => 'bad',
                        'message' => 'Email already exists',
                    ];
                    return responce(json_encode($response), 400);
                }
            }
        } else {
            $response = [
                'status' => 'bad',
                'message' => 'Your Google email not verifyed',
            ];
            return responce(json_encode($response), 400);
        }
    }
    public function authFacebook()
    {
        $type = input('type');
        $facebook = new FacebookApi([
            'app_id' => '655653188381043',
            'app_secret' => 'd80e6d3adec5c9306f30ba1a750227e3',
            'graph_version' => 'v7.0',
        ]);
        if ($type == 'login') {
            $facebook->setRedirectUrl('https://cagura.rw/account/auth/login/facebook');
            $facebook_login_url = $facebook->getFacebookLoginUrl();
        } else if ($type == 'register') {
            $facebook->setRedirectUrl('https://cagura.rw/account/auth/register/facebook');
            $facebook_login_url = $facebook->getFacebookLoginUrl();
        }
        $response = [
            'status' => 'ok',
            'message' => 'Auth url created',
            'url' => $facebook_login_url,
        ];
        return responce(json_encode($response), 200);
    }
    public function authLoginConfirmFacebook()
    {
        $facebook = new FacebookApi([
            'app_id' => '655653188381043',
            'app_secret' => 'd80e6d3adec5c9306f30ba1a750227e3',
            'graph_version' => 'v7.0',
        ]);
        $code = input('code');
        $facebook->setRedirectUrl('https://cagura.rw/account/auth/login/facebook');
        $access_token_response = $facebook->getAccessTokenWithCode($code);
        $fb_response = $access_token_response->fb_response;
        $access_token = $fb_response->access_token;
        $data = $facebook->getFacebookUserInfo($access_token)->fb_response;
        $user = new User();
        if ($info = $user->checkAccountEmail($data->email, 'facebook')) {
            $id = $info->id;
            $token = getToken($length = 500, $type = 'string');
            if ($user->addUserAccess($id, $token, 'login')) {
                $response = [
                    'status' => 'ok',
                    'message' => 'Welcome to your account',
                    'access_token' => $token,
                    'user' => $info,
                ];
                return responce(json_encode($response), 200);
            }
        } else {
            $response = [
                'status' => 'bad',
                'message' => 'Your Facebook account not found in cagura, create one !',
            ];
            return responce(json_encode($response), 400);
        }
    }
    public function authRegisterConfirmFacebook()
    {
        $facebook = new FacebookApi([
            'app_id' => '655653188381043',
            'app_secret' => 'd80e6d3adec5c9306f30ba1a750227e3',
            'graph_version' => 'v7.0',
        ]);
        $code = input('code');
        $facebook->setRedirectUrl('https://cagura.rw/account/auth/register/facebook');
        $access_token_response = $facebook->getAccessTokenWithCode($code);
        $fb_response = $access_token_response->fb_response;
        $access_token = $fb_response->access_token;
        $data = $facebook->getFacebookUserInfo($access_token)->fb_response;

        $user = new User();
        if ($info = $user->checkAccountEmail($data->email, 'google')) {
            $id = $info->id;
            $token = getToken($length = 500, $type = 'string');
            if ($user->addUserAccess($id, $token, 'login')) {
                $response = [
                    'status' => 'ok',
                    'message' => 'You already have an account in cagura',
                    'access_token' => $token,
                    'user' => $info,
                ];
                return responce(json_encode($response), 200);
            }
        } else {
            // Register now
            $first_name = $data->first_name;
            $last_name = $data->last_name;
            $email = $data->email;
            $picture_url = $data->picture->data->url;
            $id = $data->id;
            $account_type = 'facebook';
            $user_type = input('user_type');
            $access_type = 'site';
            $profile_name = $id . '_facebook.png';
            if ($user->userEmailNotExists($email)) {
                download($picture_url, 'assets/uploaded/', $profile_name);
                if ($id = $user->addNew($first_name, $last_name, $profile_name, "", $email, $account_type, $user_type, $access_type, "facebook")) {
                    $token = getToken($length = 500, $type = 'string');
                    if ($user->addUserAccess($id, $token, 'login')) {
                        $response = [
                            'status' => 'ok',
                            'message' => 'Now your account is created',
                            'access_token' => $token,
                            'user' => $info = $user->checkAccountEmail($email, 'facebook'),
                        ];
                        return responce(json_encode($response), 200);
                    }
                };
            } else {
                $response = [
                    'status' => 'bad',
                    'message' => 'Email already exists',
                ];
                return responce(json_encode($response), 400);
            }
        }
    }
    public function verifyToken()
    {
        $token = input('token', "");
        $user = new User();
        if ($user->verifyUserToken($token)) {
            $user_info = $user->getLoggedUser();
            $response = [
                'status' => 'ok',
                'message' => 'Welcome again',
                'return' => $user_info,
            ];
            return responce(json_encode($response), 200);
        } else {
            $response = [
                'status' => 'remove',
                'message' => 'Token expired',
            ];
            return responce(json_encode($response), 200);
        }
    }
    public function updateUserInfo()
    {
        $first_name = input('first_name');
        $last_name = input('last_name');
        $phone_number = input('phone_number');
        $profile_image = input('profile_image');
        $user = new User();
        $user_info = $user->getLoggedUser();
        if ($user->updateUserData($user_info->id, $first_name, $last_name, $phone_number, $profile_image)) {
            $response = [
                'status' => 'ok',
                'message' => 'You information is now updated',
                'return' => $user_info,
            ];
            return responce(json_encode($response), 200);
        }
        $response = [
            'status' => 'bad',
            'message' => 'Error while saving user information, Try again',
            'return' => $user_info,
        ];
        return responce(json_encode($response), 400);
    }
};