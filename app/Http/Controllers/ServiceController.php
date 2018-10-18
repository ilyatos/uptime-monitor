<?php

namespace App\Http\Controllers;

use App\Entities\Service;
use App\Http\ApiResponse;

class ServiceController
{
    public function index()
    {
        return ApiResponse::success(200, Service::all());
    }

    public function store()
    {
        $input = input();

        Service::save([
            'alias' => $input['alias'],
            'url' => $input['url']
        ]);

        return ApiResponse::success(201);
    }

    public function delete($id)
    {
        return ApiResponse::success(204);
    }
}