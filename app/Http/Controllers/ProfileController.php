<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        return view('profile.edit-profile', [
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $request->user()->id],
        ]);

        $user = $request->user();
        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Profile updated successfully.',
                'email_verified' => $user->hasVerifiedEmail(),
            ]);
        }

        return back()->with('status', 'profile-updated');
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required'],
        ]);

        $user = $request->user();

        if (! Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'The provided password is incorrect.',
            ], 422);
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $user->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Account deleted successfully.',
                'redirect_url' => route('login'),
            ]);
        }

        return redirect()->route('login');
    }
}
