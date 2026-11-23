<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\PermissionRegistrar;

/**
 * "lead_dashboard" permission for Lead Management > Lead Dashboard,
 * granted only to superadmin for now.
 */
return new class extends Migration
{
    private const GUARD = 'users';

    public function up(): void
    {
        $permissionId = DB::table('permissions')->where('name', 'lead_dashboard')->where('guard_name', self::GUARD)->value('id')
            ?: DB::table('permissions')->insertGetId([
                'name' => 'lead_dashboard',
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
        $permissionId = DB::table('permissions')->where('name', 'lead_dashboard')->where('guard_name', self::GUARD)->value('id');
        if ($permissionId) {
            DB::table('role_has_permissions')->where('permission_id', $permissionId)->delete();
            DB::table('model_has_permissions')->where('permission_id', $permissionId)->delete();
            DB::table('permissions')->where('id', $permissionId)->delete();
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
};
