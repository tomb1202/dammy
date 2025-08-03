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
        Schema::create('users', function (Blueprint $table) {
            $table->id()->comment('ID người dùng');
            $table->string('name')->comment('Tên người dùng');
            $table->string('email')->unique()->comment('Email người dùng');
            $table->timestamp('email_verified_at')->nullable()->comment('Thời gian xác minh email');
            $table->string('password')->comment('Mật khẩu đã mã hóa');
            $table->rememberToken()->comment('Token để đăng nhập tự động');
            $table->string('avatar')->nullable()->comment('Ảnh đại diện người dùng');
            $table->text('bio')->nullable()->comment('Giới thiệu bản thân');
            $table->enum('role', ['user', 'admin'])->default('user')->comment('Phân quyền: user hoặc admin');
            $table->boolean('is_active')->default(true)->comment('Trạng thái hoạt động của tài khoản');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};