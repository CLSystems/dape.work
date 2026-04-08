<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('knowledge_entries', function (Blueprint $table) {
            $table->id();

            $table->string('slug')->unique();
            $table->string('system', 50);        // Elasticsearch | Kibana | Logstash | Zabbix | Docker
            $table->string('category', 50);      // Errors | Alerts | Performance
            $table->string('title');

            $table->json('structured_payload');

            $table->enum('status', [
                'draft',
                'reviewed',
                'published'
            ])->default('draft');

            $table->string('version', 20)->default('1.0');
            $table->timestamp('last_verified_at')->nullable();

            $table->timestamps();

            $table->index(['system', 'category']);
            $table->index(['status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('knowledge_entries');
    }
};
