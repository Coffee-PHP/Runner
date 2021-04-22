<?php

declare(strict_types=1);

namespace Example2;

class OtherClass
{
    public static function main(): void
    {
        echo 'Sent from ' . static::class . PHP_EOL;
    }
}
