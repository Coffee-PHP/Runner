<?php

declare(strict_types=1);

namespace Example1;

class MainClass
{
    public static function main(): void
    {
        echo 'Sent from ' . static::class . PHP_EOL;
    }
}
