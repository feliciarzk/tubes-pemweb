<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('driver_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('service');
            $table->enum('delivery_type', ['antar_jemput', 'antar_saja']);
            $table->enum('address_type', ['profil', 'lain']);
            $table->text('address');
            $table->date('pickup_date')->nullable();
            $table->string('pickup_time')->nullable();
            $table->text('notes')->nullable();
            $table->decimal('weight', 6, 2)->nullable();   // diisi driver / admin
            $table->unsignedInteger('price')->nullable();  // diisi admin
            $table->string('status')->default('menunggu_pickup');
            $table->string('payment_method')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
