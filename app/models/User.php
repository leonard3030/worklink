<?php

namespace app\models;

use Carbon\Carbon;
use system\library\DB;
use system\library\Models;

class User extends Models
{
    public function getLoggedUser()
    {
        $token = getBearerToken();
        return $this->verifyUserToken($token);
    }
    public function verifyUserToken($token)
    {
        $user = DB::table('tb_user_access')->where([['access_token', '=', $token]]);
        if ($user->exists()) {
            $token_expire_date = Carbon::parse($user->get()->first()->access_token_expire_date);
            $today_date = Carbon::parse(Carbon::now('+2:00'));
            if ($token_expire_date->greaterThan($today_date)) {
                $data = $this->getUserMinInfo($user->get()->first()->user_id);
                return json_decode(json_encode($data));
            }
        }
        return false;
    }
    public function checkAccountEmail($email, $account_type)
    {
        $user = DB::table('tb_user')->where([['email', '=', $email], ['account_type', '=', $account_type], ['access_type', '=', 'site'], ['is_deleted', '=', false]]);
        if ($user->exists()) {
            $data = $user->get()->first();
            return $this->getUserMinInfo($data->id);
        }
        return false;
    }
    public function getUserMinInfo($id)
    {
        return DB::table('tb_user')->select('id', 'first_name', 'last_name', 'phone', 'email', 'profile_image', 'user_type', 'activated')->where('id', $id)->get()->first();
    }
    public function checkAccount($email, $password)
    {
        $user = DB::table('tb_user')->where([['email', '=', $email], ['account_type', '=', 'cagura'], ['access_type', '=', 'site'], ['is_deleted', '=', false]]);
        if ($user->exists()) {
            $data = $user->get()->first();
            $database_password = $data->password;
            if (password_verify($password, $database_password)) {
                return $this->getUserMinInfo($data->id);
            }
        }
        return false;
    }
    public function updateUserPassword($id, $hashed_pass)
    {
        return DB::table('tb_user')->where('id', '=', $id)->update([
            'password' => $hashed_pass,
        ]);
    }
    public function verifyUserEmail($email)
    {
        $user = DB::table('tb_user')->where('email', $email);
        if ($user->exists()) {
            return $user->get()->first();
        }
        return false;
    }
    public function activateUser($id)
    {
        return DB::table('tb_user')->where('id', $id)->update([
            'activated' => true,
        ]);
    }
    public function verifyUser($token, $type)
    {
        if ($type == 'create') {
            $user = DB::table('tb_user_access')->where([['verify_token', '=', $token]]);
            if ($user->exists()) {
                $token_expire_date = Carbon::parse($user->get()->first()->verify_token_expire_date);
                $today_date = Carbon::parse(Carbon::now('+2:00'));
                if ($token_expire_date->greaterThan($today_date)) {
                    $data = $user->get()->first();
                    $user->delete();
                    return $data;
                }
            }
        } else if ($type == 'reset') {
            $user = DB::table('tb_user_access')->where([['forget_token', '=', $token]]);
            if ($user->exists()) {
                $token_expire_date = Carbon::parse($user->get()->first()->forget_token_expire_date);
                $today_date = Carbon::parse(Carbon::now('+2:00'));
                if ($token_expire_date->greaterThan($today_date)) {
                    $data = $user->get()->first();
                    $user->delete();
                    return $data;
                }
            }
        }
        return false;
    }
    public function addUserAccess($id, $token, $type)
    {
        $today = Carbon::now('+2:00');
        $expire_date = Carbon::parse($today)->add(2, 'day');
        if ($type == 'create') {
            return DB::table('tb_user_access')->insertGetId([
                'user_id' => $id,
                'verify_token' => $token,
                'verify_token_expire_date' => $expire_date,
                'created_at' => $today,
                'updated_at' => $today,
                'is_deleted' => false,
            ]);
        } else if ($type == 'reset') {
            return DB::table('tb_user_access')->insertGetId([
                'user_id' => $id,
                'forget_token' => $token,
                'forget_token_expire_date' => $expire_date,
                'created_at' => $today,
                'updated_at' => $today,
                'is_deleted' => false,
            ]);
        } else if ($type == 'login') {
            $expire_date = Carbon::parse($today)->add(30, 'day');
            return DB::table('tb_user_access')->insertGetId([
                'user_id' => $id,
                'access_token' => $token,
                'access_token_expire_date' => $expire_date,
                'created_at' => $today,
                'updated_at' => $today,
                'is_deleted' => false,
            ]);
        }
    }
    public function addNew($first_name, $last_name, $profile_name, $phone_number, $email, $account_type, $user_type, $access_type, $hashed_pass)
    {

        $today = Carbon::now('+2:00');
        return DB::table('tb_user')->insertGetId([
            'first_name' => $first_name,
            'last_name' => $last_name,
            'profile_image' => $profile_name,
            'phone' => $phone_number,
            'email' => $email,
            'password' => $hashed_pass,
            'account_type' => $account_type,
            'user_type' => $user_type,
            'access_type' => $access_type,
            'activated' => false,
            'created_at' => $today,
            'updated_at' => $today,
            'is_deleted' => false,
        ]);
    }
    public function updateUserData($user_id, $first_name, $last_name, $phone_number, $profile_image)
    {
        $today = Carbon::now('+2:00');
        return DB::table('tb_user')->where('id', $user_id)->update([
            'first_name' => $first_name,
            'last_name' => $last_name,
            'profile_image' => $profile_image,
            'phone' => $phone_number,
            'updated_at' => $today
        ]);
    }
    public function userEmailNotExists($email)
    {
        $count = DB::table('tb_user')->where('email', '=', $email)->count();
        return !$count > 0;
    }
    public function userPhoneNotExists($phone_number)
    {
        $count = DB::table('tb_user')->where('phone', $phone_number)->count();
        return !$count > 0;
    }
    public function getUser()
    {
        $data = DB::table('tb_user')->orderBy('id', 'desc')->get();
        return $data;
    }
};