<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('fake_cars', function (Blueprint $table): void {
            $table->id();
            $table->string('model');
            $table->string('color');
            $table->foreignId('fake_user_id')->constrained('fake_users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fake_cars');
    }
};
