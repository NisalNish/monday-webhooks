<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('monday_items', function (Blueprint $table) {
        $table->id();
        $table->bigInteger('item_id');
        $table->bigInteger('board_id');
        $table->string('column_id');
        $table->string('status_value');
        $table->timestamps(); // created_at and updated_at
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monday_items');
    }
};
