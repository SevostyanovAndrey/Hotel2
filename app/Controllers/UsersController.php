<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Users;

class UsersController extends BaseController
{
    public function index()
    {
        return view('index');
    }

    public function addUser()
    {
        $request = service('request');

        $data = [
            'name' => $request->getPost('name'),
            'email' => $request->getPost('email'),
            'city' => $request->getPost('city')
        ];

        $usersModel = new Users();

        $usersModel->insert($data);

        $response = [
            'status' => 'success',
            'message' => 'Данные успешно добавлены'
        ];

        return $this->response->setJSON($response);
    }

    public function getUsers()
    {

        $request = service('request');
        $postData = $request->getPost();
        $dtpostData = $postData['data'];
        $response = array();


        $draw = $dtpostData['draw'];
        $start = $dtpostData['start'];
        $rowperpage = $dtpostData['length'];
        $columnIndex = $dtpostData['order'][0]['column'];
        $columnName = $dtpostData['columns'][$columnIndex]['data'];
        $columnSortOrder = $dtpostData['order'][0]['dir'];
        $searchValue = $dtpostData['search']['value'];


        $users = new Users();
        $totalRecords = $users->select('id')
            ->countAllResults();

        $totalRecordwithFilter = $users->select('id')
            ->orLike('name', $searchValue)
            ->orLike('email', $searchValue)
            ->orLike('city', $searchValue)
            ->countAllResults();

        $records = $users->select('*')
            ->orLike('name', $searchValue)
            ->orLike('email', $searchValue)
            ->orLike('city', $searchValue)
            ->orderBy($columnName, $columnSortOrder)
            ->findAll($rowperpage, $start);

        $data = array();

        foreach ($records as $record) {

            $data[] = array(
                "id" => $record['id'],
                "name" => $record['name'],
                "email" => $record['email'],
                "city" => $record['city'],
                "actions" => "<button class='btnDelete' value='" . $record['id'] . "'>&#10008;</button>"
            );
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data,
            "token" => csrf_hash()
        );

        return $this->response->setJSON($response);
    }

    public function delete($userId)
    {
        $request = service('request');

        $usersModel = new Users();

        $usersModel->delete($userId);

        $response = [
            'status' => 'success',
            'message' => 'Запись успешно удалена'
        ];

        return $this->response->setJSON($response);
    }
}
