<?php

namespace app\models;

use system\library\DB;
use system\library\Models;
use Carbon\Carbon;

class adminPeople extends Models
{
    public function getPeople()
    {
        $data = DB::table('tb_links')->where('is_deleted', '=', false)->orderBy('id', 'desc')->get();
        $num = 1;
        foreach ($data as $key => $value) {
            $value->num = $num;
            $num++;
        }
        return $data;
    }
    public function viewPerson($id)
    {
        $data = DB::table('tb_links')->where('id', $id)->get()->first();
        return $data;
    }
    public function addPerson($names, $company)
    {
        $today = Carbon::now('+2:00');
        if (DB::table('tb_links')->insert(
            [
                'names' => $names,
                'company' => $company,
                'created_at' => $today,
                'updated_at' => $today,
                'is_deleted' => false,
            ]
        )) {
            return true;
        } else {
            return true;
        }
    }

    public function updatePerson($id, $names, $company)
    {
        $today = Carbon::now('+2:00');
        if (DB::table('tb_links')->where('id', $id)->update(
            [
                'names' => $names,
                'company' => $company,
                'updated_at' => $today,
            ]
        )) {
            return true;
        } else {
            return true;
        }
    }
    public function deletePerson($id)
    {
        $today = Carbon::now('+2:00');
        if (DB::table('tb_links')->where('id', $id)->update(
            [
                'is_deleted' => true,
                'deleted_at' => $today,
            ]
        )) {
            return true;
        } else {
            return true;
        }
    }
};
