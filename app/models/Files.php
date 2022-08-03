<?php

namespace app\models;

use Carbon\Carbon;
use system\library\DB;
use system\library\Models;

class Files extends Models
{
    public $table = '';

    public function __construct()
    {
        $this->table = _env('DB_PREFIX', "") . '_files';
    }
    public function SaveToDb($name, $size, $ext, $v_name, $url, $user_id)
    {
        $today = Carbon::now('+2:00');
        if (DB::table($this->table)->insert(
            [
                'user_id' => $user_id,
                'unique_index' => getToken(10, 'string'),
                'virtual_name' => $v_name,
                'file_name' => $name,
                'file_location' => $url,
                'ext' => $ext,
                'size' => $size,
                'created_at' => $today,
                'updated_at' => $today,
                'is_deleted' => false,
            ]
        )) {
            return true;
        }
    }
    public function GetAllFiles($id)
    {
        return DB::table($this->table)->where([['user_id', '=', $id], ['is_deleted', '=', false]])->orderBy('id', 'desc')->get();
    }
    public function DeleteFile($id)
    {
        $today = Carbon::now('+2:00');
        if (DB::table($this->table)->where([['id', '=', $id]])->update([
            'is_deleted' => true,
            'updated_at' => $today,
        ])) {
            return true;
        };
    }
    public function SearchFile($keyword, $user_id)
    {
        return DB::table($this->table)
            ->Where([['virtual_name', 'like', '%' . $keyword . '%'], ['user_id', $user_id], ['is_deleted', false]])
            ->orWhere([['title', 'like', '%' . $keyword . '%'], ['user_id', $user_id], ['is_deleted', false]])
            ->orWhere([['content', 'like', '%' . $keyword . '%'], ['user_id', $user_id], ['is_deleted', false]])
            ->orWhere([['ext', 'like', '%' . $keyword . '%'], ['user_id', $user_id], ['is_deleted', false]])
            ->orWhere([['keyword', 'like', '%' . $keyword . '%'], ['user_id', $user_id], ['is_deleted', false]])
            ->orderBy('id', 'desc')
            ->get();
    }
    public function SearchFileByType($data, $user_id)
    {
        $files = array();
        foreach ($data as $item) {
            $items = DB::table($this->table)->Where([['ext', '=', $item], ['user_id', $user_id], ['is_deleted', false]])->get();
            foreach ($items as $file) {
                array_push($files, $file);
            }
        }
        return json_encode($files);
    }
    public function updateFileInfo($id, $file_name, $file_title, $file_content, $file_keywords)
    {
        $today = Carbon::now('+2:00');
        if (DB::table($this->table)->where([['id', '=', $id]])->update([
            'virtual_name' => $file_name,
            'title' => $file_title,
            'content' => $file_content,
            'keyword' => $file_keywords,
            'updated_at' => $today,
        ])) {
            return true;
        };
    }
};
