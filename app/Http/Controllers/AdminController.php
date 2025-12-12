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

    public function extendSubscription(Request $request, Shop $shop)
    {
        $request->validate([
            'months' => 'required|integer|min:1|max:12'
        ]);

        $shop->extendSubscription($request->months);

        return response()->json([
            'success' => true,
            'message' => "Subscription extended by {$request->months} month(s)."
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
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action.'
            ], 403);
        }

        $request->validate([
            'shop_id' => 'nullable|exists:shops,id',
            'role' => 'required|in:admin,shop_owner,manager,employee'
        ]);

        // Don't allow changing super admin role/shop assignment
        if ($user->isAdmin() || $request->role === 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot modify super admin assignments.'
            ], 403);
        }

        $oldRole = $user->role;
        $oldShopId = $user->shop_id;
        $newShopId = $request->shop_id;
        $newRole = $request->role;

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
            'shop_id' => $newShopId,
            'role' => $newRole
        ]);

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

    /**
     * Send password reset email (Super Admin only)
     */
    public function sendPasswordReset(User $user)
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
     * Delete user (Super Admin only)
     */
    public function deleteUser(User $user)
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

        try {
            // If user is a shop owner, remove ownership
            if ($user->role === 'shop_owner' && $user->ownedShop) {
                $user->ownedShop->update(['owner_id' => null]);
            }

            // Delete the user
            $userName = $user->name;
            $user->delete();

            return redirect()->route('admin.users.index')
                           ->with('success', "User '{$userName}' has been deleted successfully.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete user: ' . $e->getMessage());
        }
    }
}
