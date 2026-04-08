<?php

namespace Database\Factories;

use App\Models\KnowledgeEntry;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\KnowledgeEntry>
 */
class KnowledgeEntryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = KnowledgeEntry::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence();

        return [
            'slug' => Str::slug($title) . '-' . fake()->unique()->numberBetween(1, 10000),
            'system' => fake()->randomElement(['elasticsearch', 'kibana', 'logstash', 'zabbix', 'docker']),
            'category' => fake()->randomElement(['error', 'alert', 'performance']),
            'title' => $title,
            'structured_payload' => [
                'description' => fake()->paragraph(),
                'steps' => fake()->sentences(3),
                'tags' => fake()->words(3),
            ],
            'status' => fake()->randomElement([
                KnowledgeEntry::STATUS_DRAFT,
                KnowledgeEntry::STATUS_REVIEWED,
                KnowledgeEntry::STATUS_PUBLISHED,
            ]),
            'version' => '1.0',
            'last_verified_at' => fake()->optional()->dateTime(),
        ];
    }

    /**
     * Indicate that the knowledge entry is published.
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => KnowledgeEntry::STATUS_PUBLISHED,
        ]);
    }
}
