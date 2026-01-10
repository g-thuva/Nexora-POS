<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ProfileUpdateRequest;

class ProfileController extends Controller
{
    /**
     * Show user profile page (for regular users)
     */
    public function userProfile(Request $request)
    {
        return view('profile.user-profile', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update user profile from user profile page
     */
    public function userProfileUpdate(Request $request): RedirectResponse
    {
        $user = $request->user();

        // Check if this is a photo-only upload
        if ($request->has('photo_only') && $request->photo_only == '1')
        {
            // Only handle photo upload
            if ($file = $request->file('photo'))
            {
                $rules = ['photo' => 'image|file|max:5120'];
                $request->validate($rules);

                $fileName = hexdec(uniqid()).'.'.$file->getClientOriginalExtension();
                $path = 'public/profile/';

                // Delete old photo if exists
                if($user->photo)
                {
                    Storage::delete($path . $user->photo);
                }

                // Store new photo
                $file->storeAs($path, $fileName);

                User::where('id', $user->id)->update(['photo' => $fileName]);

                return redirect()
                    ->route('user.profile')
                    ->with('success', 'Profile photo has been updated!');
            }

            return redirect()
                ->route('user.profile')
                ->with('error', 'No photo file selected.');
        }

        // Handle regular profile update (name only)
        $rules = [
            'name' => 'required|max:50',
        ];

        $validatedData = $request->validate($rules);

        User::where('id', $user->id)->update($validatedData);

        return redirect()
            ->route('user.profile')
            ->with('success', 'Profile has been updated!');
    }

    public function edit(Request $request)
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        // Check if this is a photo-only upload
        if ($request->has('photo_only') && $request->photo_only == '1')
        {
            // Only handle photo upload
            if ($file = $request->file('photo'))
            {
                $rules = ['photo' => 'image|file|max:5120'];
                $request->validate($rules);

                $fileName = hexdec(uniqid()).'.'.$file->getClientOriginalExtension();
                $path = 'public/profile/';

                // Delete old photo if exists
                if($user->photo)
                {
                    Storage::delete($path . $user->photo);
                }

                // Store new photo
                $file->storeAs($path, $fileName);

                User::where('id', $user->id)->update(['photo' => $fileName]);

                return redirect()
                    ->route('profile.edit')
                    ->with('success', 'Profile photo has been updated!');
            }

            return redirect()
                ->route('profile.edit')
                ->with('error', 'No photo file selected.');
        }

        // Handle regular profile update (name and username only, no email)
        $rules = [
            'name' => 'required|max:50',
            'username' => 'required|min:4|max:25|alpha_dash:ascii|unique:users,username,'.$user->id
        ];

        $validatedData = $request->validate($rules);

        User::where('id', $user->id)->update($validatedData);

        return redirect()
            ->route('profile.edit')
            ->with('success', 'Profile has been updated!');
    }

    public function settings(Request $request)
    {
        return view('profile.settings', [
            'user' => $request->user(),
        ]);
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->to('/');
    }
}
