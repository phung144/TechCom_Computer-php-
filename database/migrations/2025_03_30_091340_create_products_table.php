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
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // ID sản phẩm
            $table->string('name'); // Tên sản phẩm
            $table->unsignedBigInteger('category_id'); // Liên kết đến bảng categories
            $table->integer('quantity')->default(0); // Số lượng
            $table->integer('sales')->default(0); // Lượt bán
            $table->text('description')->nullable(); // Mô tả
            $table->string('image'); // Ảnh chính
            $table->decimal('price', 10, 2); // Giá sản phẩm
            $table->timestamp('discount_start')->nullable(); // Thời gian bắt đầu mã giảm giá
            $table->timestamp('discount_end')->nullable(); // Thời gian kết thúc mã giảm giá
            $table->enum('discount_type', ['percentage', 'fixed'])->nullable(); // Loại giảm giá: % hoặc VND
            $table->decimal('discount_value', 10, 2)->nullable(); // Giá trị giảm giá
            $table->timestamps(); // created_at và updated_at

            // Khóa ngoại liên kết đến bảng categories
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
