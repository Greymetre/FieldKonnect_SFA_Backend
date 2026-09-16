<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->unsignedBigInteger('import_batch_order')->nullable()->after('others_5');
            $table->unsignedInteger('import_row_order')->nullable()->after('import_batch_order');
            $table->index(['import_batch_order', 'import_row_order'], 'leads_import_order_index');
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropIndex('leads_import_order_index');
            $table->dropColumn(['import_batch_order', 'import_row_order']);
        });
    }
};
