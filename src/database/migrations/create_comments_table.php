<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->morphs("commentable");
            $table->morphs("user");
            $table->text("comment");
            $table->softDeletes();
            $table->nullableTimestamps();
        });
        Schema::create("reactions", function (Blueprint $table) {
            $table->id();
            $table->string("type");
            $table->nullableTimestamps();
        });
        Schema::create('comment_reaction', function (Blueprint $table) {
            $table->foreignUuid("comment_id");
            $table->morphs("user");
            $table->foreignId("reaction_id");
            $table->nullableTimestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("comments");
        Schema::dropIfExists("reactions");
        Schema::dropIfExists("comment_reaction");
    }
};
