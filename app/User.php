<?php

namespace App;

class User extends JsonModel
{
    protected $columns = ['id', 'name', 'email', 'gender', 'phone', 'address'];
    protected $table = 'users';
}
