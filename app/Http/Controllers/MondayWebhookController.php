<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\MondayItem;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class MondayWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // Step 0: Verify JWT from Authorization header
        $authHeader = $request->header('Authorization');

        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return response()->json(['error' => 'Unauthorized – missing Bearer token'], 401);
        }

        $jwt = substr($authHeader, 7); // Remove "Bearer "
        $signingSecret = env('MONDAY_SIGNING_SECRET'); // Add this to .env

        try {
            $decoded = JWT::decode($jwt, new Key($signingSecret, 'HS256'));
            Log::info('JWT Verified:', (array) $decoded);
        } catch (\Exception $e) {
            Log::warning('JWT verification failed: ' . $e->getMessage());
            return response()->json(['error' => 'Unauthorized – invalid token'], 401);
        }

        // Step 1: Handle webhook URL verification challenge
        if ($request->has('challenge')) {
            Log::info('Responding to webhook challenge');
            return response($request->get('challenge'), 200);
        }

        // Step 2: Handle real webhook payload
        Log::info('Monday Webhook Triggered:', $request->all());

        $data = $request->input('inputFields');
        $itemId = $data['itemId'] ?? null;
        $boardId = $data['boardId'] ?? null;
        $columnId = $data['columnId'] ?? null;
        $value = $data['value'] ?? null;

        if (!$itemId || !$boardId || !$columnId || !$value) {
            return response()->json(['error' => 'Missing required fields'], 422);
        }

        MondayItem::updateOrCreate(
            ['item_id' => $itemId],
            [
                'board_id' => $boardId,
                'column_id' => $columnId,
                'status_value' => $value
            ]
        );

        return response()->json(['message' => 'Item saved successfully']);
    }
}
