<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WhatsAppGatewayController extends Controller
{
    public function connect()
    {
        $user = Auth::user();

        $data = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => 'admin', 
            'timestamp' => time() 
        ];

        
        $payload = base64_encode(json_encode($data));

        
        

        $secretKey = config('services.wa_gateway.secret');
        $signature = hash_hmac('sha256', $payload, $secretKey);

        
        $gatewayUrl = config('services.wa_gateway.url');
        
        
        $redirectUrl = "{$gatewayUrl}/auth/sso?payload={$payload}&signature={$signature}";

        
        return redirect()->away($redirectUrl);
    }
}