<?php

namespace App\Http\Controllers;

use App\Models\Reason;
use App\Models\Response;
use App\Models\Service;
use App\Http\ApiResponse;
use Monitor\Monitor;

class ServiceController
{
    /**
     * Get services with status.
     *
     * @return string
     */
    public function index()
    {
        $result = [];

        $services = Service::all();

        foreach ($services as $service) {
            /** @var Response $serviceLastResponse */
            $serviceLastResponse = Response::query()->where('service_id', '=', $service->id)
                ->orderBy('id', 'desc')
                ->first();

            /** @var Reason $reason */
            $reason = $serviceLastResponse->reason;

            $result[] = [
                'id' => $service->id,
                'alias' => $service->alias,
                'url' => $service->url,
                'availability' => $serviceLastResponse->availability,
                'reason' => $reason->reason
            ];
        }

        return ApiResponse::success(200, $result);
    }

    /**
     * Store the given service and check the response.
     *
     * @return string
     */
    public function store()
    {
        $values = [
            'alias' => input()->post('alias'),
            'url' => input()->post('url')
        ];

        if (Service::query()->where('url', '=', $values['url'])->exists()) {
            return ApiResponse::error(400, 'Duplicate entry.');
        }

        $service = Service::create($values);

        $monitorInstance = new Monitor();
        $monitorInstance->runForOne($service);

        return ApiResponse::success(201);
    }

    /**
     * Delete the given service by $id.
     *
     * @param int $id
     *
     * @return string
     */
    public function delete(int $id)
    {
        if (!Service::find($id)->delete()) {
            return ApiResponse::error(400);
        }

        return ApiResponse::success(200);
    }
}
