<?php

namespace App;

class Company extends JsonModel
{
    protected $columns = ['id', 'name', 'email', 'address', 'phone'];
    protected $table = 'companies';
}
