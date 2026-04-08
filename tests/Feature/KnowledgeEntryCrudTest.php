<?php

namespace Tests\Feature;

use App\Models\KnowledgeEntry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KnowledgeEntryCrudTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_guest_cannot_access_knowledge_base(): void
    {
        $this->get(route('knowledge.index'))->assertRedirect(route('login'));
    }

    public function test_user_can_view_knowledge_index(): void
    {
        KnowledgeEntry::factory()->count(3)->create();

        $response = $this->actingAs($this->user)->get(route('knowledge.index'));

        $response->assertStatus(200);
        $response->assertViewHas('entries');
    }

    public function test_user_can_view_create_form(): void
    {
        $response = $this->actingAs($this->user)->get(route('knowledge.create'));

        $response->assertStatus(200);
    }

    public function test_user_can_store_knowledge_entry(): void
    {
        $data = [
            'slug' => 'test-slug',
            'system' => 'elasticsearch',
            'category' => 'error',
            'title' => 'Test Title',
            'status' => 'draft',
            'version' => '1.0',
            'description' => 'Test Description',
            'steps' => ['Step 1', 'Step 2'],
            'tags' => ['tag1', 'tag2'],
        ];

        $response = $this->actingAs($this->user)->post(route('knowledge.store'), $data);

        $response->assertRedirect(route('knowledge.index'));
        $this->assertDatabaseHas('knowledge_entries', [
            'slug' => 'test-slug',
            'title' => 'Test Title',
        ]);

        $entry = KnowledgeEntry::where('slug', 'test-slug')->first();
        $this->assertEquals('Test Description', $entry->structured_payload['description']);
        $this->assertEquals(['Step 1', 'Step 2'], $entry->structured_payload['steps']);
    }

    public function test_user_can_view_knowledge_entry(): void
    {
        $entry = KnowledgeEntry::factory()->create();

        $response = $this->actingAs($this->user)->get(route('knowledge.show', $entry));

        $response->assertStatus(200);
        $response->assertSee($entry->title);
    }

    public function test_user_can_view_edit_form(): void
    {
        $entry = KnowledgeEntry::factory()->create();

        $response = $this->actingAs($this->user)->get(route('knowledge.edit', $entry));

        $response->assertStatus(200);
        $response->assertSee($entry->title);
    }

    public function test_user_can_update_knowledge_entry(): void
    {
        $entry = KnowledgeEntry::factory()->create();

        $data = [
            'slug' => 'updated-slug',
            'system' => 'kibana',
            'category' => 'alert',
            'title' => 'Updated Title',
            'status' => 'published',
            'version' => '2.0',
            'description' => 'Updated Description',
            'steps' => ['Updated Step 1'],
            'tags' => ['updated-tag'],
        ];

        $response = $this->actingAs($this->user)->put(route('knowledge.update', $entry), $data);

        $response->assertRedirect(route('knowledge.index'));
        $this->assertDatabaseHas('knowledge_entries', [
            'id' => $entry->id,
            'slug' => 'updated-slug',
            'title' => 'Updated Title',
        ]);
    }

    public function test_user_can_delete_knowledge_entry(): void
    {
        $entry = KnowledgeEntry::factory()->create();

        $response = $this->actingAs($this->user)->delete(route('knowledge.destroy', $entry));

        $response->assertRedirect(route('knowledge.index'));
        $this->assertDatabaseMissing('knowledge_entries', ['id' => $entry->id]);
    }
}
