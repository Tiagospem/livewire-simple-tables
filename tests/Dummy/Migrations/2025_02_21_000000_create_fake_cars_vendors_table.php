<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('cars_vendor', function (Blueprint $table): void {
            $table->id();
            $table->string('vendor');
            $table->foreignId('car_id')->constrained('cars');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cars_vendor');
    }
};
