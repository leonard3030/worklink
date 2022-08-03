<?php

namespace app\controllers\admin;

use app\models\adminPeople;
use system\library\Controller;

class adminPeopleController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getPeople()
    {
        $adminPeople = new adminPeople();
        $responce = $adminPeople->getPeople();
        return responce(json_encode($responce), 200);
    }
    public function viewPerson($id)
    {
        $adminPeople = new adminPeople();
        $responce = $adminPeople->viewPerson($id);
        return responce(json_encode($responce), 200);
    }
    public function addPerson()
    {
        $names = input('names');
        $company  = input('company');
        $adminPeople      = new adminPeople();
        $newUser        = $adminPeople->addPerson($names, $company);
        if ($newUser) {
            $responce['status'] = "ok";
            $responce['message'] = "Link was created";
            return responce(json_encode($responce), 202);
        } else {
            $responce['status'] = "bad";
            $responce['message'] = "Link was not created";
            return responce(json_encode($responce), 404);
        }
    }
    public function updatePerson()
    {
        $id = input('id');
        $names = input('names');
        $company  = input('company');
        $adminPeople      = new adminPeople();
        $newUser        = $adminPeople->updatePerson($id, $names, $company);
        if ($newUser) {
            $responce['status'] = "ok";
            $responce['message'] = "Person was updated";
            return responce(json_encode($responce), 202);
        } else {
            $responce['status'] = "bad";
            $responce['message'] = "Person was not updated";
            return responce(json_encode($responce), 404);
        }
    }
    public function deletePerson($id)
    {
        $adminPeople      = new adminPeople();
        $newUser        = $adminPeople->deletePerson($id);
        if ($newUser) {
            $responce['status'] = "ok";
            $responce['message'] = "Person was deleted";
            return responce(json_encode($responce), 202);
        } else {
            $responce['status'] = "bad";
            $responce['message'] = "Person was not deleted";
            return responce(json_encode($responce), 404);
        }
    }
};
