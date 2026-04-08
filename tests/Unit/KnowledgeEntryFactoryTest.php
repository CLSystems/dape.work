<?php

namespace Tests\Unit;

use App\Models\KnowledgeEntry;
use Database\Factories\KnowledgeEntryFactory;
use Tests\TestCase;

class KnowledgeEntryFactoryTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_knowledge_entry_factory_definition(): void
    {
        $factory = KnowledgeEntryFactory::new();
        $definition = $factory->definition();

        $this->assertArrayHasKey('slug', $definition);
        $this->assertArrayHasKey('system', $definition);
        $this->assertArrayHasKey('category', $definition);
        $this->assertArrayHasKey('title', $definition);
        $this->assertArrayHasKey('structured_payload', $definition);
        $this->assertArrayHasKey('status', $definition);
        $this->assertArrayHasKey('version', $definition);
        $this->assertArrayHasKey('last_verified_at', $definition);

        $this->assertIsString($definition['slug']);
        $this->assertIsString($definition['system']);
        $this->assertIsString($definition['category']);
        $this->assertIsString($definition['title']);
        $this->assertIsArray($definition['structured_payload']);
        $this->assertContains($definition['status'], [
            KnowledgeEntry::STATUS_DRAFT,
            KnowledgeEntry::STATUS_REVIEWED,
            KnowledgeEntry::STATUS_PUBLISHED,
        ]);
    }
}
