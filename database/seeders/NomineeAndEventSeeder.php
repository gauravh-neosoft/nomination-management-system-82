<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Carbon\Carbon;

class NomineeAndEventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Get or create a default nominator user
        $roleId = DB::table('roles')->where('name', 'nominator')->value('id');
        $nominator = User::firstOrCreate(
            ['email' => 'gaurav@nominator.com'],
            [
                'name' => 'Gaurav',
                'last_name' => 'Heda',
                'contact_no' => '+1234567890',
                'status' => 1,
                'password' => bcrypt('test@123'),
                'role_id' => $roleId
            ]
        );

        // 2. Seed active/ongoing events
        $activeEvents = [
            [
                'event_code' => 'EVT-2026-001',
                'name' => 'Q3 Performance Awards',
                'description' => 'Honoring outstanding performers of the third quarter.',
                'type' => 'non_hospitality',
                'start_date' => Carbon::now()->addDays(5)->toDateString(),
                'end_date' => Carbon::now()->addDays(7)->toDateString(),
                'nomination_deadline' => Carbon::now()->addDays(3)->toDateTimeString(),
                'location' => 'San Francisco, CA',
                'status' => 'ongoing',
                'max_nominees_per_form' => 10,
                'gdpr_compliance' => 'Existing Business Relationship (Client)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'event_code' => 'EVT-2026-002',
                'name' => 'Global Talent Development Program',
                'description' => 'Elite leadership development tracks for top engineering minds.',
                'type' => 'non_hospitality',
                'start_date' => Carbon::now()->addDays(15)->toDateString(),
                'end_date' => Carbon::now()->addDays(20)->toDateString(),
                'nomination_deadline' => Carbon::now()->addDays(12)->toDateTimeString(),
                'location' => 'London, UK',
                'status' => 'ongoing',
                'max_nominees_per_form' => 5,
                'gdpr_compliance' => 'Legitimate Business Interest(Prospect)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'event_code' => 'EVT-2026-003',
                'name' => 'Annual Sales Excellence',
                'description' => 'Recognizing high-achieving sales professionals globally.',
                'type' => 'hospitality',
                'start_date' => Carbon::now()->addDays(30)->toDateString(),
                'end_date' => Carbon::now()->addDays(32)->toDateString(),
                'nomination_deadline' => Carbon::now()->addDays(25)->toDateTimeString(),
                'location' => 'New York, NY',
                'status' => 'ongoing',
                'max_nominees_per_form' => 15,
                'gdpr_compliance' => 'Existing Business Relationship (Client)',
                'declaration' => 'I declare that the attendees are clients under active contract.',
                'invite_for' => 'Dinner and Awards Ceremony',
                'invite_spouser' => 'Spouse Allowed',
                'govt_company' => 'No Govt Officials',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'event_code' => 'EVT-2026-004',
                'name' => 'Innovation Prize 2026',
                'description' => 'Celebrating revolutionary ideas and prototypes.',
                'type' => 'non_hospitality',
                'start_date' => Carbon::now()->addDays(45)->toDateString(),
                'end_date' => Carbon::now()->addDays(47)->toDateString(),
                'nomination_deadline' => Carbon::now()->addDays(1)->toDateTimeString(), // Closing soon!
                'location' => 'Bangalore, India',
                'status' => 'ongoing',
                'max_nominees_per_form' => 8,
                'gdpr_compliance' => 'Existing Business Relationship (Client)',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        foreach ($activeEvents as $evt) {
            DB::table('events')->updateOrInsert(
                ['event_code' => $evt['event_code']],
                $evt
            );
        }

        // 3. Seed completed events
        $completedEvents = [
            [
                'event_code' => 'EVT-2025-099',
                'name' => 'Q2 Leadership Summit',
                'description' => 'Summit for regional executives and business units.',
                'type' => 'non_hospitality',
                'start_date' => Carbon::now()->subDays(30)->toDateString(),
                'end_date' => Carbon::now()->subDays(28)->toDateString(),
                'nomination_deadline' => Carbon::now()->subDays(35)->toDateTimeString(),
                'location' => 'Tokyo, Japan',
                'status' => 'completed',
                'max_nominees_per_form' => 10,
                'gdpr_compliance' => 'Existing Business Relationship (Client)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'event_code' => 'EVT-2025-098',
                'name' => 'Tech Catalyst 2025',
                'description' => 'Annual technical symposium and vendor exhibition.',
                'type' => 'hospitality',
                'start_date' => Carbon::now()->subDays(60)->toDateString(),
                'end_date' => Carbon::now()->subDays(58)->toDateString(),
                'nomination_deadline' => Carbon::now()->subDays(65)->toDateTimeString(),
                'location' => 'Munich, Germany',
                'status' => 'completed',
                'max_nominees_per_form' => 5,
                'gdpr_compliance' => 'Legitimate Business Interest(Prospect)',
                'declaration' => 'Standard hospitality terms apply.',
                'invite_for' => 'VIP Lounge Access',
                'invite_spouser' => 'No Spouse',
                'govt_company' => 'Govt Representatives Included',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        foreach ($completedEvents as $evt) {
            DB::table('events')->updateOrInsert(
                ['event_code' => $evt['event_code']],
                $evt
            );
        }

        // 4. Seed event assignments for our nominator
        $eventIds = DB::table('events')->pluck('id');
        foreach ($eventIds as $eventId) {
            DB::table('event_assignments')->updateOrInsert(
                ['event_id' => $eventId, 'user_id' => $nominator->id],
                [
                    'event_id' => $eventId,
                    'user_id' => $nominator->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // 5. Seed nominee listings (nominees_master)
        $q3EventId = DB::table('events')->where('event_code', 'EVT-2026-001')->value('id');
        $talentEventId = DB::table('events')->where('event_code', 'EVT-2026-002')->value('id');
        $q2EventId = DB::table('events')->where('event_code', 'EVT-2025-099')->value('id');

        $nominees = [
            [
                'event_id' => $q3EventId,
                'nominator_id' => $nominator->id,
                'gdpr_compliance' => 'GDPR Compliant',
                'unit' => 'FS',
                'sub_unit' => 'FSIB',
                'client_or_prospect' => 'Client',
                'first_name' => 'Prathna',
                'last_name' => 'Saxena',
                'email' => 'prathna0299@infosys.com',
                'company' => 'Infosys',
                'title' => 'Software Engineer',
                'job_level' => '2',
                'primary_account_manager_name' => 'Suraj Kumar',
                'primary_account_manager_email' => 's.kumar@infosys.com',
                'account_manager_email_1' => 'v.sawant@infosys.com',
                'account_manager_email_2' => 'n.kedia@infosys.com',
                'business_or_it' => 'IT',
                'industry' => 'Financial Services',
                'country' => 'India',
                'approval_status' => 'approved',
                'invite_status' => 'Invite Sent',
                'delivery_status' => 'Sent',
                'is_dnc_contact' => false,
                'spoc_comment' => 'Approved by regional head.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'event_id' => $q3EventId,
                'nominator_id' => $nominator->id,
                'gdpr_compliance' => 'GDPR Compliant',
                'unit' => 'SURE',
                'sub_unit' => 'SURE-R',
                'client_or_prospect' => 'Client',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john.doe@cisco.com',
                'company' => 'Cisco',
                'title' => 'Director IT',
                'job_level' => '4',
                'primary_account_manager_name' => 'Amanda Smith',
                'primary_account_manager_email' => 'a.smith@infosys.com',
                'account_manager_email_1' => 'manager1@infosys.com',
                'account_manager_email_2' => null,
                'business_or_it' => 'IT',
                'industry' => 'Technology',
                'country' => 'USA',
                'approval_status' => 'pending',
                'invite_status' => 'Invite Pending',
                'delivery_status' => 'Sent',
                'is_dnc_contact' => false,
                'spoc_comment' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'event_id' => $talentEventId,
                'nominator_id' => $nominator->id,
                'gdpr_compliance' => 'GDPR Compliant',
                'unit' => 'FS',
                'sub_unit' => 'FSIB',
                'client_or_prospect' => 'Prospect',
                'first_name' => 'Jane',
                'last_name' => 'Miller',
                'email' => 'jane.miller@barclays.com',
                'company' => 'Barclays',
                'title' => 'VP Innovation',
                'job_level' => '4',
                'primary_account_manager_name' => 'Suraj Kumar',
                'primary_account_manager_email' => 's.kumar@infosys.com',
                'account_manager_email_1' => null,
                'account_manager_email_2' => null,
                'business_or_it' => 'Business',
                'industry' => 'Banking',
                'country' => 'UK',
                'approval_status' => 'approved',
                'invite_status' => 'Invite Sent',
                'delivery_status' => 'Delivered',
                'is_dnc_contact' => false,
                'spoc_comment' => 'High priority prospect.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'event_id' => $q2EventId,
                'nominator_id' => $nominator->id,
                'gdpr_compliance' => 'GDPR Compliant',
                'unit' => 'RETAIL',
                'sub_unit' => 'RET-EU',
                'client_or_prospect' => 'Client',
                'first_name' => 'Alice',
                'last_name' => 'Brown',
                'email' => 'alice.brown@tesco.com',
                'company' => 'Tesco',
                'title' => 'Lead Architect',
                'job_level' => '3',
                'primary_account_manager_name' => 'Robert Johnson',
                'primary_account_manager_email' => 'r.johnson@infosys.com',
                'account_manager_email_1' => null,
                'account_manager_email_2' => null,
                'business_or_it' => 'IT',
                'industry' => 'Retail',
                'country' => 'UK',
                'approval_status' => 'approved',
                'invite_status' => 'Completed Event',
                'delivery_status' => 'Completed Event',
                'is_dnc_contact' => false,
                'spoc_comment' => 'Attended successfully.',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        foreach ($nominees as $nom) {
            DB::table('nominees_master')->updateOrInsert(
                ['event_id' => $nom['event_id'], 'email' => $nom['email']],
                $nom
            );
        }
    }
}
