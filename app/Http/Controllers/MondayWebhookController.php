<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\MondayItem;

class MondayWebhookController extends Controller
{
    public function handle(Request $request)
    {
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
