<?php

namespace App\Enums;

class TaskStatus
{
    const TODO = 1;
    const IN_PROGRESS = 2;
    const DONE = 3;

    public static function all()
    {
        return [
            self::TODO,
            self::IN_PROGRESS,
            self::DONE,
        ];
    }
}