<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('group_name')->default('general');
            $table->text('description')->nullable();
            $table->string('type')->default('text'); // text, textarea, image, etc.
            $table->timestamps();
            
            $table->index(['group_name']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('settings');
    }
}