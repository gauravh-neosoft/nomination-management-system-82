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
    }
}
