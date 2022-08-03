<?php

namespace app\models;

use system\library\DB;
use system\library\Models;
use Carbon\Carbon;

class adminLogin extends Models
{
    public function checkLogin($username, $password)
    {
        $today = Carbon::now('+2:00');
        $checkUser = DB::table('tb_user')->where([
            ['username', '=', $username]
        ]);
        $user_exists = $checkUser->exists();
        if ($user_exists) {
            $data = DB::table('tb_user')->where([
                ['username', '=', $username]
            ])->get()->first();
            $hashed_password = $data->password;
            if (password_verify($password, $hashed_password)) {
                return $data;
            }
        } else {
            return null;
        }
    }
    public function setToken($newToken, $loggedInId)
    {
        if (DB::table('tb_user')
            ->where([['user_id', '=', $loggedInId]])
            ->update(['token' => $newToken])
        ) {
            return $newToken;
        }
        return null;
    }
    public function validateToken($token)
    {
        $user = DB::table('tb_user')->where([['token', '=', $token]]);
        if ($user->exists()) {
            return true;
        }
        return false;
    }
};
