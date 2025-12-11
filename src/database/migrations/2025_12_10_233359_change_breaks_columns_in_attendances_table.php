<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeBreaksColumnsInAttendancesTable extends Migration
{
    public function up()
    {
        Schema::table('attendances', function (Blueprint $table) {
            // breaks を削除
            if (Schema::hasColumn('attendances', 'breaks')) {
                $table->dropColumn('breaks');
            }

            // 休憩開始と終了を追加
            $table->dateTime('break_start')->nullable()->after('clock_out');
            $table->dateTime('break_end')->nullable()->after('break_start');
        });
    }

    public function down()
    {
        Schema::table('attendances', function (Blueprint $table) {
            // 復元: break_start / break_end 削除
            if (Schema::hasColumn('attendances', 'break_start')) {
                $table->dropColumn('break_start');
            }
            if (Schema::hasColumn('attendances', 'break_end')) {
                $table->dropColumn('break_end');
            }

            // breaks カラムを復元
            $table->json('breaks')->nullable()->after('clock_out');
        });
    }
}
