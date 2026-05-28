<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('group_user', function (Blueprint $table) {
            $table->string('role')->default('viewer')->after('user_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('group_user', function (Blueprint $table) {
            $table->dropTimestamps();
            $table->dropColumn('role');
        });
    }
};
