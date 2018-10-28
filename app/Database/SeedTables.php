<?php

namespace App\Database;

use App\Entities\Service;
use Monitor\Monitor;

class SeedTables
{
    /**
     * Write here your seeds.
     */
    private static $servicesSeeds = [
        ['alias' => 'Hawk', 'url' => 'https://hawk.so'],
        ['alias' => 'ITMO', 'url' => 'http://www.ifmo.ru/ru/'],
        ['alias' => 'Pik', 'url' => 'https://pikabu.ru'],
        ['alias' => 'Main CodeX', 'url' => 'https://ifmo.su/'],
        ['alias' => 'NF CodeX', 'url' => 'https://ifmo.su/ad404'],
        ['alias' => 'Capella', 'url' => 'https://capella.pics'],
        ['alias' => 'Unreachable service', 'url' => 'https://unworks.com']
    ];

    /**
     * Run the seeders.
     */
    public static function run()
    {
        self::seedServicesTable();
    }

    /**
     * Seed the `services` table.
     */
    private static function seedServicesTable()
    {
        foreach (self::$servicesSeeds as $seed) {
            Service::store($seed);

            $createdService = Service::find()->where([['url' => $seed['url']]])->get();

            $monitorInstance = new Monitor();
            $monitorInstance->runForOne($createdService);
        }
    }
}
