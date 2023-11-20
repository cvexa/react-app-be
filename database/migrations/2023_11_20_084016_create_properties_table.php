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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();//Best Apartment
            $table->string('pic')->nullable();
            $table->bigInteger('price')->nullable();
            $table->string('location')->nullable();
            $table->integer('floor_number')->nullable();//1, 2
            $table->integer('number_of_rooms')->nullable();
            $table->boolean('with_parking')->default(false);
            $table->string('type')->nullable();//apartment, vila ....
            $table->string('contract')->nullable();//Contract Ready, Contract not ready
            $table->string('payment_process')->nullable();//ready, waiting documents, bank
            $table->string('safety')->nullable();//under control
            $table->bigInteger('quadrature')->nullable();//250, 500
            $table->longText('description')->nullable();//lorem ipsum;
            $table->boolean('is_top')->default(false);//to be shown at banner
            $table->boolean('is_featured')->default(false);//to be shown on first page
            $table->boolean('is_best_deal')->default(false);//to be shown in best deal carousel
            $table->foreignId('created_by')->nullable()->constrained('users');//user_id
            $table->boolean('published')->default(false);//to be visible or not
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
