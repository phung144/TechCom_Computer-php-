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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('combination_code'); // Mã tự sinh để nhóm các biến thể (ví dụ: "CPU-1_RAM-2_GPU-3")
            $table->decimal('price', 12, 2); // Giá riêng của cấu hình này
            $table->integer('quantity')->default(0);
            $table->string('sku')->unique(); // Mã hàng hóa (tự sinh hoặc nhập tay)
            $table->timestamps();
        });

        // Bảng trung gian cho mối quan hệ nhiều-nhiều giữa product_variants và variant_options
        Schema::create('product_variant_options', function (Blueprint $table) {
            $table->foreignId('product_variant_id')->constrained()->onDelete('cascade');
            $table->foreignId('variant_option_id')->constrained()->onDelete('cascade');
            $table->primary(['product_variant_id', 'variant_option_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
