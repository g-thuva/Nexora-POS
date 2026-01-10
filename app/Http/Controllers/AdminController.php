<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Show the admin dashboard.
     */
    public function dashboard()
    {
        // Get shop statistics
        $totalShops = Shop::count();
        $activeShops = Shop::where('subscription_status', 'active')->where('is_active', true)->count();
        $suspendedShops = Shop::where('subscription_status', 'suspended')->count();
        $overdueShops = Shop::where('subscription_end_date', '<', now())
            ->where('subscription_status', '!=', 'suspended')
            ->count();

        $stats = [
            'total_shops' => $totalShops,
            'active_shops' => $activeShops,
            'suspended_shops' => $suspendedShops,
            'overdue_shops' => $overdueShops,
        ];

        // Add global order KPIs from DB-side cache/view to avoid heavy aggregates here
        $kpiService = new \App\Services\KpiService();
        $orderKpis = $kpiService->getOrderKpis();
        $stats['total_orders'] = $orderKpis->total_orders ?? 0;
        $stats['orders_total_amount_cents'] = $orderKpis->total_amount ?? 0;
        $stats['completed_orders'] = $orderKpis->completed_count ?? 0;

        // Get overdue shops for the alert section
        $overdueShops = Shop::where('subscription_end_date', '<', now())
            ->where('subscription_status', '!=', 'suspended')
            ->with('owner')
            ->orderBy('subscription_end_date', 'asc')
            ->get();

        return view('admin.dashboard', compact('stats', 'overdueShops'));
    }

    /**
     * Show all shops for admin management
     */
    public function shops()
    {
        // Eager-load owner and pre-compute counts to avoid N+1 on products/orders/users
        $shops = Shop::with(['owner:id,name,email'])
            ->withCount(['users', 'products', 'orders'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        return view('admin.shops.index', compact('shops'));
    }

    /**
     * Show the form for creating a new shop (admin panel)
     */
    public function createShop()
    {
        $users = User::where('role', '!=', 'admin')->get();
        return view('admin.shops.create', compact('users'));
    }

    /**
     * Store a newly created shop in storage (admin panel)
     */
    public function storeShop(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'email' => 'required|email',
            'owner_id' => 'required|exists:users,id',
        ]);

        $shop = new Shop();
        $shop->name = $validated['name'];
        $shop->address = $validated['address'];
        $shop->phone = $validated['phone'];
        $shop->email = $validated['email'];
        $shop->owner_id = $validated['owner_id'];
        $shop->is_active = true;
        $shop->save();

        // Update the owner's role and shop assignment
        $owner = User::findOrFail($validated['owner_id']);
        $owner->update([
            'role' => 'shop_owner',
            'shop_id' => $shop->id
        ]);

        return redirect()->route('admin.shops.index')->with('success', 'Shop created successfully.');
    }

    /**
     * Toggle shop status (active/inactive)
     */
    public function toggleShopStatus(Shop $shop)
    {
        $shop->update([
            'is_active' => !$shop->is_active
        ]);

        $status = $shop->is_active ? 'activated' : 'deactivated';

        return response()->json([
            'success' => true,
            'message' => "Shop {$status} successfully."
        ]);
    }

    /**
     * Show all users for admin management
     */
    public function users()
    {
        // Eager-load shop to avoid N+1 when rendering shop info per user
        $users = User::with([
            'shop:id,name,email',
            'orders' => function($query) {
                $query->withoutGlobalScopes();
            }
        ])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        $shops = Shop::orderBy('name')->get();
        return view('admin.users.index', compact('users', 'shops'));
    }

    /**
     * Show suspended users list (Super Admin only)
     */
    public function suspendedUsers()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        // Get only suspended users with their shop information
        $users = User::with([
            'shop:id,name,email',
            'suspendedBy:id,name,email'
        ])
            ->where('is_suspended', true)
            ->orderBy('suspended_at', 'desc')
            ->paginate(20);

        $shops = Shop::orderBy('name')->get();

        return view('admin.users.suspended', compact('users', 'shops'));
    }

    /**
     * Show user profile for admin (uses admin layout)
     */
    public function showUser(User $boundUser)
    {
        // Re-query the user with full eager loading to avoid N+1 warnings from the detector
        $user = User::withoutGlobalScopes()
            ->with([
                'shop',
                'ownedShop',
                'orders' => function ($query) {
                    $query->withoutGlobalScopes()->latest()->limit(10);
                },
            ])
            ->withCount([
                // Precompute counts to avoid extra queries in the view
                'orders as total_orders_count' => function ($q) {
                    $q->withoutGlobalScopes();
                },
                'orders as pending_orders_count' => function ($q) {
                    $q->withoutGlobalScopes()->where('order_status', 0);
                },
                'orders as completed_orders_count' => function ($q) {
                    $q->withoutGlobalScopes()->where('order_status', 1);
                },
            ])
            ->withSum([
                // Optional: precompute total spent if column is `total` (adjust if different)
                'orders as total_spent_sum' => function ($q) {
                    $q->withoutGlobalScopes();
                },
            ], 'total')
            ->findOrFail($boundUser->getKey());

        // Map stats for the view (kept for backwards compatibility in blade)
        $stats = [
            'total_orders' => $user->total_orders_count ?? 0,
            'total_spent' => $user->total_spent_sum ?? 0,
            'pending_orders' => $user->pending_orders_count ?? 0,
            'completed_orders' => $user->completed_orders_count ?? 0,
        ];

        return view('admin.users.show', compact('user', 'stats'));
    }
    // ...existing code...

    /**
     * Show the form for creating a new user (admin panel)
     */
    public function createUser()
    {
        $shops = \App\Models\Shop::orderBy('name')->get();
        return view('admin.users.create', compact('shops'));
    }

    /**
     * Store a newly created user in storage (admin panel)
     */
    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'username' => 'required|string|max:255|unique:users,username',
            'role' => 'required|in:admin,shop_owner,manager,employee',
            'password' => 'required|string|min:6|confirmed',
            'shop_id' => 'nullable|exists:shops,id',
        ]);

        $user = new User();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->username = $validated['username'];
        $user->role = $validated['role'];
        $user->password = bcrypt($validated['password']);
        $user->email_verified_at = now();
        if (!empty($validated['shop_id'])) {
            $user->shop_id = $validated['shop_id'];
        }
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }


    public function showShop(Shop $shop)
    {
        // Load lightweight relations and counts to avoid loading very large collections
        $shop->load('owner');
        $shop->loadCount(['users', 'products', 'orders', 'customers']);

        // Get per-shop KPIs via KpiService for fast dashboard metrics
        $kpiService = new \App\Services\KpiService();
        $shopKpis = $kpiService->getOrderKpisByShop($shop->id);

        // Fetch small bounded collections for display
        $recentOrders = \App\Models\Order::where('shop_id', $shop->id)->with('customer')->latest()->limit(5)->get();
        $shopUsers = \App\Models\User::where('shop_id', $shop->id)->latest()->limit(10)->get(['id','name','email','role','created_at']);
        $recentProducts = \App\Models\Product::where('shop_id', $shop->id)->latest()->limit(10)->get();

        return view('admin.shops.show', compact('shop', 'shopKpis', 'recentOrders', 'shopUsers', 'recentProducts'));
    }

    public function editShop(Shop $shop)
    {
        $shop->load('owner');
        $availableOwners = User::whereIn('role', ['admin', 'shop_owner'])
            ->where(function ($query) use ($shop) {
                $query->whereDoesntHave('ownedShop')
                    ->orWhere('id', $shop->owner_id);
            })
            ->get(['id', 'name', 'email']);

        return view('admin.shops.edit', compact('shop', 'availableOwners'));
    }

    public function suspendShop(Request $request, Shop $shop)
    {
        $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        $shop->suspend($request->reason, Auth::user());

        return response()->json([
            'success' => true,
            'message' => 'Shop suspended successfully.'
        ]);
    }

    public function reactivateShop(Shop $shop)
    {
        $shop->reactivate();

        return response()->json([
            'success' => true,
            'message' => 'Shop reactivated successfully.'
        ]);
    }

    public function updateShopSettings(Request $request, Shop $shop)
    {
        $request->validate([
            'monthly_fee' => 'required|numeric|min:0',
            'grace_period_days' => 'required|integer|min:0|max:30'
        ]);

        $shop->update([
            'monthly_fee' => $request->monthly_fee,
            'grace_period_days' => $request->grace_period_days,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Shop settings updated successfully.'
        ]);
    }

    public function recordPayment(Request $request, Shop $shop)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'notes' => 'nullable|string|max:500',
            'extend_months' => 'required|integer|min:1|max:12'
        ]);

        // Record payment
        $paymentData = [
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'notes' => $request->notes,
            'recorded_by' => Auth::user()->name,
            'recorded_by_id' => Auth::id()
        ];

        $shop->addPaymentRecord($paymentData);

        // Extend subscription
        $shop->extendSubscription($request->extend_months);

        return response()->json([
            'success' => true,
            'message' => 'Payment recorded and subscription extended successfully.'
        ]);
    }

    public function toggleShopAccess(Shop $shop)
    {
        $shop->update([
            'is_active' => !$shop->is_active
        ]);

        $status = $shop->is_active ? 'enabled' : 'disabled';

        return response()->json([
            'success' => true,
            'message' => "Shop access {$status} successfully."
        ]);
    }

    public function getUsersByShop()
    {
        $users = User::with('shop')
            ->where('role', '!=', 'admin')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $shops = Shop::orderBy('name')->get();

        return view('admin.users.index', compact('users', 'shops'));
    }

    public function toggleUserAccess(User $user)
    {
        if ($user->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot disable admin users.'
            ], 403);
        }

        // Toggle user's shop access by updating their role
        $newRole = $user->role === 'employee' ? 'suspended' : 'employee';
        $user->update(['role' => $newRole]);

        $status = $newRole === 'employee' ? 'enabled' : 'disabled';

        return response()->json([
            'success' => true,
            'message' => "User access {$status} successfully."
        ]);
    }

    public function getShopUsers(Shop $shop)
    {
        $users = $shop->users()->with('shop')->get();

        return response()->json([
            'success' => true,
            'users' => $users->map(function($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                ];
            })
        ]);
    }

    public function getAvailableUsers()
    {
        // Get users who either have no shop assigned or are not shop owners
        $users = User::where('role', '!=', 'admin')
            ->where(function($query) {
                $query->whereNull('shop_id')
                      ->orWhere('role', '!=', 'shop_owner');
            })
            ->get(['id', 'name', 'email', 'role']);

        return response()->json([
            'success' => true,
            'users' => $users
        ]);
    }

    public function assignUserToShop(Request $request, Shop $shop)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|in:shop_owner,manager,employee',
            'make_owner' => 'boolean'
        ]);

        $user = User::findOrFail($request->user_id);

        // If making the user a shop owner, update ownership
        if ($request->make_owner || $request->role === 'shop_owner') {
            // Update current owner to manager if exists
            if ($shop->owner && $shop->owner->id !== $user->id) {
                $shop->owner->update([
                    'role' => 'manager',
                    'shop_id' => $shop->id
                ]);
            }

            // Update shop owner
            $shop->update(['owner_id' => $user->id]);

            $user->update([
                'role' => 'shop_owner',
                'shop_id' => $shop->id
            ]);
        } else {
            // Regular user assignment
            $user->update([
                'role' => $request->role,
                'shop_id' => $shop->id
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'User assigned to shop successfully.'
        ]);
    }

    public function removeUserFromShop(Request $request, Shop $shop, User $user)
    {
        if ($user->isShopOwner() && $shop->owner_id === $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot remove shop owner. Transfer ownership first.'
            ], 400);
        }

        $user->update([
            'shop_id' => null,
            'role' => 'employee' // Default role for unassigned users
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User removed from shop successfully.'
        ]);
    }

    /**
     * Verify user email manually (Super Admin only)
     */
    public function verifyUserEmail(User $user)
    {
        try {
            \Log::info('Email verification attempt', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'current_verification_status' => $user->email_verified_at,
                'authenticated_user' => auth()->user()->id ?? 'not authenticated'
            ]);

            if (!auth()->user()->isAdmin()) {
                \Log::warning('Unauthorized email verification attempt', [
                    'user_id' => $user->id,
                    'authenticated_user_role' => auth()->user()->role ?? 'unknown'
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized action.'
                ], 403);
            }

            if ($user->email_verified_at) {
                \Log::info('Email already verified', [
                    'user_id' => $user->id,
                    'verified_at' => $user->email_verified_at
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'User email is already verified.'
                ]);
            }

            $user->markEmailAsVerified();

            \Log::info('Email verification successful', [
                'user_id' => $user->id,
                'new_verification_status' => $user->fresh()->email_verified_at
            ]);

            return response()->json([
                'success' => true,
                'message' => 'User email verified successfully.'
            ]);
        } catch (\Exception $e) {
            \Log::error('Email verification error', [
                'user_id' => $user->id ?? 'unknown',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while verifying user email.'
            ], 500);
        }
    }

    /**
     * Unverify user email (Super Admin only)
     */
    public function unverifyUserEmail(User $user)
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action.'
            ], 403);
        }

        if (!$user->email_verified_at) {
            return response()->json([
                'success' => false,
                'message' => 'User email is already unverified.'
            ]);
        }

        $user->update(['email_verified_at' => null]);

        return response()->json([
            'success' => true,
            'message' => 'User email unverified successfully.'
        ]);
    }

    /**
     * Update user shop assignment (Admin only)
     */
    public function updateUserShopAssignment(Request $request, User $user)
    {
        if (!auth()->user()->isAdmin()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized action.'
                ], 403);
            }
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'shop_id' => 'nullable|exists:shops,id',
            'role' => 'required|in:admin,shop_owner,manager,employee',
            'confirm_transfer' => 'nullable|boolean'
        ]);

        // Don't allow changing super admin role/shop assignment
        if ($user->isAdmin() || $request->role === 'admin') {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot modify super admin assignments.'
                ], 403);
            }
            return redirect()->back()->with('error', 'Cannot modify super admin assignments.');
        }

        $oldRole = $user->role;
        $oldShopId = $user->shop_id;
        $newShopId = $request->shop_id;
        $newRole = $request->role;

        // If making user a shop owner
        if ($newRole === 'shop_owner' && $newShopId) {
            $targetShop = Shop::findOrFail($newShopId);

            // Check if shop already has owner and it's not the current user
            if ($targetShop->owner_id && $targetShop->owner_id !== $user->id) {
                // Require explicit confirmation for ownership transfer
                if (!$request->has('confirm_transfer') || !$request->confirm_transfer) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'This shop already has an owner. You must confirm the ownership transfer. Please check the confirmation dialog.');
                }

                // Demote current owner to manager
                $currentOwner = $targetShop->owner;
                if ($currentOwner) {
                    $currentOwner->update([
                        'role' => 'manager',
                        'shop_id' => $targetShop->id
                    ]);
                }
            }

            // Update shop ownership
            $targetShop->update(['owner_id' => $user->id]);
        }

        // If user was a shop owner and role is changing
        if ($oldRole === 'shop_owner' && $newRole !== 'shop_owner') {
            if ($user->ownedShop) {
                $user->ownedShop->update(['owner_id' => null]);
            }
        }

        // Update user
        $user->update([
            'shop_id' => $newShopId,
            'role' => $newRole
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'User shop assignment updated successfully.',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'role' => $user->role,
                    'shop_name' => $user->shop ? $user->shop->name : null
                ]
            ]);
        }

        return redirect()->route('admin.users.index')->with('success', 'User shop assignment updated successfully!');
    }

    /**
     * Send password reset email (Super Admin only)
     */
    public function sendPasswordResetToUser(User $user)
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action.'
            ], 403);
        }

        try {
            // Generate password reset token
            $token = app('auth.password.broker')->createToken($user);

            // Send password reset email
            $user->sendPasswordResetNotification($token);

            return response()->json([
                'success' => true,
                'message' => 'Password reset email sent successfully to ' . $user->email
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send password reset email: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Show edit form for user (Super Admin only)
     */
    public function editUser(User $user)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $shops = Shop::orderBy('name')->get();
        return view('admin.users.edit', compact('user', 'shops'));
    }

    /**
     * Show assign shop page (Super Admin only)
     */
    public function assignShopPage(User $user)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $shops = Shop::orderBy('name')->get();
        return view('admin.users.assign-shop', compact('user', 'shops'));
    }

    /**
     * Update user (Super Admin only)
     */
    public function updateUser(Request $request, User $user)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        // Prevent modification of super admin accounts
        if ($user->isAdmin()) {
            return redirect()->back()->with('error', 'Cannot modify super admin accounts.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'role' => 'required|in:shop_owner,manager,employee',
            'shop_id' => 'nullable|exists:shops,id',
        ]);

        $oldRole = $user->role;
        $oldShopId = $user->shop_id;
        $newShopId = $validated['shop_id'] ?? null;
        $newRole = $validated['role'];

        // If making user a shop owner
        if ($newRole === 'shop_owner' && $newShopId) {
            $targetShop = Shop::findOrFail($newShopId);

            // Check if shop already has owner
            if ($targetShop->owner_id && $targetShop->owner_id !== $user->id) {
                // Demote current owner to manager
                $currentOwner = $targetShop->owner;
                if ($currentOwner) {
                    $currentOwner->update([
                        'role' => 'manager',
                        'shop_id' => $targetShop->id
                    ]);
                }
            }

            // Update shop ownership
            $targetShop->update(['owner_id' => $user->id]);
        }

        // If user was a shop owner and role is changing
        if ($oldRole === 'shop_owner' && $newRole !== 'shop_owner') {
            if ($user->ownedShop) {
                $user->ownedShop->update(['owner_id' => null]);
            }
        }

        // Update user
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'username' => $validated['username'],
            'role' => $newRole,
            'shop_id' => $newShopId
        ]);

        return redirect()->route('admin.users.index')
                        ->with('success', 'User updated successfully.');
    }

    /**
     * Update shop (Super Admin only)
     */
    public function updateShop(Request $request, Shop $shop)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'email' => 'required|email',
            'owner_id' => 'required|exists:users,id',
            'monthly_fee' => 'nullable|numeric|min:0',
            'subscription_status' => 'nullable|in:active,suspended,expired',
            'subscription_end_date' => 'nullable|date',
        ]);

        // If owner is changing
        if ($validated['owner_id'] != $shop->owner_id) {
            // Update old owner role if exists
            if ($shop->owner) {
                $shop->owner->update([
                    'role' => 'manager'
                ]);
            }

            // Update new owner role
            $newOwner = User::findOrFail($validated['owner_id']);
            $newOwner->update([
                'role' => 'shop_owner',
                'shop_id' => $shop->id
            ]);
        }

        $shop->update($validated);

        return redirect()->route('admin.shops.index')
                        ->with('success', 'Shop updated successfully.');
    }

    /**
     * Show delete user page
     */
    public function deleteUserPage(User $user)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        // Prevent deletion of super admin accounts
        if ($user->isAdmin()) {
            return redirect()->back()->with('error', 'Cannot delete super admin accounts.');
        }

        // Prevent deletion of currently authenticated user
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }

        $shops = Shop::orderBy('name')->get();

        // Get related users if this is a shop owner
        $relatedUsers = collect();
        $shopToDelete = null;
        if ($user->role === 'shop_owner' && $user->ownedShop) {
            $shopToDelete = $user->ownedShop;
            // Get all users (managers, employees) assigned to this shop
            $relatedUsers = User::where('shop_id', $shopToDelete->id)
                                ->where('id', '!=', $user->id)
                                ->get();
        }

        return view('admin.users.delete', compact('user', 'shops', 'relatedUsers', 'shopToDelete'));
    }

    /**
     * Delete user (Super Admin only)
     */
    public function deleteUser(Request $request, User $user)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        // Prevent deletion of super admin accounts
        if ($user->isAdmin()) {
            return redirect()->back()->with('error', 'Cannot delete super admin accounts.');
        }

        // Prevent deletion of currently authenticated user
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }

        // Validate confirmation
        $request->validate([
            'confirm_delete' => 'required|in:DELETE',
            'confirm_understand' => 'required|accepted',
        ], [
            'confirm_delete.in' => 'You must type DELETE (all caps) to confirm deletion.',
            'confirm_understand.accepted' => 'You must confirm that you understand this action is permanent.',
        ]);

        try {
            $userName = $user->name;
            $deletedCount = 0;
            $deletedShop = null;

            // If user is a shop owner, delete all related users and the shop
            if ($user->role === 'shop_owner' && $user->ownedShop) {
                $shop = $user->ownedShop;
                $deletedShop = $shop->name;

                // Get all users assigned to this shop (managers, employees)
                $relatedUsers = User::where('shop_id', $shop->id)
                                    ->where('id', '!=', $user->id)
                                    ->get();

                // Delete sessions for all related users
                foreach ($relatedUsers as $relatedUser) {
                    \DB::table('sessions')->where('user_id', $relatedUser->id)->delete();
                    $relatedUser->delete();
                    $deletedCount++;
                }

                // Delete the shop
                $shop->delete();
            }

            // Delete user's sessions
            \DB::table('sessions')->where('user_id', $user->id)->delete();

            // Delete the user
            $user->delete();

            // Build success message
            $message = "User '{$userName}' has been deleted successfully.";
            if ($deletedCount > 0) {
                $message .= " Additionally, {$deletedCount} related user(s) were deleted.";
            }
            if ($deletedShop) {
                $message .= " Shop '{$deletedShop}' has been removed from the system.";
            }

            return redirect()->route('admin.users.index')
                           ->with('success', $message);
        } catch (\Exception $e) {
            \Log::error('Failed to delete user: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'exception' => $e
            ]);
            return redirect()->back()->with('error', 'Failed to delete user: ' . $e->getMessage());
        }
    }

    /**
     * Show suspend user page
     */
    public function suspendUserPage(User $user)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $shops = Shop::orderBy('name')->get();
        return view('admin.users.suspend', compact('user', 'shops'));
    }

    /**
     * Suspend user
     */
    public function suspendUser(Request $request, User $user)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        // Prevent suspending super admin
        if ($user->isAdmin()) {
            return redirect()->back()->with('error', 'Cannot suspend super admin accounts.');
        }

        $request->validate([
            'suspension_reason' => 'required|string',
            'suspension_type' => 'required|in:days,months,lifetime,until_payment,manual',
            'suspension_duration' => 'required_if:suspension_type,days,months|nullable|integer|min:1'
        ]);

        $suspensionEndsAt = null;

        // Calculate suspension end date based on type
        if ($request->suspension_type === 'days') {
            $suspensionEndsAt = now()->addDays($request->suspension_duration);
        } elseif ($request->suspension_type === 'months') {
            $suspensionEndsAt = now()->addMonths($request->suspension_duration);
        }

        // Update user suspension status
        $user->update([
            'is_suspended' => true,
            'suspension_reason' => $request->suspension_reason,
            'suspension_type' => $request->suspension_type,
            'suspension_duration' => $request->suspension_duration,
            'suspended_at' => now(),
            'suspension_ends_at' => $suspensionEndsAt,
            'suspended_by' => auth()->id()
        ]);

        // Log out the user from all devices
        \DB::table('sessions')->where('user_id', $user->id)->delete();

        return redirect()->route('admin.users.index')
                       ->with('success', "User '{$user->name}' has been suspended successfully.");
    }

    /**
     * Unsuspend user
     */
    public function unsuspendUser(User $user)
    {
        try {
            if (!auth()->user()->isAdmin()) {
                return back()->with('error', 'Unauthorized action.');
            }

            $user->is_suspended = false;
            $user->suspension_reason = null;
            $user->suspension_type = null;
            $user->suspension_duration = null;
            $user->suspended_at = null;
            $user->suspension_ends_at = null;
            $user->suspended_by = null;
            $user->save();

            return redirect()->route('admin.users.index')
                           ->with('success', "User '{$user->name}' suspension has been lifted.");
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while unsuspending user: ' . $e->getMessage());
        }
    }

    /**
     * Show user reports
     */
    public function userReports()
    {
        // Get user statistics
        $stats = [
            'total_users' => User::count(),
            'admin_users' => User::where('role', 'admin')->count(),
            'shop_owner_users' => User::where('role', 'shop_owner')->count(),
            'manager_users' => User::where('role', 'manager')->count(),
            'employee_users' => User::where('role', 'employee')->count(),
        ];

        // User distribution by role
        $totalUsers = User::count();
        $usersByRole = User::selectRaw('role, COUNT(*) as total')
            ->groupBy('role')
            ->get()
            ->map(function ($item) use ($totalUsers) {
                $item->percentage = $totalUsers > 0 ? ($item->total / $totalUsers) * 100 : 0;
                $item->active_count = $item->total; // All users are considered active since there's no is_active column
                $item->role_color = match($item->role) {
                    'admin' => 'red',
                    'shop_owner' => 'blue',
                    'manager' => 'green',
                    'employee' => 'yellow',
                    default => 'secondary'
                };
                return $item;
            });

        // Recent users
        $recentUsers = User::with('shop:id,name')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.reports.users', compact('stats', 'usersByRole', 'recentUsers'));
    }

    /**
     * Show shop reports
     */
    public function shopReports()
    {
        // Get shop statistics
        $stats = [
            'total_shops' => Shop::count(),
            'active_shops' => Shop::where('subscription_status', 'active')->where('is_active', true)->count(),
            'suspended_shops' => Shop::where('subscription_status', 'suspended')->count(),
            'overdue_shops' => Shop::where('subscription_end_date', '<', now())
                ->where('subscription_status', '!=', 'suspended')
                ->count(),
            'active_subscription_value' => Shop::where('subscription_status', 'active')
                ->where('is_active', true)
                ->sum('monthly_fee'),
            'avg_subscription' => Shop::where('subscription_status', 'active')
                ->where('is_active', true)
                ->avg('monthly_fee') ?? 0,
        ];

        // Shop distribution by status
        $totalShops = Shop::count();
        $shopsByStatus = Shop::selectRaw('subscription_status as status, COUNT(*) as total')
            ->groupBy('subscription_status')
            ->get()
            ->map(function ($item) use ($totalShops) {
                $item->percentage = $totalShops > 0 ? ($item->total / $totalShops) * 100 : 0;
                return $item;
            });

        // Top performing shops
        $topShops = Shop::with(['owner:id,name'])
            ->withCount(['users', 'products'])
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Recent shops
        $recentShops = Shop::with('owner:id,name')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.reports.shops', compact('stats', 'shopsByStatus', 'topShops', 'recentShops'));
    }

    /**
     * Display all shops with their subscription details
     */
    public function subscriptions()
    {
        $shops = Shop::with('owner:id,name')
            ->withCount('users')
            ->orderBy('subscription_end_date', 'asc')
            ->paginate(20);

        return view('admin.shops.subscriptions', compact('shops'));
    }

    /**
     * Display only suspended shops
     */
    public function suspendedShops()
    {
        $shops = Shop::where('is_suspended', true)
            ->with(['owner:id,name', 'suspendedBy:id,name'])
            ->withCount('users')
            ->orderBy('suspended_at', 'desc')
            ->paginate(20);

        return view('admin.shops.suspended', compact('shops'));
    }

    /**
     * Extend shop subscription
     */
    public function extendSubscription(Request $request, Shop $shop)
    {
        $request->validate([
            'extend_days' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:500'
        ]);

        $currentEndDate = $shop->subscription_end_date ?? now();
        $newEndDate = \Carbon\Carbon::parse($currentEndDate)->addDays($request->extend_days);

        $shop->update([
            'subscription_end_date' => $newEndDate,
            'subscription_status' => 'active'
        ]);

        return redirect()
            ->route('admin.shops.subscriptions')
            ->with('success', "Subscription for {$shop->name} extended by {$request->extend_days} days until {$newEndDate->format('M d, Y')}");
    }

    /**
     * Change shop subscription status
     */
    public function changeSubscriptionStatus(Request $request, Shop $shop)
    {
        $request->validate([
            'status' => 'required|in:trial,active,expired,cancelled',
            'reason' => 'nullable|string|max:500'
        ]);

        $shop->update([
            'subscription_status' => $request->status
        ]);

        return redirect()
            ->route('admin.shops.subscriptions')
            ->with('success', "Subscription status for {$shop->name} changed to {$request->status}");
    }

    /**
     * Unsuspend a shop
     */
    public function unsuspendShop(Request $request, Shop $shop)
    {
        $request->validate([
            'unsuspend_users' => 'nullable|boolean',
            'notes' => 'nullable|string|max:500'
        ]);

        $shop->update([
            'is_suspended' => false,
            'suspended_at' => null,
            'suspended_by' => null,
            'suspension_reason' => null
        ]);

        // Unsuspend all shop users if requested
        if ($request->unsuspend_users) {
            User::where('shop_id', $shop->id)
                ->where('is_suspended', true)
                ->update([
                    'is_suspended' => false,
                    'suspended_at' => null,
                    'suspended_by' => null,
                    'suspension_reason' => null
                ]);
        }

        $message = $request->unsuspend_users
            ? "Shop {$shop->name} and all its users have been unsuspended"
            : "Shop {$shop->name} has been unsuspended";

        return redirect()
            ->route('admin.shops.suspended')
            ->with('success', $message);
    }
}
