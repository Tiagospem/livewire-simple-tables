<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('fake_users', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->boolean('is_active')->default(false);
            $table->foreignId('country_id')->nullable()->constrained('fake_countries');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fake_users');
    }
};
