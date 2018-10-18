<?php

namespace Monitor\Database;

use Monitor\Entities\Service;

class SeedTables
{
    private static $servicesSeeds = [
        ['alias' => 'Hawk', 'url' => 'https://hawk.so'],
        ['alias' => 'ITMO', 'url' => 'http://www.ifmo.ru/ru/'],
        ['alias' => 'Pik', 'url' => 'https://pikabu.ru'],
        ['alias' => 'Main CodeX', 'url' => 'https://ifmo.su/'],
        ['alias' => 'Capella', 'url' => 'https://capella.pics'],
    ];

    public static function run()
    {
        self::seedServicesTable();
    }

    public static function seedServicesTable()
    {
        foreach (self::$servicesSeeds as $seed) {
            Service::store($seed);
        }
    }
}