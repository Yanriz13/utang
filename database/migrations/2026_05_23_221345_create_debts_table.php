<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::create('debts', function (Blueprint $table) {
        $table->id();

        $table->string('title');

        $table->bigInteger('total_amount');

        $table->integer('total_month');

        $table->bigInteger('monthly_payment');

        $table->date('start_date');

        $table->text('description')->nullable();

        $table->enum('status', ['belum_lunas', 'lunas'])
              ->default('belum_lunas');

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('debts');
    }
};
