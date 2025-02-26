<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('fake_cars_vendor', function (Blueprint $table): void {
            $table->id();
            $table->string('vendor');
            $table->foreignId('fake_car_id')->constrained('fake_cars');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fake_cars_vendor');
    }
};
