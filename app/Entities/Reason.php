<?php

namespace App\Entities;

use Core\BaseEntity;

class Reason extends BaseEntity
{
    /**
     * Returns the table name.
     *
     * @return string
     */
    protected static function getTableName()
    {
        return 'reasons';
    }

    /**
     * @param string $reasonIn
     *
     * @return int
     */
    public static function findOrCreateReasonId(string $reasonIn): int
    {
        if (!self::existsWhere('reason', $reasonIn)) {
            self::store(['reason' => $reasonIn]);
        }

        $reasonOut = self::find()->where([['reason' => $reasonIn]])->get();

        return $reasonOut['id'];
    }
}
