<?php

namespace app\controllers\helper;

use app\models\Files;
use app\models\User;
use app\models\AdminWritter;
use SplFileInfo;
use system\library\Controller;
use system\library\Session;
use system\library\Upload;

class Uploader extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function uploadNow($token)
    {
        $files = Upload::dir("/assets/uploaded/")->param("file")->randomName(true)->Start();
        foreach ($files as $index => $file) {
            $file_name = $file->name;
            $v_name = $file->realname;
            $size = $file->size;
            $url = $file->url;
            $fileinfo = new SplFileInfo($file_name);
            $ext = strtolower($fileinfo->getExtension());

            $AdminWritter = new AdminWritter();
            $user_info = $AdminWritter->getLoggedUser($token);
            if ($token != 'empty') {
                $id = $user_info->id;
            } else {
                $id = null;
            }
            // $user = new User();
            // $user_info = $user->getLoggedUser();
            $files_model = new Files();
            $files_model->SaveToDb($file_name, $size, $ext, $v_name, $url, $id);
        }
        $responce['message'] = "uploaded";
        $responce['data'] = $files;
        responce(json_encode($responce), 200);
    }
    public function allFiles($token)
    {
        $files_model = new Files();
        $AdminWritter = new AdminWritter();
        $data = $AdminWritter->getLoggedUser($token);
        if ($data) {
            echo $files_model->GetAllFiles($data->id);
        } else {
            echo $files_model->GetAllFiles(null);
        }
    }
    public function delete($id)
    {
        $files_model = new Files();
        // $user = new User();
        // $user_info = $user->getLoggedUser();
        if ($files_model->DeleteFile($id)) {
            $responce['message'] = "File was deleted";
            responce(json_encode($responce), 200);
        }
    }
    public function search($token)
    {
        $files_model = new Files();
        $keyword = input('keyword');
        // $user = new User();
        // $user_info = $user->getLoggedUser();
        $AdminWritter = new AdminWritter();
        $user = $AdminWritter->getLoggedUser($token);
        if ($user) {
            echo $files_model->SearchFile($keyword, $user->id);
        } else {
            echo $files_model->SearchFile($keyword, null);
        }
    }
    public function searchByType($token)
    {
        $files_model = new Files();
        $AdminWritter = new AdminWritter();
        $keywords = input('keyword');
        $data = explode(',', $keywords);
        $user = $AdminWritter->getLoggedUser($token);
        if ($user) {
            echo $files_model->SearchFileByType($data, $user->id);
        } else {
            echo $files_model->SearchFileByType($data, null);
        }
        // $user = new User();
        // $user_info = $user->getLoggedUser();
    }
    public function updateFileInfo($id)
    {
        $files_model = new Files();
        $file_name = input('file_name');
        $file_title = input('file_title');
        $file_content = input('file_content');
        $file_keywords = input('file_keywords');
        // $user = new User();
        // $user_info = $user->getLoggedUser();
        if ($files_model->updateFileInfo($id, $file_name, $file_title, $file_content, $file_keywords)) {
            $responce['message'] = "Info was updated";
            responce(json_encode($responce), 200);
        } else {
            $responce['message'] = "Failed to update";
            responce(json_encode($responce), 400);
        }
    }
    public function startUploadChunk()
    {

        $phase = input('phase');
        if ($phase == 'start') {
            $mime_type = input('mime_type');
            $v_name = input('name');
            $size = input('size');
            $session_id = getToken(10, 'string');
            $fileinfo = new SplFileInfo($v_name);
            $ext = strtolower($fileinfo->getExtension());
            $name = getToken(40, 'string') . '.' . $ext;
            $file_to_upload = array(
                'mime_type' => $mime_type,
                'v_name' => $v_name,
                'name' => $name,
                'ext' => $ext,
                'phase' => $phase,
                'size' => $size,
                'chucks' => [],
            );

            Session::init()->add($session_id, json_encode($file_to_upload));
            $chuck = array(
                "data" => array(
                    "end_offset" => 2000000,
                    "session_id" => $session_id,
                ),
                "status" => "success",
            );
            responce(json_encode($chuck), 200);
        } else if ($phase == 'upload') {
            $session_id = input('session_id');
            $start_offset = input('start_offset');
            $file_to_upload = json_decode(Session::init()->get($session_id));
            $files = Upload::dir("/assets/uploaded/tmp/" . $session_id . "/")->param("chunk")->randomName(true)->Start();
            foreach ($files as $index => $file) {
                $file_name = $file->name;
                $v_name = $file->realname;
                $size = $file->size;
                $obj = array(
                    'name' => $file_name,
                    'start_offset' => $start_offset,
                    'size' => $size,
                );
                array_push($file_to_upload->chucks, $obj);
            }
            Session::init()->add($session_id, json_encode($file_to_upload));

            $output = ROOT_FOLDER . "/assets/uploaded/" . $file_to_upload->name;
            if ($this->joinFiles($session_id, $file_name, $output)) {
                $result = array(
                    "status" => "success",
                );
                responce(json_encode($result), 200);
            }
        } else if ($phase == 'finish') {
            $session_id = input('session_id');
            $file_to_upload = json_decode(Session::init()->get($session_id));
            $result = array(
                "status" => "success",
            );
            $this->deleteDir(ROOT_FOLDER . "/assets/uploaded/tmp/" . $session_id);
            $v_name = $file_to_upload->v_name;
            $file_name = $file_to_upload->name;
            $size = $file_to_upload->size;
            $url = "large_file";
            $fileinfo = new SplFileInfo($v_name);
            $ext = strtolower($fileinfo->getExtension());
            $files_model = new Files();
            $AdminWritter = new AdminWritter();
            $user_info = $AdminWritter->getLoggedUser();
            $files_model->SaveToDb($file_name, $size, $ext, $v_name, $url, $user_info->id);
            responce(json_encode($result), 200);
        } else {
            $result = array(
                "status" => "error",
            );
            responce(json_encode($result), 401);
        }
    }

    public function joinFiles($session_id, $tmp_file_name, $outputPath)
    {
        $chunk_file = ROOT_FOLDER . "/assets/uploaded/tmp/" . $session_id . "/" . $tmp_file_name;
        $file = fopen($chunk_file, "rb");
        $buff = fread($file, filesize($chunk_file));
        fclose($file);
        $final = fopen($outputPath, 'ab');
        $write = fwrite($final, $buff);
        fclose($final);
        return true;
    }
    public function deleteDir($dirPath)
    {
        if (!is_dir($dirPath)) {
            throw new InvalidArgumentException("$dirPath must be a directory");
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                self::deleteDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
    }
};
