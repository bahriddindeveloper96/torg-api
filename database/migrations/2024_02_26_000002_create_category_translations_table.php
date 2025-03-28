<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('category_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('locale')->index();
            $table->string('name');
            $table->text('description')->nullable();

            $table->unique(['category_id', 'locale']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('category_translations');
    }
};
