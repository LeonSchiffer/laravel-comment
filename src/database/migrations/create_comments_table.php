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
            $table->uuid("parent_id")->nullable();
            $table->string("commentable_type");
            $table->string("commentable_id");
            $table->string("user_type");
            $table->string("user_id");
            $table->text("comment");
            $table->softDeletes();
            $table->nullableTimestamps();
        });
        Schema::create("reaction_types", function (Blueprint $table) {
            $table->id();
            $table->string("type");
            $table->nullableTimestamps();
        });
        Schema::create('reactions', function (Blueprint $table) {
            $table->id();
            $table->string("model_type");
            $table->string("model_id");
            $table->string("user_type");
            $table->string("user_id");
            $table->foreignId("reaction_type_id");
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
