<?php

namespace App\Database;

use App\Entities\Reason;
use App\Entities\Service;

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

    private static $reasonsSeeds = [
        ['reason' => 'No error'],
        ['reason' => 'Client error 4**'],
        ['reason' => 'Client error 5**'],
    ];

    /**
     * Run the seeders.
     */
    public static function run()
    {
        self::seedServicesTable();
        self::seedReasonsTable();
    }

    /**
     * Seed the `services` table.
     */
    private static function seedServicesTable()
    {
        foreach (self::$servicesSeeds as $seed) {
            $seed['token'] = bin2hex(random_bytes(10));
            Service::store($seed);
        }
    }

    /**
     * Seed the `reasons` table.
     */
    private static function seedReasonsTable()
    {
        foreach (self::$reasonsSeeds as $seed) {
            Reason::store($seed);
        }
    }
}