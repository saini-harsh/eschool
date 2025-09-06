<?php

namespace App\Http\Controllers\Admin\Communication;

use Log;
use App\Mail\SmsEmail;
use App\Models\Section;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\EmailSms;
use App\Models\Institution;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use App\Models\NonWorkingStaff;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class EmailSmsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $lists = EmailSms::orderBy('created_at', 'desc')->get();

        // Ensure all records have valid recipients data for the view
        $lists->each(function ($item) {
            if (!is_array($item->recipients)) {
                // Try to decode if it's a JSON string
                $decoded = json_decode($item->recipients, true);
                if (is_array($decoded)) {
                    $item->recipients = $decoded;
                } else {
                    // Set to empty array if invalid
                    $item->recipients = [];
                }
            }
        });

        return view('admin.communication.emailsms.index', compact('lists'));
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'send_through' => 'required|in:email,sms,whatsapp',
                'recipient_type' => 'required|in:group,individual,class',
                'recipients' => 'required|array',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Step 1: Store message as pending
            $emailSms = EmailSms::create([
                'title' => $request->title,
                'description' => $request->description,
                'send_through' => $request->send_through,
                'recipient_type' => $request->recipient_type,
                'recipients' => json_encode($request->recipients),
                'status' => 'pending'
            ]);

            // Step 2: Try sending
            $sendStatus = false;

            if ($request->send_through === 'email') {
                $sendStatus = $this->sendEmail($request);
            }
             if ($request->send_through === 'sms') {
                $sendStatus = $this->sendSMS($request);
            }
            if ($request->send_through === 'whatsapp') {
                $sendStatus = $this->sendWhatsApp($request);
            }

            // Step 3: Update DB status based on result
            $emailSms->update([
                'status' => $sendStatus ? 'sent' : 'failed'
            ]);

            return response()->json([
                'success' => $sendStatus,
                'message' => $sendStatus ? 'Message sent successfully' : 'Message failed to send',
                'data' => $emailSms
            ], $sendStatus ? 200 : 500);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while sending the message',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    private function sendEmail($request)
    {
        $recipients = is_array($request->recipients)
                        ? $request->recipients
                        : explode(',', $request->recipients);
        Mail::to($recipients)->send(new SmsEmail($request->title, $request->description));

        return response()->json([
            'status' => 'success',
            'message' => 'Email sent successfully',
            'recipients' => $recipients
        ]);
    }

    private function sendSMS($request)
    {
        try {
            $recipients = is_array($request->recipients)
                ? $request->recipients
                : explode(',', $request->recipients);

            $success = true;

            foreach ($recipients as $recipient) {
                $response = Http::withHeaders([
                    'X-RapidAPI-Key'  => config('services.rapidapi.key'),
                    'X-RapidAPI-Host' => config('services.rapidapi.sms_host'),
                    'Content-Type'    => 'application/json',
                ])->post(config('services.rapidapi.sms_url'), [
                    "data" => [
                        "phone_number" => "+91".trim($recipient['phone']),
                        "text"         => strip_tags($request->description),
                        "api_key"      => config('services.rapidapi.TEXTFLOW_API_KEY'),
                    ]
                ]);

                \Log::info('SMS API response', [
                    'recipient' => $recipient,
                    'status'    => $response->status(),
                    'body'      => $response->body(),
                ]);


                if ($response->failed()) {
                    $success = false;
                    \Log::error('Text SMS sending failed', [
                        'recipient' => $recipient,
                        'response'  => $response->body()
                    ]);
                }
            }

            return $success;
        } catch (\Exception $e) {
            \Log::error('Text SMS exception: ' . $e->getMessage());
            return false;
        }
    }

    private function sendWhatsApp($request)
    {
        try {
            $recipients = is_array($request->recipients)
                ? $request->recipients
                : explode(',', $request->recipients);

            $success = true;

            foreach ($recipients as $recipient) {
                $response = Http::asForm()->withHeaders([
                    'X-RapidAPI-Key'  => config('services.rapidapi.key'),
                    'X-RapidAPI-Host' => config('services.rapidapi.whatsapp_host'),
                ])->post(config('services.rapidapi.whatsapp_url'), [
                    "account"   => config('services.rapidapi.whatsapp_account'), // 👈 your unique account ID
                    "recipient" => "+91" . (is_array($recipient) ? $recipient['phone'] : $recipient), // format as E.164
                    "message"   => strip_tags($request->description),
                    "type"      => "text", // text / media / document
                ]);
                    dd($response->json());
                if ($response->failed()) {
                    $success = false;
                    \Log::error('WhatsApp sending failed', [
                        'recipient' => $recipient,
                        'response'  => $response->body()
                    ]);
                }
            }

            return $success;
        } catch (\Exception $e) {
            \Log::error('WhatsApp exception: ' . $e->getMessage());
            return false;
        }
    }



    // private function sendSMS($request)
    // {
    //     // RapidAPI SMS Service Integration
    //     $response = Http::withHeaders([
    //         'X-RapidAPI-Key' => config('services.rapidapi.key'),
    //         'X-RapidAPI-Host' => config('services.rapidapi.sms_host')
    //     ])->post(config('services.rapidapi.sms_url'), [
    //         'to' => $request->recipients,
    //         'message' => $request->description,
    //         'from' => config('services.sms.from')
    //     ]);

    //     return $response->json();
    // }

    public function getEmailSms()
    {
        try {
            $emailSms = EmailSms::orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'data' => $emailSms
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching messages'
            ], 500);
        }
    }

    public function edit($id)
    {
        try {
            $emailSms = EmailSms::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $emailSms
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Message not found'
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'send_through' => 'required|in:email,sms',
                'recipient_type' => 'required|in:group,individual,class',
                'recipients' => 'required|array',
                'recipients.*' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $emailSms = EmailSms::findOrFail($id);
            $emailSms->update([
                'title' => $request->title,
                'description' => $request->description,
                'send_through' => $request->send_through,
                'recipient_type' => $request->recipient_type,
                'recipients' => json_encode($request->recipients)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Message updated successfully',
                'data' => $emailSms
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the message',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function delete($id)
    {
        try {
            $emailSms = EmailSms::findOrFail($id);
            $emailSms->delete();

            return response()->json([
                'success' => true,
                'message' => 'Message deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the message',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $emailSms = EmailSms::findOrFail($id);
            $emailSms->update(['status' => $request->status]);

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all institutions for group selection
     */
    public function getInstitutions()
    {
        try {
            $institutions = Institution::where('status', 1)
                ->select('id', 'name')
                ->orderBy('name')
                ->get();

            if ($institutions->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No active institutions found',
                    'data' => []
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $institutions
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching institutions',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get teachers by institution for group selection
     */
    public function getTeachersByInstitution($institutionId)
    {
        try {
            $teachers = Teacher::where('institution_id', $institutionId)
                ->where('status', 1)
                ->select('id', 'first_name', 'last_name', 'email', 'phone')
                ->get();

            if ($teachers->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No teachers found for this institution',
                    'count' => 0
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $teachers,
                'count' => $teachers->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching teachers',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get students by institution for group selection
     */
    public function getStudentsByInstitution($institutionId)
    {
        try {
            $students = Student::where('institution_id', $institutionId)
                ->where('status', 1)
                ->select('id', 'first_name', 'last_name', 'email', 'phone')
                ->get();

            if ($students->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No students found for this institution',
                    'count' => 0
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $students,
                'count' => $students->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching students',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get non-working staff by institution for group selection
     */
    public function getNonWorkingStaffByInstitution($institutionId)
    {
        try {
            $staff = NonWorkingStaff::where('institution_id', $institutionId)
                ->where('status', 1)
                ->select('id', 'first_name', 'email', 'phone')
                ->get();

            if ($staff->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No non-working staff found for this institution',
                    'count' => 0
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $staff,
                'count' => $staff->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching non-working staff',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get parents by institution for group selection
     * Note: Parents are typically associated with students
     */
    public function getParentsByInstitution($institutionId)
    {
        try {
            // First, check if parent fields exist in the students table
            $student = Student::first();
            $hasParentFields = $student && (
                property_exists($student, 'parent_name') ||
                property_exists($student, 'parent_phone') ||
                property_exists($student, 'parent_email')
            );

            if (!$hasParentFields) {
                // If parent fields don't exist, return students as parents (using student contact info)
                $students = Student::where('institution_id', $institutionId)
                    ->where('status', 1)
                    ->select('id', 'first_name', 'last_name', 'email', 'phone')
                    ->get();

                // Treat students as parents for contact purposes
                $parents = $students->map(function($student) {
                    return [
                        'id' => 'parent_' . $student->id,
                        'name' => $student->first_name . ' ' . $student->last_name . ' (Parent)',
                        'phone' => $student->phone,
                        'email' => $student->email,
                        'is_student_contact' => true
                    ];
                });

                return response()->json([
                    'success' => true,
                    'data' => $parents,
                    'count' => $parents->count(),
                    'message' => 'Using student contact information as parent contacts'
                ]);
            }

            // If parent fields exist, use them
            $students = Student::where('institution_id', $institutionId)
                ->where('status', 1)
                ->select('id', 'parent_name', 'parent_phone', 'parent_email')
                ->whereNotNull('parent_name')
                ->get();

            // Extract unique parent information
            $parents = $students->map(function($student) {
                return [
                    'id' => 'parent_' . $student->id,
                    'name' => $student->parent_name,
                    'phone' => $student->parent_phone,
                    'email' => $student->parent_email,
                    'is_student_contact' => false
                ];
            })->unique('phone')->values();

            return response()->json([
                'success' => true,
                'data' => $parents,
                'count' => $parents->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching parents',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get classes by institution for class selection
     */
    public function getClassesByInstitution($institutionId)
    {
        try {
            $classes = SchoolClass::where('institution_id', $institutionId)
                ->where('status', 1)
                ->select('id', 'name')
                ->orderBy('name')
                ->get();

            if ($classes->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No classes found for this institution',
                    'data' => []
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $classes
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching classes',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get sections by class for class selection
     */
    public function getSectionsByClass($classId)
    {
        try {
            $class = SchoolClass::findOrFail($classId);
            $sectionIds = json_decode($class->section_ids, true) ?? [];

            if (empty($sectionIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No sections found for this class',
                    'data' => []
                ], 404);
            }

            $sections = Section::whereIn('id', $sectionIds)
            ->where('status', 1)
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

            return response()->json([
                'success' => true,
                'data' => $sections
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching sections',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get students and parents by class and section for class selection
     */
    public function getStudentsAndParentsByClassSection($classId, $sectionId = null)
    {
        try {
            $query = Student::where('class_id', $classId)
                ->where('status', 1);

            if ($sectionId) {
                $query->where('section_id', $sectionId);
            }

            // Debug: Log the query and parameters
            \Log::info('Student query:', [
                'class_id' => $classId,
                'section_id' => $sectionId,
                'sql' => $query->toSql(),
                'bindings' => $query->getBindings()
            ]);

            $students = $query->select('id', 'first_name', 'last_name', 'email', 'phone')
                ->get();

            // Debug: Log the results
            \Log::info('Student query results:', [
                'count' => $students->count(),
                'students' => $students->toArray()
            ]);


            if ($students->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No students found for this class' . ($sectionId ? ' and section' : ''),
                    'data' => [
                        'students' => [],
                        'parents' => []
                    ]
                ], 404);
            }

            // Prepare students data
            $studentsData = $students->map(function($student) {
                return [
                    'id' => 'student_' . $student->id,
                    'name' => $student->first_name . ' ' . $student->last_name,
                    'email' => $student->email,
                    'phone' => $student->phone,
                    'type' => 'student'
                ];
            });

            // Prepare parents data (using student contact info as parent contacts)
            $parentsData = $students->map(function($student) {
                return [
                    'id' => 'parent_' . $student->id,
                    'name' => $student->first_name . ' ' . $student->last_name . ' (Parent)',
                    'email' => $student->email,
                    'phone' => $student->phone,
                    'type' => 'parent',
                    'is_student_contact' => true
                ];
            });

            return response()->json([
                'success' => true,
                'data' => [
                    'students' => $studentsData,
                    'parents' => $parentsData
                ],
                'counts' => [
                    'students' => $studentsData->count(),
                    'parents' => $parentsData->count()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching students and parents',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
