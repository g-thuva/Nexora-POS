<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;

class UserController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            // Super admin can see all users - eager-load shop relation with all columns needed for view
            $users = User::with(['shop:id,name,email,subscription_status,status'])->latest()->get();
        } elseif ($user->isShopOwner()) {
            // Shop owner can see users in their shop - eager-load shop relation
            $users = User::with('shop:id,name')
                        ->where('shop_id', $user->ownedShop->id)
                        ->orWhere('id', $user->id) // Include themselves
                        ->latest()->get();
        } else {
            // Other users can only see themselves - eager-load shop relation
            $users = User::with('shop:id,name')->where('id', $user->id)->get();
        }

        return view('users.index', [
            'users' => $users
        ]);
    }

    public function create()
    {
        $user = auth()->user();

        // Only super admin and shop owners can create users
        if (!$user->isAdmin() && !$user->isShopOwner()) {
            return redirect()->route('users.index')
                           ->with('error', 'You do not have permission to create users.');
        }

        $availableRoles = $this->getAvailableRoles($user);
        $availableShops = $this->getAvailableShops($user);

        return view('users.create', [
            'availableRoles' => $availableRoles,
            'availableShops' => $availableShops
        ]);
    }

    private function getAvailableRoles($user)
    {
        if ($user->isAdmin()) {
            return [
                'admin' => 'Super Admin',
                'admin' => 'Admin',
                'shop_owner' => 'Shop Owner',
                'manager' => 'Manager',
                'employee' => 'Employee'
            ];
        } elseif ($user->isShopOwner()) {
            return [
                'manager' => 'Manager',
                'employee' => 'Employee'
            ];
        }

        return [];
    }

    private function getAvailableShops($user)
    {
        if ($user->isAdmin()) {
            return \App\Models\Shop::all(['id', 'name']);
        } elseif ($user->isShopOwner()) {
            return collect([$user->ownedShop]);
        }

        return collect([]);
    }

    public function store(StoreUserRequest $request)
    {
        $currentUser = auth()->user();

        // Validate permissions
        if (!$currentUser->isAdmin() && !$currentUser->isShopOwner()) {
            return redirect()->route('users.index')
                           ->with('error', 'You do not have permission to create users.');
        }

        // Validate role assignment permissions
        $requestedRole = $request->get('role');
        $availableRoles = array_keys($this->getAvailableRoles($currentUser));

        if (!in_array($requestedRole, $availableRoles)) {
            return back()->withErrors(['role' => 'You do not have permission to assign this role.']);
        }

        // Set shop_id based on user permissions and request
        $userData = $request->all();

        if ($currentUser->isShopOwner()) {
            // Shop owners can only create users for their own shop
            $userData['shop_id'] = $currentUser->ownedShop->id;
        } elseif ($currentUser->isAdmin()) {
            // Super admin can specify shop or leave null for shop_owner role
            if ($requestedRole === 'shop_owner') {
                $userData['shop_id'] = null; // Shop owners don't have shop_id, they own a shop
            }
            // For other roles, shop_id should be provided in the request
        }

        $user = User::create($userData);

        /**
         * Handle upload an image
         */
        if($request->hasFile('photo')){
            $file = $request->file('photo');
            $filename = hexdec(uniqid()).'.'.$file->getClientOriginalExtension();

            $file->storeAs('profile/', $filename, 'public');
            $user->update([
                'photo' => $filename
            ]);
        }

        return redirect()
            ->route('users.index')
            ->with('success', 'New User has been created!');
    }

    public function show(User $user)
    {
        return view('users.show', [
            'user' => $user
        ]);
    }

    public function edit(User $user)
    {
        return view('users.edit', [
            'user' => $user
        ]);
    }

    public function update(UpdateUserRequest $request, User $user)
    {

//        if ($validatedData['email'] != $user->email) {
//            $validatedData['email_verified_at'] = null;
//        }

        $user->update($request->except('photo'));

        /**
         * Handle upload image with Storage.
         */
        if($request->hasFile('photo')){

            // Delete Old Photo
            if($user->photo){
                unlink(public_path('storage/profile/') . $user->photo);
            }

            // Prepare New Photo
            $file = $request->file('photo');
            $fileName = hexdec(uniqid()).'.'.$file->getClientOriginalExtension();

            // Store an image to Storage
            $file->storeAs('profile/', $fileName, 'public');

            // Save DB
            $user->update([
                'photo' => $fileName
            ]);
        }

        return redirect()
            ->route('users.index')
            ->with('success', 'User has been updated!');
    }

    public function updatePassword(Request $request, String $username)
    {
        # Validation
        $validated = $request->validate([
            'password' => 'required_with:password_confirmation|min:6',
            'password_confirmation' => 'same:password|min:6',
        ]);

        # Update the new Password
        User::where('username', $username)->update([
            'password' => Hash::make($validated['password'])
        ]);

        return redirect()
            ->route('users.index')
            ->with('success', 'User has been updated!');
    }

    public function destroy(User $user)
    {
        // Prevent deletion of super admin users
        if ($user->isAdmin()) {
            return redirect()
                ->route('users.index')
                ->with('error', 'System administrators cannot be deleted for security reasons.');
        }

        /**
         * Delete photo if exists.
         */
        if($user->photo){
            unlink(public_path('storage/profile/') . $user->photo);
        }

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'User has been deleted!');
    }
}
