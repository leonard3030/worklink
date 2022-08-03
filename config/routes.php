<?php

use system\library\Router;

/* -----------------------------
 * This is router array, please don't change any key just add values
 * -----------------------------
 */

$routes = [

    // SITE API
    [
        'path' => '/api/v1',
        "children" => [
            [
                'path' => '/',
                'method' => 'GET',
                'folder' => 'api',
                'return' => 'UserController@index',
            ],
        ],
    ],

    // ADMIN API
    [
        'path' => '/api/v1/admin',
        "children" => [
            [
                'path' => '/getPeople',
                'method' => 'GET',
                'folder' => 'admin',
                'return' => 'adminPeopleController@getPeople',
            ],
            [
                'path' => '/viewPerson/{id}',
                'method' => 'GET',
                'folder' => 'admin',
                'return' => 'adminPeopleController@viewPerson',
            ],
            [
                'path' => '/addPerson',
                'method' => 'POST',
                'folder' => 'admin',
                'return' => 'adminPeopleController@addPerson',
            ],
            [
                'path' => '/updatePerson',
                'method' => 'POST',
                'folder' => 'admin',
                'return' => 'adminPeopleController@updatePerson',
            ],
            [
                'path' => '/deletePerson/{id}',
                'method' => 'GET',
                'folder' => 'admin',
                'return' => 'adminPeopleController@deletePerson',
            ],
        ],
    ],





    // UPLOAD HELPER API
    [
        'path' => "/upload",
        // 'middleware' => 'web',
        "children" => [
            [
                'path' => '/{token}',
                'method' => 'POST',
                'folder' => 'helper',
                'return' => 'Uploader@uploadNow',
            ],
            [
                'path' => '/chunk',
                'method' => 'POST',
                'folder' => 'helper',
                'return' => 'Uploader@startUploadChunk',
            ],
            [
                'path' => '/all-files/{token}',
                'method' => 'GET',
                'folder' => 'helper',
                'return' => 'Uploader@allFiles',
            ],
            [
                'path' => '/delete/{id}',
                'method' => 'DELETE',
                'folder' => 'helper',
                'return' => 'Uploader@delete',
            ],
            [
                'path' => '/search/{token}',
                'method' => 'POST',
                'folder' => 'helper',
                'return' => 'Uploader@search',
            ],
            [
                'path' => '/search-by-type/{token}',
                'method' => 'POST',
                'folder' => 'helper',
                'return' => 'Uploader@searchByType',
            ],
            [
                'path' => 'upload/update-file/{id}',
                'method' => 'POST',
                'folder' => 'helper',
                'return' => 'Uploader@updateFileInfo',
            ],
        ],
    ],

    // NOT FOUND ROUTER
    [
        'path' => '*',
        'folder' => 'site',
        'return' => 'PagesController@index',
    ],
];

/* -----------------------------
 * This is condition array, please don't change any key just add values
 * This will be user if you want to redirect all route to the same controller function
 * -----
 * This condition will be good for Vue,Angular,React
 * -----
 * Let that framework to handle routes
 * ----
 * You can enable or disable this condition
 * ------------------------------
 */
$route_condition = [
    'ENABLED' => true,
    'ALL_TO' => [
        'folder' => 'site',
        'return' => 'HomeController@index',
    ],
    'EXCEPT' => ['/api/v1', '/api/v1/admin', '/api/v1/writer', '/upload'],
];

// Init routing
Router::init($routes, $route_condition);
