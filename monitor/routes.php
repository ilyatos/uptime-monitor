<?php

use Pecee\SimpleRouter\SimpleRouter;

SimpleRouter::group(['namespace' => 'Monitor\Http\Controllers'], function () {
    SimpleRouter::get('/', function () {
        return 'Home';
    });
    SimpleRouter::post('/service/store', 'ServiceController@store');
    SimpleRouter::get('/service/all', 'ServiceController@index');
});
