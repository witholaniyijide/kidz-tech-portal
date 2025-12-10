<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PushNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PushTokenController extends Controller
{
    protected PushNotificationService $pushService;

    public function __construct(PushNotificationService $pushService)
    {
        $this->pushService = $pushService;
    }

    /**
     * Register a push token for the authenticated user.
     * 
     * POST /api/push-token
     * Body: { "token": "fcm_token_here", "device_type": "android|ios|web" }
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'token' => 'required|string|max:500',
            'device_type' => 'required|in:android,ios,web',
        ]);

        $user = Auth::user();

        $success = $this->pushService->registerToken(
            $user,
            $validated['token'],
            $validated['device_type']
        );

        if ($success) {
            return response()->json([
                'success' => true,
                'message' => 'Push token registered successfully.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to register push token.',
        ], 500);
    }

    /**
     * Remove the push token (on logout).
     * 
     * DELETE /api/push-token
     */
    public function destroy()
    {
        $user = Auth::user();

        $success = $this->pushService->unregisterToken($user);

        if ($success) {
            return response()->json([
                'success' => true,
                'message' => 'Push token removed successfully.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to remove push token.',
        ], 500);
    }

    /**
     * Test push notification (for debugging).
     * 
     * POST /api/push-token/test
     */
    public function test(Request $request)
    {
        $user = Auth::user();

        if (empty($user->push_token)) {
            return response()->json([
                'success' => false,
                'message' => 'No push token registered for this user.',
            ], 400);
        }

        $success = $this->pushService->sendToUser(
            $user,
            'Test Notification',
            'This is a test push notification from KidzTech Portal.',
            ['type' => 'test']
        );

        return response()->json([
            'success' => $success,
            'message' => $success ? 'Test notification sent!' : 'Failed to send test notification.',
        ]);
    }
}
