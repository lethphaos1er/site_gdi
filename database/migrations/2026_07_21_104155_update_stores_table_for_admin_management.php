<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->string('address', 80)->nullable()->after('slug');
            $table->string('phone', 30)->nullable()->after('address');
            $table->string('email')->nullable()->after('phone');

            $table->string('identifier', 10)
                ->nullable()
                ->unique()
                ->after('type');
        });
    }

    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropUnique(['identifier']);

            $table->dropColumn([
                'address',
                'phone',
                'email',
                'identifier',
            ]);
        });
    }
};