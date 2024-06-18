<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\TokenManager;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class RegisterController
 *
 * Handles user registration and OAuth2 token generation.
 *
 * @package App\Http\Controllers\Auth
 */
class RegisterController extends Controller
{
    /**
     * @var TokenManager
     */
    protected TokenManager $tokenManager;

    /**
     * RegisterController constructor.
     *
     * @param TokenManager $tokenManager
     */
    public function __construct(TokenManager $tokenManager)
    {
        $this->tokenManager = $tokenManager;
    }

    /**
     * Show the registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm(): \Illuminate\View\View
    {
        return view('auth.register');
    }

    /**
     * Handle the registration request.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request): \Illuminate\Http\RedirectResponse
    {
        // Validate input
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create a new user
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
        ]);

        // Log the user in
        Auth::login($user);

        // Generate OAuth2 token
        $this->tokenManager->generateAccessToken($validatedData['email'], $validatedData['password']);

        // Redirect user or return response
        return redirect('/')->with('success', 'Registration successful!');
    }
}
