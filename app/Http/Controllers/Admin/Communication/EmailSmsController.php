<?php

namespace App\Http\Controllers\Admin\Communication;

use App\Http\Controllers\Controller;
use App\Models\EmailSms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;

class EmailSmsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $lists = EmailSms::orderBy('created_at', 'desc')->get();
        return view('admin.communication.emailsms.index', compact('lists'));
    }

    public function store(Request $request)
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

            // Store the message
            $emailSms = EmailSms::create([
                'title' => $request->title,
                'description' => $request->description,
                'send_through' => $request->send_through,
                'recipient_type' => $request->recipient_type,
                'recipients' => json_encode($request->recipients),
                'status' => 'pending'
            ]);

            // Send through RapidAPI based on type
            if ($request->send_through === 'email') {
                $this->sendEmail($request);
            } else {
                $this->sendSMS($request);
            }

            return response()->json([
                'success' => true,
                'message' => 'Message sent successfully',
                'data' => $emailSms
            ], 201);

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
        // RapidAPI Email Service Integration
        $response = Http::withHeaders([
            'X-RapidAPI-Key' => config('services.rapidapi.key'),
            'X-RapidAPI-Host' => config('services.rapidapi.email_host')
        ])->post(config('services.rapidapi.email_url'), [
            'to' => $request->recipients,
            'subject' => $request->title,
            'body' => $request->description,
            'from' => config('mail.from.address')
        ]);

        return $response->json();
    }

    private function sendSMS($request)
    {
        // RapidAPI SMS Service Integration
        $response = Http::withHeaders([
            'X-RapidAPI-Key' => config('services.rapidapi.key'),
            'X-RapidAPI-Host' => config('services.rapidapi.sms_host')
        ])->post(config('services.rapidapi.sms_url'), [
            'to' => $request->recipients,
            'message' => $request->description,
            'from' => config('services.sms.from')
        ]);

        return $response->json();
    }

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
}
