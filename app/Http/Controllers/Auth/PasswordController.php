<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validateWithBag('updatePassword', [
            'current_password' => ['required'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        // Custom validation for plain text passwords
        if ($request->user()->password !== $request->current_password) {
            return back()->withErrors([
                'current_password' => 'The provided password is incorrect.',
            ], 'updatePassword');
        }

        $request->user()->update([
            'password' => $request->password, // Store as plain text to match your system
        ]);

        return back()->with('status', 'password-updated');
    }
}
