<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\PermissionRegistrar;

/**
 * Adds the "lead_delete" permission and grants it only to the superadmin role.
 * Other roles can be given it later from the Roles screen.
 */
return new class extends Migration
{
    private const GUARD = 'users';

    public function up(): void
    {
        $permissionId = DB::table('permissions')
            ->where('name', 'lead_delete')
            ->where('guard_name', self::GUARD)
            ->value('id');

        if (! $permissionId) {
            $permissionId = DB::table('permissions')->insertGetId([
                'name' => 'lead_delete',
                'guard_name' => self::GUARD,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $roleId = DB::table('roles')->where('name', 'superadmin')->where('guard_name', self::GUARD)->value('id');
        if ($roleId) {
            DB::table('role_has_permissions')->insertOrIgnore([
                'permission_id' => $permissionId,
                'role_id' => $roleId,
            ]);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function down(): void
    {
        $permissionId = DB::table('permissions')
            ->where('name', 'lead_delete')
            ->where('guard_name', self::GUARD)
            ->value('id');

        if ($permissionId) {
            DB::table('role_has_permissions')->where('permission_id', $permissionId)->delete();
            DB::table('model_has_permissions')->where('permission_id', $permissionId)->delete();
            DB::table('permissions')->where('id', $permissionId)->delete();
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
};
