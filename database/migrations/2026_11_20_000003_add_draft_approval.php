<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\PermissionRegistrar;

/**
 * Draft approval: email / LinkedIn drafts get approved_by + approved_at, and a
 * "draft_approval" permission (granted only to superadmin for now).
 */
return new class extends Migration
{
    private const GUARD = 'users';

    public function up(): void
    {
        Schema::table('lead_notes', function (Blueprint $table) {
            if (! Schema::hasColumn('lead_notes', 'approved_by')) {
                $table->unsignedBigInteger('approved_by')->nullable()->after('created_by');
            }
            if (! Schema::hasColumn('lead_notes', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->index()->after('approved_by');
            }
        });

        $permissionId = DB::table('permissions')->where('name', 'draft_approval')->where('guard_name', self::GUARD)->value('id')
            ?: DB::table('permissions')->insertGetId([
                'name' => 'draft_approval',
                'guard_name' => self::GUARD,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

        $roleId = DB::table('roles')->where('name', 'superadmin')->where('guard_name', self::GUARD)->value('id');
        if ($roleId) {
            DB::table('role_has_permissions')->insertOrIgnore(['permission_id' => $permissionId, 'role_id' => $roleId]);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function down(): void
    {
        Schema::table('lead_notes', function (Blueprint $table) {
            foreach (['approved_at', 'approved_by'] as $column) {
                if (Schema::hasColumn('lead_notes', $column)) {
                    if ($column === 'approved_at') {
                        $table->dropIndex(['approved_at']);
                    }
                    $table->dropColumn($column);
                }
            }
        });

        $permissionId = DB::table('permissions')->where('name', 'draft_approval')->where('guard_name', self::GUARD)->value('id');
        if ($permissionId) {
            DB::table('role_has_permissions')->where('permission_id', $permissionId)->delete();
            DB::table('model_has_permissions')->where('permission_id', $permissionId)->delete();
            DB::table('permissions')->where('id', $permissionId)->delete();
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
};
