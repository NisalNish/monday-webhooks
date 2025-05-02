<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MondayWebhookController;

Route::get('/', function () {
    return view('welcome');
});


Route::post('/monday-webhook', [MondayWebhookController::class, 'handle'])
     ->middleware('verify.monday.webhook');
