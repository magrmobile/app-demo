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
        Schema::table('cat013', function (Blueprint $table) {
            $table->renameColumn('departamento', 'departamento_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cat013', function (Blueprint $table) {
            $table->renameColumn('departamento_id', 'departamento');
        });
    }
};
