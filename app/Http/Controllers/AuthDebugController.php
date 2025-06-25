<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\SocialiteUser;

class AuthDebugController extends Controller
{
    public function debugAuth(Request $request)
    {
        $data = [
            'authenticated' => Auth::check(),
            'user' => Auth::user() ? [
                'id' => Auth::user()->id,
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'roles' => Auth::user()->getRoleNames(),
                'permissions' => Auth::user()->getAllPermissions()->pluck('name'),
            ] : null,
            'session_id' => session()->getId(),
            'session_data' => session()->all(),
            'socialite_users' => SocialiteUser::with('user')->get()->map(function ($socialiteUser) {
                return [
                    'id' => $socialiteUser->id,
                    'provider' => $socialiteUser->provider,
                    'provider_id' => $socialiteUser->provider_id,
                    'user_id' => $socialiteUser->user_id,
                    'user_name' => $socialiteUser->user->name ?? 'N/A',
                    'user_email' => $socialiteUser->user->email ?? 'N/A',
                ];
            }),
            'request_headers' => $request->headers->all(),
            'request_url' => $request->fullUrl(),
            'request_method' => $request->method(),
        ];

        Log::info('Auth Debug Info', $data);

        return response()->json($data);
    }

    public function testOAuthCallback(Request $request)
    {
        Log::info('OAuth Callback Test', [
            'all_params' => $request->all(),
            'headers' => $request->headers->all(),
            'session_id' => session()->getId(),
            'authenticated' => Auth::check(),
        ]);

        return response()->json([
            'status' => 'callback_received',
            'params' => $request->all(),
            'authenticated' => Auth::check(),
        ]);
    }
} 