<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        return view('settings.edit-profile');
    }

    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $user = Auth::user();

        $user->name = $request->validated('name');

        if ($request->filled('password')) {
            $user->password = $request->validated('password');
        }

        $user->save();

        return redirect()->route('settings.profile.edit')
            ->with('success', 'Profile updated successfully.');
    }
}
