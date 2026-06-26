<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Events;
use Carbon\Carbon;

class LargeScaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Retrieve role IDs
        $superAdminRoleId = DB::table('roles')->where('name', 'super_admin')->value('id');
        $adminRoleId = DB::table('roles')->where('name', 'admin')->value('id');
        $nominatorRoleId = DB::table('roles')->where('name', 'nominator')->value('id');
        $unitSpocRoleId = DB::table('roles')->where('name', 'unit_spoc')->value('id');
        $eventOpsRoleId = DB::table('roles')->where('name', 'event_ops')->value('id');

        $passwordHash = bcrypt('test@123');

        // 2. Seed Users
        $usersToSeed = [];

        // 1 Super Admin
        $usersToSeed[] = [
            'email' => 'hedagaurav1378-super_admin-super1@gmail.com',
            'name' => 'Gaurav',
            'last_name' => 'SuperAdmin',
            'contact_no' => '+1234567890',
            'status' => 1,
            'password' => $passwordHash,
            'role_id' => $superAdminRoleId,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // 3 Admins
        for ($i = 1; $i <= 3; $i++) {
            $usersToSeed[] = [
                'email' => "hedagaurav1378-admin-admin{$i}@gmail.com",
                'name' => 'Gaurav',
                'last_name' => "Admin{$i}",
                'contact_no' => '+1234567890',
                'status' => 1,
                'password' => $passwordHash,
                'role_id' => $adminRoleId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // 10 Nominators
        $nominatorUserIds = [];
        for ($i = 1; $i <= 10; $i++) {
            $nominatorData = [
                'email' => "hedagaurav1378-nominator-nom{$i}@gmail.com",
                'name' => 'Gaurav',
                'last_name' => "Nom{$i}",
                'contact_no' => '+1234567890',
                'status' => 1,
                'password' => $passwordHash,
                'role_id' => $nominatorRoleId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            
            $userId = DB::table('users')->insertGetId($nominatorData);
            $nominatorUserIds[] = $userId;
        }

        // 5 Unit SPOCs
        $unitSpocUserIds = [];
        for ($i = 1; $i <= 5; $i++) {
            $spocData = [
                'email' => "hedagaurav1378-unit_spoc-spoc{$i}@gmail.com",
                'name' => 'Gaurav',
                'last_name' => "Spoc{$i}",
                'contact_no' => '+1234567890',
                'status' => 1,
                'password' => $passwordHash,
                'role_id' => $unitSpocRoleId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            
            $userId = DB::table('users')->insertGetId($spocData);
            $unitSpocUserIds[] = $userId;
        }

        // 10 Event Ops
        for ($i = 1; $i <= 10; $i++) {
            $usersToSeed[] = [
                'email' => "hedagaurav1378-event_ops-ops{$i}@gmail.com",
                'name' => 'Gaurav',
                'last_name' => "Ops{$i}",
                'contact_no' => '+1234567890',
                'status' => 1,
                'password' => $passwordHash,
                'role_id' => $eventOpsRoleId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insert remaining users batch
        if (!empty($usersToSeed)) {
            DB::table('users')->insert($usersToSeed);
        }

        // Retrieve Super Admin user ID to set as creator
        $creatorId = DB::table('users')
            ->where('email', 'hedagaurav1378-super_admin-super1@gmail.com')
            ->value('id');

        // 3. Seed 20 Events
        $gdprOptions = ['Existing Business Relationship (Client)', 'Legitimate Business Interest(Prospect)'];
        $locations = ['New York, NY', 'London, UK', 'San Francisco, CA', 'Bangalore, India', 'Tokyo, Japan', 'Paris, France'];
        
        for ($evtIdx = 1; $evtIdx <= 20; $evtIdx++) {
            $isHospitality = ($evtIdx % 2 === 0);
            $eventCode = sprintf('EVT-EVAL-%03d', $evtIdx);
            
            $eventData = [
                'event_code' => $eventCode,
                'name' => "Evaluation Event {$evtIdx}",
                'description' => "Evaluation assessment event number {$evtIdx}.",
                'type' => $isHospitality ? 'hospitality' : 'non_hospitality',
                'start_date' => Carbon::now()->addDays($evtIdx + 5)->toDateString(),
                'end_date' => Carbon::now()->addDays($evtIdx + 8)->toDateString(),
                'nomination_deadline' => Carbon::now()->addDays($evtIdx + 2)->toDateTimeString(),
                'location' => $locations[$evtIdx % count($locations)],
                'status' => 'ongoing',
                'max_nominees_per_form' => 100,
                'gdpr_compliance' => $gdprOptions[$evtIdx % count($gdprOptions)],
                'created_by' => $creatorId,
                'last_updated' => $creatorId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            
            if ($isHospitality) {
                $eventData['declaration'] = "I declare that event {$evtIdx} complies with guidelines.";
                $eventData['invite_for'] = 'VIP Dinner Gala';
                $eventData['invite_spouser'] = 'Spouse Allowed';
                $eventData['govt_company'] = 'No State Owned Officials';
            }
            
            $eventId = DB::table('events')->insertGetId($eventData);

            // Seed event assignments
            // 4a. Assign all 10 nominators to this event
            foreach ($nominatorUserIds as $nomId) {
                DB::table('event_assignments')->insert([
                    'event_id' => $eventId,
                    'user_id' => $nomId,
                    'assigned_by' => $creatorId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // 4b. Assign 1-2 Unit SPOCs (round-robin) to this event
            $spoc1 = $unitSpocUserIds[($evtIdx - 1) % count($unitSpocUserIds)];
            $spoc2 = $unitSpocUserIds[$evtIdx % count($unitSpocUserIds)];
            
            DB::table('event_assignments')->insert([
                'event_id' => $eventId,
                'user_id' => $spoc1,
                'assigned_by' => $creatorId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            if ($spoc1 !== $spoc2) {
                DB::table('event_assignments')->insert([
                    'event_id' => $eventId,
                    'user_id' => $spoc2,
                    'assigned_by' => $creatorId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // 5. Seed 50 Nominees for this event
            for ($nomineeIdx = 1; $nomineeIdx <= 50; $nomineeIdx++) {
                // Nominated by different nominators (rotate through the 10 nominators)
                $nominatorId = $nominatorUserIds[($nomineeIdx - 1) % count($nominatorUserIds)];
                
                // Mix approval statuses: pending (30), approved (10), rejected (10)
                $status = 'pending';
                if ($nomineeIdx > 40) {
                    $status = 'rejected';
                } elseif ($nomineeIdx > 30) {
                    $status = 'approved';
                }

                $nomineeEmail = "nominee{$nomineeIdx}.evt{$evtIdx}@example.com";
                
                $nomineeId = DB::table('nominees_master')->insertGetId([
                    'event_id' => $eventId,
                    'nominator_id' => $nominatorId,
                    'gdpr_compliance' => $eventData['gdpr_compliance'],
                    'unit' => 'FS',
                    'sub_unit' => 'Banking',
                    'client_or_prospect' => ($nomineeIdx % 3 === 0) ? 'Prospect' : 'Client',
                    'first_name' => "Nominee{$nomineeIdx}",
                    'last_name' => "Evt{$evtIdx}",
                    'email' => $nomineeEmail,
                    'company' => 'Infosys Client Co.',
                    'title' => 'Manager IT',
                    'job_level' => (string)(($nomineeIdx % 4) + 1),
                    'primary_account_manager_name' => 'Suraj Kumar',
                    'primary_account_manager_email' => 's.kumar@infosys.com',
                    'account_manager_email_1' => null,
                    'account_manager_email_2' => null,
                    'business_or_it' => ($nomineeIdx % 2 === 0) ? 'IT' : 'Business',
                    'industry' => 'Financial Services',
                    'country' => 'India',
                    'approval_status' => $status,
                    'invite_status' => ($status === 'approved') ? 'Invite Sent' : 'Invite Pending',
                    'delivery_status' => 'Sent',
                    'is_dnc_contact' => false,
                    'spoc_comment' => ($status === 'rejected') ? 'Out of budget limit.' : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                if ($isHospitality) {
                    DB::table('hospitality_meta')->insert([
                        'nominee_id' => $nomineeId,
                        'invite_for' => 'VIP Dinner Gala',
                        'invite_spouse' => ($nomineeIdx % 2 === 0) ? 'Yes' : 'No',
                        'govt_or_state_owned' => 'No',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}
