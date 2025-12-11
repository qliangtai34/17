<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeBreaksColumnsInAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('attendances', function (Blueprint $table) {

            // ① 旧 breaks カラムを削除（DBAL不要）
            if (Schema::hasColumn('attendances', 'breaks')) {
                $table->dropColumn('breaks');
            }

            // ② 新しく休憩開始・終了を追加
            $table->dateTime('break_start')->nullable()->after('end_time');
            $table->dateTime('break_end')->nullable()->after('break_start');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('attendances', function (Blueprint $table) {

            // 戻す処理：break_start / break_end を削除
            if (Schema::hasColumn('attendances', 'break_start')) {
                $table->dropColumn('break_start');
            }
            if (Schema::hasColumn('attendances', 'break_end')) {
                $table->dropColumn('break_end');
            }

            // 元の breaks カラム復元（string型例）
            $table->string('breaks')->nullable();
        });
    }
}
