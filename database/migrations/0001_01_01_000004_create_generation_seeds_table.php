<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('generation_seeds', function (Blueprint $table) {
            $table->id();
            $table->string('system');          // elasticsearch | kibana | logstash | zabbix | docker
            $table->string('category');        // errors | alerts | performance
            $table->string('slug')->unique();
            $table->text('scenario');          // human-readable problem
            $table->enum('status', ['pending','processing','done','failed'])->default('pending');
            $table->text('last_error')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('generation_seeds');
    }
};
