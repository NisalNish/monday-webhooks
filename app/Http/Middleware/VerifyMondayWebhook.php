<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyMondayWebhook
{
    public function handle(Request $request, Closure $next)
    {
        $inputFields = $request->input('inputFields');

        // Basic structure check
        if (!$inputFields || !is_array($inputFields)) {
            return response()->json(['error' => 'Invalid webhook payload: inputFields missing'], 400);
        }

        // Optional: check specific fields exist
        if (
            empty($inputFields['itemId']) ||
            empty($inputFields['boardId']) ||
            empty($inputFields['columnId']) ||
            empty($inputFields['value'])
        ) {
            return response()->json(['error' => 'Missing required input fields'], 422);
        }

        return $next($request);
    }
}
