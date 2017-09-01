<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Storage;

class JsonModel extends Model
{
    protected $data;
    protected $columns = [];
    protected $table = '';

    public function __construct()
    {
        $path = storage_path() . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . $this->table . '.json';
        $data = json_decode(file_get_contents($path), true);
        $this->data = collect($data);
    }

    public function getAll($perPage = 15, $page = null)
    {
        $page = $page->get('page') > 0 ? $page->get('page') : null;
        return $this->customPaginate($this->data, $perPage, $page);
    }

    public function getOne($id)
    {
        $users = $this->data->keyBy('id');
        return $users->get($id);
    }

    public function createData($input)
    {
        $object = new \stdClass;

        foreach ($this->columns as $column => $data) {
            $object->{$data} = '';
        }

        foreach ($input->all() as $column => $data) {
            if (in_array($column, $this->columns)) {
                $object->{$column} = $data;
            }
        }
        # TODO:: CHECK KEY EXISTS
        if ($this->data->last()) {
            $object->id = $this->data->last()['id'] + 1;
        }

        $this->data->push($object);

        $file = storage_path() . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . $this->table . '.json';
        file_put_contents($file, json_encode($this->data->all()));
        return $this->$object;
    }

    public function updateData($id, $input)
    {
        $users = $this->data->keyBy('id');
        $user = (object)$users->get($id);
        foreach ($input->all() as $column => $data) {
            if (in_array($column, $this->columns)) {
                $user->{$column} = $data;
            }
        }
        # TODO: REMOVE ARRAY FOR MUATION
        $users = $this->data->toArray();
        $users[$id] = (array)$user;
        $this->saveJson(collect($data->all()));
        return $user;
    }

    public function deleteData($id)
    {
        $users = $this->data->keyBy('id');
        $remove = $users->forget($id);
        $this->saveJson($remove->all());
        if ($remove) {
            return true;
        }
        return false;
    }

    private function saveJson($data)
    {
        $file = storage_path() . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . $this->table . '.json';
        file_put_contents($file, json_encode($data));
    }

    private function customPaginate($items, $perPage = 15, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}
