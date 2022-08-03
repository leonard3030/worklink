<?php

namespace app\controllers\admin;

use app\models\adminUser;
use system\library\Controller;

class adminUserController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $adminUser = new adminUser();
        $Users = $adminUser->getUsers();
        $responce =  [
            'Users' => $Users,
        ];

        return responce(json_encode($responce), 200);
    }
    public function addUser()
    {
        $names = input('names');
        $username  = input('username');
        $user_image     = input('user_image');
        $password  = input('password');
        $adminUser      = new adminUser();
        $newUser        = $adminUser->addUser($names, $username, $user_image, $password);
        if ($newUser) {
            $responce['status'] = "ok";
            $responce['message'] = "User was saved";
            return responce(json_encode($responce), 202);
        } else {
            $responce['status'] = "bad";
            $responce['message'] = "User was not saved";
            return responce(json_encode($responce), 404);
        }
    }
    public function viewUser($id)
    {
        $adminUser = new adminUser();
        $viewUser = $adminUser->viewUser($id);

        $responce =  [
            'Users' => $viewUser,
        ];

        return responce(json_encode($responce), 200);
    }
    public function updateUser()
    {
        $user_id        = input('user_id');
        $names = input('names');
        $username  = input('username');
        $user_image     = input('user_image');
        $password  = input('password');
        $adminUser      = new adminUser();
        $updateUser     = $adminUser->updateUser($user_id, $names, $username, $user_image, $password);
        if ($updateUser) {
            $responce['status'] = "ok";
            $responce['message'] = "User was updated";
            return responce(json_encode($responce), 202);
        } else {
            $responce['status'] = "bad";
            $responce['message'] = "User was not updated";
            return responce(json_encode($responce), 404);
        }
    }
    public function deleteUser($id)
    {
        $adminUser      = new adminUser();
        $deleteUser     = $adminUser->deleteUser($id);
        if ($deleteUser) {
            $responce['status'] = "ok";
            $responce['message'] = "User was deleted";
            responce(json_encode($responce), 202);
        } else {
            $responce['status'] = "bad";
            $responce['message'] = "User was not deleted";
            responce(json_encode($responce), 404);
        }
    }
};
