<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\MondayItem;

class MondayWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // Log raw incoming request for debugging
        Log::info('Monday Webhook Triggered:', $request->all());

        // Extract input fields from the webhook payload
        $data = $request->input('inputFields');
        $itemId = $data['itemId'] ?? null;
        $boardId = $data['boardId'] ?? null;
        $columnId = $data['columnId'] ?? null;
        $value = $data['value'] ?? null;

        // Validate required fields
        if (!$itemId || !$boardId || !$columnId || !$value) {
            return response()->json(['error' => 'Missing required fields'], 400);
        }

        // Save or update record in the database
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
