<?php
$app->group(['prefix' => 'users'], function() use ($app) {
    $app->get('/', ['uses' => 'UsersController@getUsers', ]);
    $app->get('{id}', ['uses' => 'UsersController@getUser', ]);
    $app->post('/', ['uses' => 'UsersController@createUser', ]);
    $app->delete('{id}', ['uses' => 'UsersController@deleteUser', ]);
    $app->put('{id}', ['uses' => 'UsersController@updateUser', ]);
});


$app->group(['prefix' => 'companies'], function() use ($app) {
    $app->get('/', ['uses' => 'CompaniesController@getCompanies', ]);
    $app->get('{id}', ['uses' => 'CompaniesController@getCompany', ]);
    $app->post('/', ['uses' => 'CompaniesController@createCompany', ]);
    $app->delete('{id}', ['uses' => 'CompaniesController@deleteCompany', ]);
    $app->put('{id}', ['uses' => 'CompaniesController@updateCompany', ]);
});


$app->get('/', function () use ($app) {
    return $app->version();
});
