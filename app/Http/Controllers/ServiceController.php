<?php

namespace App\Http\Controllers;

use App\Entities\Reason;
use App\Entities\Response;
use App\Entities\Service;
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

        $services = Service::all(['id', 'alias', 'url']);

        foreach ($services as $service) {
            $serviceLastResponse = Response::find(['availability', 'reason_id'])
                ->where([['service_id' => $service['id']]])
                ->orderBy('id', 'DESC')
                ->limit(1)->get();

            $reason = Reason::find(['reason'])->where([['id' => $serviceLastResponse['reason_id']]])->get();

            $result[] = [
                'id' => $service['id'],
                'alias' => $service['alias'],
                'url' => $service['url'],
                'availability' => $serviceLastResponse['availability'],
                'reason' => $reason['reason']
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

        if (Service::existsWhere('url', $values['url'])) {
            return ApiResponse::error(400, 'Duplicate entry.');
        }

        Service::store($values);

        $createdService = Service::find()->where([['url' => $values['url']]])->get();

        $monitorInstance = new Monitor();
        $monitorInstance->runForOne($createdService);

        return ApiResponse::success(201);
    }

    /**
     * Delete the given service by $id.
     *
     * @param int $id
     * @return string
     */
    public function delete(int $id)
    {
        if (Service::delete()->where([['id' => $id]])->executeWithRowCount() === 0) {
            return ApiResponse::error(400);
        }

        return ApiResponse::success(200);
    }
}
