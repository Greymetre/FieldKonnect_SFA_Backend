<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->string('alternate_number')->nullable()->after('company_url');
            $table->string('revenue_rs_cr')->nullable()->after('alternate_number');
            $table->text('others_1')->nullable()->after('others');
            $table->text('others_2')->nullable()->after('others_1');
            $table->text('others_3')->nullable()->after('others_2');
            $table->text('others_4')->nullable()->after('others_3');
            $table->text('others_5')->nullable()->after('others_4');
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn([
                'alternate_number',
                'revenue_rs_cr',
                'others_1',
                'others_2',
                'others_3',
                'others_4',
                'others_5',
            ]);
        });
    }
};
