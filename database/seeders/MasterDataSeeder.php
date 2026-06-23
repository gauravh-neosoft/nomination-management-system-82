<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Seed Roles table
        $roles = [
            [
                'name' => 'super_admin',
                'display_name' => 'Super Admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'admin',
                'display_name' => 'Admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'event_ops',
                'display_name' => 'Event OPS',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'unit_spoc',
                'display_name' => 'Unit SPOC',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'nominator',
                'display_name' => 'Nominator',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->updateOrInsert(
                ['name' => $role['name']],
                $role
            );
        }

        // 2. Seed Invite Statuses table
        $statuses = [
            ['status_name' => 'Sent'],
            ['status_name' => 'Delivered'],
            ['status_name' => 'Registered'],
            ['status_name' => 'Do Not Contact (DNC)'],
            ['status_name' => 'Completed Event'],
        ];

        foreach ($statuses as $status) {
            DB::table('invite_statuses')->updateOrInsert(
                ['status_name' => $status['status_name']],
                [
                    'status_name' => $status['status_name'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // 3. Seed Units table
        $units = [
            ['name' => 'FS'],
            ['name' => 'SURE'],
            ['name' => 'Digital'],
            ['name' => 'Operations'],
        ];

        foreach ($units as $unit) {
            DB::table('units')->updateOrInsert(
                ['name' => $unit['name']],
                [
                    'name' => $unit['name'],
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // Retrieve mapped IDs for subunits
        $fsId = DB::table('units')->where('name', 'FS')->value('id');
        $sureId = DB::table('units')->where('name', 'SURE')->value('id');
        $digitalId = DB::table('units')->where('name', 'Digital')->value('id');

        // 4. Seed Sub Units table
        $subUnits = [
            // FS
            ['unit_id' => $fsId, 'name' => 'Banking'],
            ['unit_id' => $fsId, 'name' => 'Insurance'],
            ['unit_id' => $fsId, 'name' => 'Capital Markets'],
            // SURE
            ['unit_id' => $sureId, 'name' => 'Retail'],
            ['unit_id' => $sureId, 'name' => 'Manufacturing'],
            ['unit_id' => $sureId, 'name' => 'Logistics'],
            // Digital
            ['unit_id' => $digitalId, 'name' => 'Cloud'],
            ['unit_id' => $digitalId, 'name' => 'Data Analytics'],
            ['unit_id' => $digitalId, 'name' => 'AI/ML'],
        ];

        foreach ($subUnits as $subUnit) {
            if ($subUnit['unit_id']) {
                DB::table('sub_units')->updateOrInsert(
                    ['unit_id' => $subUnit['unit_id'], 'name' => $subUnit['name']],
                    [
                        'unit_id' => $subUnit['unit_id'],
                        'name' => $subUnit['name'],
                        'is_active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }

        // 5. Seed GDPR Compliances table
        $gdprOptions = [
            ['name' => 'Existing Business Relationship (Client)'],
            ['name' => 'Legitimate Business Interest(Prospect)'],
        ];

        foreach ($gdprOptions as $option) {
            DB::table('gdpr_compliances')->updateOrInsert(
                ['name' => $option['name']],
                [
                    'name' => $option['name'],
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
