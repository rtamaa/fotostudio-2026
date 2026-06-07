<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransCallbackController extends Controller
{
    protected MidtransService $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    public function handle(Request $request)
    {
        Log::info('Midtrans Callback Received', $request->all());
        
        try {
            $this->midtransService->handleWebhook();
            return response()->json(['status' => 'ok']);
        } catch (\Exception $e) {
            Log::error('Callback Error: ' . $e->getMessage());
            return response()->json(['status' => 'error'], 500);
        }
    }
}