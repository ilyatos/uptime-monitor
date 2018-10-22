<?php

use Pecee\SimpleRouter\SimpleRouter;

SimpleRouter::group(['namespace' => 'App\Http\Controllers'], function () {
    SimpleRouter::get('/', function () {
        return 'Home';
    });
    SimpleRouter::get('/service/all', 'ServiceController@index');
    SimpleRouter::get('/service/{id}/delete', 'ServiceController@delete')->where(['id' => '[0-9]+']);
    SimpleRouter::post('/service/store', 'ServiceController@store');
});
