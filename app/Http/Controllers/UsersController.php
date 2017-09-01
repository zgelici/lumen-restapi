<?php

namespace App\Http\Controllers;

use App\User;

use Illuminate\Http\Request;
use App\Helpers\Response;

class UsersController extends Controller
{
    protected $users;
    protected $request;

    public function __construct(User $users, Request $request)
    {
        $this->users = $users;
        $this->request = $request;
    }

    public function getUsers()
    {
        $users = $this->users->getAll(15, $this->request);
        if ($users) {
            return Response::json($users);
        }
        return Response::internalError('Unable to get the user');
    }

    public function getUser($id)
    {
        $user = $this->users->getOne($id);
        if (!$user) {
            return Response::notFound('user not found');
        }
        return Response::json($user);
    }
//
    public function createUser()
    {
        $validator = $this->validate($this->request, [
            'name' => 'required',
            'email' => 'required',
        ]);
        if ($validator && $validator->errors()->count()) {
            return Response::badRequest($validator->errors());
        }
        $users = $this->users->createData($this->request);
        if ($users) {
            return Response::created($users);
        }
        return Response::internalError('Unable to create the user');
    }

    public function deleteUser($id)
    {
        $user = $this->users->deleteData($id);
        if (!$user) {
            return Response::internalError('Unable to delete the user');
        }
        return Response::deleted();
    }

    public function updateUser($id)
    {
        $user = $this->users->getOne($id);
        if (!$user) {
            return Response::notFound('User not found');
        }

        $user = $this->users->updateData($id, $this->request);
        if ($user) {
            return Response::json($user);
        }
        return Response::internalError('Unable to update the user');
    }
}