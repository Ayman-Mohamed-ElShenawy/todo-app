<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): JsonResponse
    {
        // $request->authenticate();
        // $request->session()->regenerate();
        // return redirect()->intended(route('dashboard', absolute: false));

        $request->authenticate();
        // Get the authenticated user
        $user = Auth::user();

        // Generate the API token using Sanctum
        $token = $user->createToken('auth_token')->plainTextToken;

        // Return the token in the response
        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => $user,
        ]);



    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): JsonResponse
    {
        // Auth::guard('web')->logout();

        // $request->session()->invalidate();

        // $request->session()->regenerateToken();

        // return redirect('/');
        // return response()->json([
        //     'status' => 200,
        //     'message' => 'Logged out successfully'
        // ], 200);
        $user = $request->user();

        if ($user) {
            // Revoke all tokens for the user
            $user->tokens()->delete();

            return response()->json([
                'status' => 200,
                'message' => 'Logged out successfully',
            ], 200);
        }

        return response()->json([
            'status' => 401,
            'message' => 'Not authenticated',
        ], 401);
    }
}
