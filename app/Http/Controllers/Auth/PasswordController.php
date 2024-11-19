<?php

namespace App\Http\Controllers\Auth;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{




    /**
     * Display the form to change the user's password.
     */

    public function edit()
    {
        return Inertia::render('Employee/Settings/ChangePassword');
    }
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        request()->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8',
        ]);

        if (!\Hash::check(request('current_password'), auth()->user()->password)) {
            dd('here');
            return redirect()->back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }
        auth()->user()->update([
            'password' => bcrypt(request('new_password'))
        ]);
        return back();
    }
}
