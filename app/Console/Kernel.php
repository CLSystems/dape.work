<?php

namespace App\Console;

use App\Console\Commands\AISanityTest;
use App\Console\Commands\BackfillMonetizationKey;
use App\Console\Commands\GenerateKnowledge;

class Kernel
{
    protected $commands = [
        AISanityTest::class,
        GenerateKnowledge::class,
        BackfillMonetizationKey::class,
    ];

}
