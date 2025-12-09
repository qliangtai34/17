<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendanceCorrectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance_corrections', function (Blueprint $table) {
            $table->id();

            // 申請者（一般ユーザー）
            $table->unsignedBigInteger('user_id');

            // 勤怠データ（元データ）
            $table->unsignedBigInteger('attendance_id');

            // 元の勤怠データ
            $table->text('original_clock_in')->nullable();
            $table->text('original_clock_out')->nullable();
            $table->text('original_breaks')->nullable();
            $table->text('original_note')->nullable();

            // 修正後データ
            $table->text('new_clock_in')->nullable();
            $table->text('new_clock_out')->nullable();
            $table->text('new_breaks')->nullable();
            $table->text('new_note')->nullable();

            // 状態
            $table->string('status')->default('pending');
            // pending / approved / rejected

            $table->timestamps();

            // 外部キー
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');

            $table->foreign('attendance_id')
                ->references('id')->on('attendances')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendance_corrections');
    }
}