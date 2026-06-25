<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Events;
use Carbon\Carbon;
use App\Imports\NomineesImport;
use Maatwebsite\Excel\Facades\Excel;

class NominatorController extends Controller
{
    public function dashboard()
    {
        $userId = Auth::id();

        // 1. Get counts (assigned to this nominator)
        $newEventsCount = Events::where('status', 'ongoing')
            ->whereIn('id', function($query) use ($userId) {
                $query->select('event_id')
                    ->from('event_assignments')
                    ->where('user_id', $userId);
            })
            ->count();

        $nominationsCount = DB::table('nominees_master')
            ->where('nominator_id', $userId)
            ->count();

        $eventHistoryCount = Events::where('status', 'completed')
            ->whereIn('id', function($query) use ($userId) {
                $query->select('event_id')
                    ->from('event_assignments')
                    ->where('user_id', $userId);
            })
            ->count();

        // 2. Get latest active events for the table (assigned to this nominator)
        $rawEvents = Events::where('status', 'ongoing')
            ->whereIn('id', function($query) use ($userId) {
                $query->select('event_id')
                    ->from('event_assignments')
                    ->where('user_id', $userId);
            })
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $events = $rawEvents->map(function ($event) use ($userId) {
            // Get nominees count submitted by this nominator
            $event->nominees_count = DB::table('nominees_master')
                ->where('event_id', $event->id)
                ->where('nominator_id', $userId)
                ->count();

            // Format dates matching the reference "Dec 1, 2024"
            $event->formatted_start_date = Carbon::parse($event->start_date)->format('M j, Y');
            $event->formatted_end_date = Carbon::parse($event->end_date)->format('M j, Y');
            return $event;
        });

        return view('nominator.dashboard.nominator-dashboard', compact(
            'newEventsCount',
            'nominationsCount',
            'eventHistoryCount',
            'events'
        ));
    }

