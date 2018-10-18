<?php

namespace Monitor\Http\Controllers;

use Monitor\Entities\Service;
use Monitor\Http\ApiResponse;

class ServiceController
{
    public function index()
    {
        return ApiResponse::success(200, Service::all());
    }

    public function store()
    {
        $input = input();

        Service::store();

        return ApiResponse::success(201);
    }

    public function delete($id)
    {
        return ApiResponse::success(204);
    }
}