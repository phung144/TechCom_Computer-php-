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
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable(); // tiêu đề banner
            $table->string('image');             // đường dẫn ảnh banner
            $table->string('link')->nullable();  // liên kết khi click vào banner
            $table->boolean('is_active')->default(true); // trạng thái hiển thị
            $table->text(column: 'position');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
