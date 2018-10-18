<?php

use Pecee\SimpleRouter\SimpleRouter;

SimpleRouter::group(['namespace' => 'Monitor\Http\Controllers'], function () {
    SimpleRouter::get('/', function () {
        return 'Home';
    });
    SimpleRouter::get('/service/all', 'ServiceController@index');
    SimpleRouter::get('/service/{id}/delete', 'ServiceController@delete');
    SimpleRouter::post('/service/store', 'ServiceController@store');

});
