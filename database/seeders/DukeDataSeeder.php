<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Spatie\Permission\PermissionRegistrar;

class DukeDataSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('seeders/data/duke_user_1.sql');
        $sql = file_get_contents($path);

        if ($sql === false) {
            throw new RuntimeException("Unable to read seed data: {$path}");
        }

        DB::unprepared('SET FOREIGN_KEY_CHECKS=0');

        try {
            foreach ($this->statements($sql) as $statement) {
                DB::unprepared($statement);
            }

            // Users receive permissions only through their assigned roles.
            DB::table('model_has_permissions')->delete();

            $superAdminRoleId = DB::table('roles')
                ->where('name', 'superadmin')
                ->where('guard_name', 'users')
                ->value('id');

            if ($superAdminRoleId !== null) {
                DB::table('model_has_roles')->updateOrInsert([
                    'role_id' => $superAdminRoleId,
                    'model_type' => 'App\\Models\\User',
                    'model_id' => 1,
                ]);
            }
        } finally {
            DB::unprepared('SET FOREIGN_KEY_CHECKS=1');
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    /** @return array<int, string> */
    private function statements(string $sql): array
    {
        $statements = [];
        $start = 0;
        $quote = null;
        $escaped = false;
        $length = strlen($sql);

        for ($i = 0; $i < $length; $i++) {
            $char = $sql[$i];

            if ($quote !== null) {
                if ($escaped) {
                    $escaped = false;
                } elseif ($char === '\\') {
                    $escaped = true;
                } elseif ($char === $quote) {
                    $quote = null;
                }
                continue;
            }

            if ($char === "'") {
                $quote = $char;
            } elseif ($char === ';') {
                $statement = trim(substr($sql, $start, $i - $start));
                if ($statement !== '' && !str_starts_with($statement, '--')) {
                    $statements[] = $statement;
                } elseif (str_contains($statement, "\nINSERT INTO")) {
                    $statements[] = trim(substr($statement, strpos($statement, 'INSERT INTO')));
                }
                $start = $i + 1;
            }
        }

        return $statements;
    }
}
