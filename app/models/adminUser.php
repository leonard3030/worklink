<?php

namespace app\models;

use system\library\DB;
use system\library\Models;
use Carbon\Carbon;

class adminUser extends Models
{
    public function getUsers()
    {
        $data = DB::table('tb_user')->where('is_deleted', '=', false)->orderBy('user_id', 'desc')->get();
        return $data;
    }
    public function viewUser($id)
    {
        $data = DB::table('tb_user')->where('user_id', $id)->get()->first();
        return $data;
    }
    public function addUser($names, $username, $user_image, $password)
    {
        $today = Carbon::now('+2:00');
        if (DB::table('tb_user')->insert(
            [
                'names' => $names,
                'username' => $username,
                'user_image' => $user_image,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'created_at' => $today,
                'updated_at' => $today,
                'is_deleted' => false,
            ]
        )) {
            if (DB::table('tb_files')->where('file_name', $user_image)->update([
                'is_deleted' => true,
            ])) {
                return true;
            } else {
                return true;
            }
        }
    }
    public function updateUser($user_id, $names, $username, $user_image, $password)
    {
        $today = Carbon::now('+2:00');
        if (DB::table('tb_user')
            ->where('user_id', '=', $user_id)
            ->update(
                [
                    'names' => $names,
                    'username' => $username,
                    'user_image' => $user_image,
                    'password' => password_hash($password, PASSWORD_DEFAULT),
                    'updated_at' => $today,
                ]
            )
        ) {
            if (DB::table('tb_files')->where('file_name', $user_image)->update([
                'is_deleted' => true,
            ])) {
                return true;
            } else {
                return true;
            }
        }
    }
    public function deleteUser($id)
    {
        $today = Carbon::now('+2:00');
        if (
            DB::table('tb_user')->where('user_id', '=', $id)->update(['is_deleted' => true, 'deleted_at' => $today])
        ) {
            return true;
        }
    }
};
