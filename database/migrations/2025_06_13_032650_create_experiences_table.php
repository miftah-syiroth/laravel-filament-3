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
        Schema::create('experiences', function (Blueprint $table) {
            $table->ulid('id')->primary()->unique();
            $table->string('company');
            $table->string('address');
            $table->string('url')->nullable();
            $table->string('role');
            $table->string('job_type');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->text('excerpt');
            $table->text('content')->nullable();
            $table->softDeletes('deleted_at', precision: 0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('experiences');
    }
};
