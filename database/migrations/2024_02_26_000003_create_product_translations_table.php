<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('product_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('locale')->index();
            $table->string('title');
            $table->text('description')->nullable();

            $table->unique(['product_id', 'locale']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_translations');
    }
};
