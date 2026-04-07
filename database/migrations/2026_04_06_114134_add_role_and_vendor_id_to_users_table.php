<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'vendor', 'customer'])->default('customer')->after('email');
            $table->foreignId('vendor_id')->nullable()->constrained('vendor', 'idvendor')->onDelete('set null')->after('role');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
            $table->dropForeign(['vendor_id']);
            $table->dropColumn('vendor_id');
        });
    }
};