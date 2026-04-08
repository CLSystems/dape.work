<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GenerationSeed;

class ElasticsearchErrorSeedSeeder extends Seeder
{
    public function run(): void
    {
        $seeds = require database_path(
            'seeders/data/elasticsearch_errors.php'
        );

        foreach ($seeds as $seed) {
            GenerationSeed::firstOrCreate(
                [
                    'slug' => $seed['slug'],
                ],
                [
                    'system' => $seed['system'],
                    'category' => $seed['category'],
                    'scenario' => $seed['scenario'],
                    'status' => 'pending',
                ]
            );
        }
    }
}