    public function activeEvents()
    {
        $userId = Auth::id();

        // Fetch ongoing events assigned to this nominator
        $rawEvents = Events::where('status', 'ongoing')
            ->whereIn('id', function($query) use ($userId) {
                $query->select('event_id')
                    ->from('event_assignments')
                    ->where('user_id', $userId);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $events = $rawEvents->map(function ($event) use ($userId) {
            $event->nominees_count = DB::table('nominees_master')
                ->where('event_id', $event->id)
                ->where('nominator_id', $userId)
                ->count();

            // Fetch actual nominee list for the view list modal
            $event->nominees = DB::table('nominees_master')
                ->where('event_id', $event->id)
                ->where('nominator_id', $userId)
                ->get()
                ->map(function ($nominee) {
                    $nominee->full_name = trim($nominee->first_name . ' ' . $nominee->last_name);
                    return $nominee;
                });

            // Date formats
            $event->formatted_start_date = Carbon::parse($event->start_date)->format('M j, Y');
            $event->formatted_end_date = Carbon::parse($event->end_date)->format('M j, Y');
            $event->formatted_deadline = Carbon::parse($event->nomination_deadline)->format('M j, Y H:i');

            // Determine closing soon (e.g. event date is less than or equal to 15 days)
            $startDate = Carbon::parse($event->start_date);
            $diffInDays = Carbon::now()->diffInDays($startDate, false);
            $event->is_closing_soon = $diffInDays >= 0 && $diffInDays <= 15;

            return $event;
        });

        // Calculate badges
        $openCount = Events::where('status', 'ongoing')
            ->whereIn('id', function($query) use ($userId) {
                $query->select('event_id')
                    ->from('event_assignments')
                    ->where('user_id', $userId);
            })
            ->count();

        $closingSoonCount = 0;
        foreach ($events as $evt) {
            if ($evt->is_closing_soon) {
                $closingSoonCount++;
            }
        }

        return view('nominator.events.active', compact('events', 'openCount', 'closingSoonCount'));
    }

    public function completedEvents()
    {
        $userId = Auth::id();

        // Fetch completed events assigned to this nominator
        $rawEvents = Events::where('status', 'completed')
            ->whereIn('id', function($query) use ($userId) {
                $query->select('event_id')
                    ->from('event_assignments')
                    ->where('user_id', $userId);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $events = $rawEvents->map(function ($event) use ($userId) {
            $event->nominees_count = DB::table('nominees_master')
                ->where('event_id', $event->id)
                ->where('nominator_id', $userId)
                ->count();

            $event->nominees = DB::table('nominees_master')
                ->where('event_id', $event->id)
                ->where('nominator_id', $userId)
                ->get()
                ->map(function ($nominee) {
                    $nominee->full_name = trim($nominee->first_name . ' ' . $nominee->last_name);
                    return $nominee;
                });

            $event->formatted_start_date = Carbon::parse($event->start_date)->format('M j, Y');
            $event->formatted_end_date = Carbon::parse($event->end_date)->format('M j, Y');
            return $event;
        });

        return view('nominator.events.completed', compact('events'));
    }

    public function nominations()
    {
        $userId = Auth::id();

        // Fetch all nominees submitted by this nominator
        $nominees = DB::table('nominees_master')
            ->join('events', 'nominees_master.event_id', '=', 'events.id')
            ->where('nominees_master.nominator_id', $userId)
            ->select('nominees_master.*', 'events.name as event_name')
            ->orderBy('nominees_master.created_at', 'desc')
            ->get()
            ->map(function ($nominee) {
                $nominee->full_name = trim($nominee->first_name . ' ' . $nominee->last_name);
                return $nominee;
            });

        return view('nominator.nominations', compact('nominees'));
    }

    public function showNominationForm($id)
    {
        $event = Events::findOrFail($id);
        return view('nominator.events.nominate', compact('event'));
    }

    public function submitNomination(Request $request, $id)
    {
        $event = Events::findOrFail($id);
        $userId = Auth::id();

        // 1. Check if the nominator has already reached the nomination limit for this event
        $currentCount = DB::table('nominees_master')
            ->where('event_id', $event->id)
            ->where('nominator_id', $userId)
            ->count();

        // Get effective limit (custom vs default)
        $customLimit = DB::table('nominator_event_limits')
            ->where('event_id', $event->id)
            ->where('nominator_id', $userId)
            ->whereNull('deleted_at')
            ->first();
        
        $effectiveLimit = $customLimit ? (int)$customLimit->max_nominees : (int)$event->max_nominees_per_form;

        if ($currentCount >= $effectiveLimit) {
            return redirect()->back()
                ->withErrors(['limit' => 'You have reached the maximum nominee limit (' . $effectiveLimit . ') for this event.'])
                ->withInput();
        }

        // 2. Validate form inputs
        $rules = [
            'gdpr_compliance' => 'required|string|max:255',
            'unit' => 'required|string|max:100',
            'sub_unit' => 'required|string|max:100',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'company' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'job_level' => 'required|string|in:1,2,3,4',
            'primary_account_manager_name' => 'required|string|max:255',
            'primary_account_manager_email' => 'required|email|max:255',
            'account_manager_email_1' => 'nullable|email|max:255',
            'account_manager_email_2' => 'nullable|email|max:255',
            'business_or_it' => 'required|string|in:Business,IT',
            'country' => 'required|string|max:100',
        ];

        if ($event->type === 'hospitality') {
            // $rules['invite_for'] = 'required|string|max:255';
            // $rules['invite_spouse'] = 'required|string|in:Yes,No';
            // $rules['govt_or_state_owned'] = 'required|string|in:Yes,No';
        }

        $validated = $request->validate($rules);

        // 3. Map GDPR compliance logic and client_or_prospect
        $clientOrProspect = 'Client';
        if (str_contains(strtolower($validated['gdpr_compliance']), 'prospect')) {
            $clientOrProspect = 'Prospect';
        }

        // 4. Save record
        DB::transaction(function () use ($event, $userId, $validated, $clientOrProspect) {
            $nomineeId = DB::table('nominees_master')->insertGetId([
                'event_id' => $event->id,
                'nominator_id' => $userId,
                'gdpr_compliance' => $validated['gdpr_compliance'],
                'unit' => $validated['unit'],
                'sub_unit' => $validated['sub_unit'],
                'client_or_prospect' => $clientOrProspect,
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'company' => $validated['company'],
                'title' => $validated['title'],
                'job_level' => $validated['job_level'],
                'primary_account_manager_name' => $validated['primary_account_manager_name'],
                'primary_account_manager_email' => $validated['primary_account_manager_email'],
                'account_manager_email_1' => $validated['account_manager_email_1'],
                'account_manager_email_2' => $validated['account_manager_email_2'],
                'business_or_it' => $validated['business_or_it'],
                'industry' => 'Technology', // Default required column not present in UI
                'country' => $validated['country'],
                'approval_status' => 'pending',
                'invite_status' => 'Invite Pending',
                'delivery_status' => 'Sent',
                'is_dnc_contact' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if ($event->type === 'hospitality') {
                DB::table('hospitality_meta')->insert([
                    'nominee_id' => $nomineeId,
                    'invite_for' => $validated['invite_for'],
                    'invite_spouse' => $validated['invite_spouse'],
                    'govt_or_state_owned' => $validated['govt_or_state_owned'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });

        // 5. Send notification to admins and unit ops/spocs assigned to this event
        try {
            $nominator = Auth::user();
            $adminEmails = \App\Helpers\MailHelper::getAdminEmails();
            $unitSpocEmails = DB::table('event_assignments')
                ->join('users', 'event_assignments.user_id', '=', 'users.id')
                ->join('roles', 'users.role_id', '=', 'roles.id')
                ->where('event_assignments.event_id', $event->id)
                ->where('roles.name', 'unit_spoc')
                ->where('users.status', 1)
                ->pluck('users.email')
                ->toArray();
            $recipientEmails = array_unique(array_merge($adminEmails, $unitSpocEmails));

            if (!empty($recipientEmails)) {
                send_templated_email($recipientEmails, 'nomination_added', [
                    'subject' => 'New Nomination: ' . $validated['first_name'] . ' ' . $validated['last_name'] . ' - ' . $event->name,
                    'event_name' => $event->name,
                    'event_code' => $event->event_code,
                    'nominator_name' => trim(($nominator->name ?? '') . ' ' . ($nominator->last_name ?? '')),
                    'nominator_email' => $nominator->email ?? '',
                    'first_name' => $validated['first_name'],
                    'last_name' => $validated['last_name'],
                    'email' => $validated['email'],
                    'company' => $validated['company'],
                    'title' => $validated['title'],
                    'unit' => $validated['unit'],
                    'sub_unit' => $validated['sub_unit'],
                ]);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to dispatch single nomination email: ' . $e->getMessage());
        }

        return redirect()->route('nominator-active-events')->with('success', 'Nomination submitted successfully.');
    }

    public function downloadTemplate($id)
    {
        $event = Events::findOrFail($id);

        if ($event->type === 'hospitality') {
            $fileName = 'Hospitality Event - Nomination Template.xlsx';
        } else {
            $fileName = 'Non Hospitality Event - Nomination Template.xlsx';
        }

        $destDir = public_path('assets/templates');
        // if (!file_exists($destDir)) {
        //     mkdir($destDir, 0755, true);
        // }

        $destPath = $destDir . DIRECTORY_SEPARATOR . $fileName;

        // // Copy template to public assets if not already done
        // if (!file_exists($destPath)) {
        //     $srcPath = 'C:\\Gaurav\\work\\resources\\' . $fileName;
        //     if (file_exists($srcPath)) {
        //         copy($srcPath, $destPath);
        //     } else {
        //         abort(404, 'Template source file not found.');
        //     }
        // }

        return response()->download($destPath, $fileName);
    }

    public function bulkUpload(Request $request, $eventId)
    {
        $event = Events::findOrFail($eventId);
        $userId = Auth::id();

        if (!$request->hasFile('file')) {
            return response()->json([
                'success' => false,
                'message' => 'No file uploaded.'
            ], 400);
        }

        $file = $request->file('file');
        
        try {
            $sheets = Excel::toArray(new NomineesImport, $file);
            $rows = $sheets[0] ?? [];
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error reading Excel file: ' . $e->getMessage()
            ], 400);
        }

        if (count($rows) <= 1) {
            return response()->json([
                'success' => false,
                'message' => 'The uploaded file contains no nominee records.'
            ], 400);
        }

        $headers = $rows[0];
        $mapping = [];
        foreach ($headers as $index => $header) {
            if (is_null($header)) continue;
            $headerLower = strtolower(str_replace(["\r", "\n", ' ', '?'], '', $header));
            
            if (str_contains($headerLower, 'gdprcompliance')) {
                $mapping[$index] = 'gdpr_compliance';
            } elseif ($headerLower === 'unit') {
                $mapping[$index] = 'unit';
            } elseif ($headerLower === 'subunit') {
                $mapping[$index] = 'sub_unit';
            } elseif (str_contains($headerLower, 'client/prospects') || str_contains($headerLower, 'clientorprospect') || str_contains($headerLower, 'clientprospects')) {
                $mapping[$index] = 'client_or_prospect';
            } elseif (str_contains($headerLower, 'firstname')) {
                $mapping[$index] = 'first_name';
            } elseif (str_contains($headerLower, 'lastname')) {
                $mapping[$index] = 'last_name';
            } elseif (str_contains($headerLower, 'emailaddress') || $headerLower === 'email') {
                $mapping[$index] = 'email';
            } elseif ($headerLower === 'company') {
                $mapping[$index] = 'company';
            } elseif ($headerLower === 'title') {
                $mapping[$index] = 'title';
            } elseif (str_contains($headerLower, 'joblevel')) {
                $mapping[$index] = 'job_level';
            } elseif (str_contains($headerLower, 'primaryaccmanagername') || str_contains($headerLower, 'primaryaccountmanagername')) {
                $mapping[$index] = 'primary_account_manager_name';
            } elseif (str_contains($headerLower, 'primaryaccmanageremailid1') || str_contains($headerLower, 'primaryaccountmanageremail') || str_contains($headerLower, 'primaryaccmanageremail')) {
                $mapping[$index] = 'primary_account_manager_email';
            } elseif (str_contains($headerLower, 'accountmanageremailid1') || str_contains($headerLower, 'accountmanageremail1')) {
                $mapping[$index] = 'account_manager_email_1';
            } elseif (str_contains($headerLower, 'accountmanageremailid2') || str_contains($headerLower, 'accountmanageremail2')) {
                $mapping[$index] = 'account_manager_email_2';
            } elseif (str_contains($headerLower, 'business/it') || str_contains($headerLower, 'businessit')) {
                $mapping[$index] = 'business_or_it';
            } elseif ($headerLower === 'industry') {
                $mapping[$index] = 'industry';
            } elseif ($headerLower === 'country') {
                $mapping[$index] = 'country';
            } elseif (str_contains($headerLower, 'invitefor')) {
                $mapping[$index] = 'invite_for';
            } elseif (str_contains($headerLower, 'invitespouse')) {
                $mapping[$index] = 'invite_spouse';
            } elseif (str_contains($headerLower, 'govt/stateowned') || str_contains($headerLower, 'govtstateowned') || str_contains($headerLower, 'govtcompany')) {
                $mapping[$index] = 'govt_or_state_owned';
            }
        }

        $initialCount = DB::table('nominees_master')
            ->where('event_id', $event->id)
            ->where('nominator_id', $userId)
            ->count();

        // Get effective limit (custom vs default)
        $customLimit = DB::table('nominator_event_limits')
            ->where('event_id', $event->id)
            ->where('nominator_id', $userId)
            ->whereNull('deleted_at')
            ->first();
        
        $effectiveLimit = $customLimit ? (int)$customLimit->max_nominees : (int)$event->max_nominees_per_form;

        $successCount = 0;
        $failedCount = 0;
        $failedList = [];
        $processedEmails = [];
        $successfulNominees = [];

        // Process from row index 1 (skipping header)
        for ($i = 1; $i < count($rows); $i++) {
            $row = $rows[$i];
            
            // Check if row is completely empty
            $isEmpty = true;
            foreach ($row as $cell) {
                if (!is_null($cell) && trim((string)$cell) !== '') {
                    $isEmpty = false;
                    break;
                }
            }
            if ($isEmpty) continue;

            $rowData = [];
            foreach ($mapping as $index => $field) {
                $rowData[$field] = isset($row[$index]) ? trim((string)$row[$index]) : null;
            }

            $email = $rowData['email'] ?? '';

            try {
                // Validation rules
                $requiredFields = ['first_name', 'last_name', 'email', 'company', 'title', 'job_level', 'primary_account_manager_name', 'primary_account_manager_email', 'business_or_it', 'country'];
                foreach ($requiredFields as $req) {
                    if (empty($rowData[$req])) {
                        throw new \Exception(ucwords(str_replace('_', ' ', $req)) . " is required.");
                    }
                }

                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    throw new \Exception("Invalid Email format.");
                }

                if (!filter_var($rowData['primary_account_manager_email'], FILTER_VALIDATE_EMAIL)) {
                    throw new \Exception("Invalid Primary Account Manager Email format.");
                }

                $jobLevelClean = trim((string)$rowData['job_level']);
                if (str_contains($jobLevelClean, '.')) {
                    $jobLevelClean = explode('.', $jobLevelClean)[0];
                }
                if (!in_array($jobLevelClean, ['1', '2', '3', '4'])) {
                    throw new \Exception("Job Level must be 1, 2, 3, or 4.");
                }
                $rowData['job_level'] = $jobLevelClean;

                $businessOrIt = ucfirst(strtolower(trim($rowData['business_or_it'])));
                if ($businessOrIt === 'It') {
                    $businessOrIt = 'IT';
                }
                if (!in_array($businessOrIt, ['Business', 'IT'])) {
                    throw new \Exception("Business / IT must be 'Business' or 'IT'.");
                }
                $rowData['business_or_it'] = $businessOrIt;

                if ($event->type === 'hospitality') {
                    $hospitalityFields = ['invite_for', 'invite_spouse', 'govt_or_state_owned'];
                    foreach ($hospitalityFields as $req) {
                        if (empty($rowData[$req])) {
                            throw new \Exception(ucwords(str_replace('_', ' ', $req)) . " is required for Hospitality events.");
                        }
                    }
                    $inviteSpouse = ucfirst(strtolower(trim($rowData['invite_spouse'])));
                    if (!in_array($inviteSpouse, ['Yes', 'No'])) {
                        throw new \Exception("Invite Spouse must be 'Yes' or 'No'.");
                    }
                    $rowData['invite_spouse'] = $inviteSpouse;

                    $govtOrState = ucfirst(strtolower(trim($rowData['govt_or_state_owned'])));
                    if (!in_array($govtOrState, ['Yes', 'No'])) {
                        throw new \Exception("Govt / State Owned must be 'Yes' or 'No'.");
                    }
                    $rowData['govt_or_state_owned'] = $govtOrState;
                }

                // Check for duplicate in the same file
                if (in_array(strtolower($email), $processedEmails)) {
                    throw new \Exception("Duplicate nominee email in the uploaded file.");
                }
                $processedEmails[] = strtolower($email);

                // Already nominated check in database
                $existing = DB::table('nominees_master')
                    ->where('event_id', $event->id)
                    ->where('email', $email)
                    ->first();
                if ($existing) {
                    if ($existing->nominator_id == $userId) {
                        throw new \Exception("Already nominated by you for this event.");
                    } else {
                        throw new \Exception("Already nominated by another nominator for this event.");
                    }
                }

                // Check max nomination limit
                if ($initialCount + $successCount >= $effectiveLimit) {
                    throw new \Exception("Nomination limit of " . $effectiveLimit . " reached for this event.");
                }

                // Insert row
                DB::transaction(function () use ($event, $userId, $rowData, $email) {
                    $clientOrProspect = 'Client';
                    if (isset($rowData['client_or_prospect']) && str_contains(strtolower($rowData['client_or_prospect']), 'prospect')) {
                        $clientOrProspect = 'Prospect';
                    } elseif (isset($rowData['gdpr_compliance']) && str_contains(strtolower($rowData['gdpr_compliance']), 'prospect')) {
                        $clientOrProspect = 'Prospect';
                    }

                    $nomineeId = DB::table('nominees_master')->insertGetId([
                        'event_id' => $event->id,
                        'nominator_id' => $userId,
                        'gdpr_compliance' => $rowData['gdpr_compliance'] ?: 'Existing Business Relationship (Client)',
                        'unit' => $rowData['unit'] ?: 'FS',
                        'sub_unit' => $rowData['sub_unit'] ?: 'FSIB',
                        'client_or_prospect' => $clientOrProspect,
                        'first_name' => $rowData['first_name'],
                        'last_name' => $rowData['last_name'],
                        'email' => $email,
                        'company' => $rowData['company'],
                        'title' => $rowData['title'],
                        'job_level' => $rowData['job_level'],
                        'primary_account_manager_name' => $rowData['primary_account_manager_name'],
                        'primary_account_manager_email' => $rowData['primary_account_manager_email'],
                        'account_manager_email_1' => $rowData['account_manager_email_1'] ?: null,
                        'account_manager_email_2' => $rowData['account_manager_email_2'] ?: null,
                        'business_or_it' => $rowData['business_or_it'],
                        'industry' => $rowData['industry'] ?: 'Technology',
                        'country' => $rowData['country'],
                        'approval_status' => 'pending',
                        'invite_status' => 'Invite Pending',
                        'delivery_status' => 'Sent',
                        'is_dnc_contact' => false,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    if ($event->type === 'hospitality') {
                        DB::table('hospitality_meta')->insert([
                            'nominee_id' => $nomineeId,
                            'invite_for' => $rowData['invite_for'],
                            'invite_spouse' => $rowData['invite_spouse'],
                            'govt_or_state_owned' => $rowData['govt_or_state_owned'],
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                });

                $successfulNominees[] = [
                    'first_name' => $rowData['first_name'],
                    'last_name' => $rowData['last_name'],
                    'email' => $email,
                    'company' => $rowData['company'],
                    'title' => $rowData['title'],
                    'unit' => $rowData['unit'] ?: 'FS',
                ];
                $successCount++;
            } catch (\Exception $ex) {
                $failedCount++;
                $failedList[] = [
                    'email' => $email ?: 'Line ' . ($i + 1),
                    'reason' => $ex->getMessage()
                ];
            }
        }

        if ($successCount > 0) {
            try {
                $nominator = Auth::user();
                $adminEmails = \App\Helpers\MailHelper::getAdminEmails();
                $unitSpocEmails = DB::table('event_assignments')
                    ->join('users', 'event_assignments.user_id', '=', 'users.id')
                    ->join('roles', 'users.role_id', '=', 'roles.id')
                    ->where('event_assignments.event_id', $event->id)
                    ->where('roles.name', 'unit_spoc')
                    ->where('users.status', 1)
                    ->pluck('users.email')
                    ->toArray();
                $recipientEmails = array_unique(array_merge($adminEmails, $unitSpocEmails));

                if (!empty($recipientEmails)) {
                    send_templated_email($recipientEmails, 'bulk_nomination_added', [
                        'subject' => 'Bulk Nominations Uploaded (' . $successCount . ') - ' . $event->name,
                        'event_name' => $event->name,
                        'event_code' => $event->event_code,
                        'nominator_name' => trim(($nominator->name ?? '') . ' ' . ($nominator->last_name ?? '')),
                        'nominator_email' => $nominator->email ?? '',
                        'nominees' => $successfulNominees,
                        'count' => $successCount,
                    ]);
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Failed to dispatch bulk nomination email: ' . $e->getMessage());
            }
        }

        return response()->json([
            'success' => true,
            'total' => $successCount + $failedCount,
            'success_count' => $successCount,
            'failed_count' => $failedCount,
            'failed_list' => $failedList
        ]);
    }
}
