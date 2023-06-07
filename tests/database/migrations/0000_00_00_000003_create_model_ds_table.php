<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('model_d_s', function (Blueprint $table): void {
            $table->id();
            $table->string('slug');
            $table->string('hash')->nullable();
            $table->timestamps();
        });
    }
};
