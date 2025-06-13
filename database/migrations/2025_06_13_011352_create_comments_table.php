<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->ulid('id');
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->text('content');
            $table->text('reply')->nullable();
            $table->boolean('is_approved')->default(false);
            $table->foreignUlid('commentable_id')->nullable();
            $table->string('commentable_type')->nullable();
            $table->softDeletes('deleted_at', precision: 0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
