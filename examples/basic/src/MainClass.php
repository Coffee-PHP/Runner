<?php

declare(strict_types=1);

namespace Example;

class MainClass
{
    public static function main(): void
    {
        echo 'Sent from ' . static::class . PHP_EOL;
    }
}
