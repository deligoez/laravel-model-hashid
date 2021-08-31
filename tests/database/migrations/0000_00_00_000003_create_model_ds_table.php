<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('model_d_s', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->string('hash')->nullable();
            $table->timestamps();
        });
    }
};
