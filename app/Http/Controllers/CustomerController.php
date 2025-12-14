<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Http\Requests\Customer\StoreCustomerRequest;
use App\Http\Requests\Customer\UpdateCustomerRequest;

class CustomerController extends Controller
{
    public function index()
    {
        // Paginate customers and compute total purchases via DB aggregate
        $customers = Customer::withCount('orders')->latest()->paginate(20);

        $totalPurchasesCents = \App\Models\Order::sum('total');

        // New customers counts
        $newToday = Customer::whereDate('created_at', '>=', today())->count();
        $newThisMonth = Customer::whereDate('created_at', '>=', now()->startOfMonth())->count();

        return view('customers.index', [
            'customers' => $customers,
            'total_purchases_cents' => $totalPurchasesCents,
            'new_today_count' => $newToday,
            'new_this_month_count' => $newThisMonth,
        ]);
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(StoreCustomerRequest $request)
    {
        $customer = Customer::create($request->all());

        /**
         * Handle upload an image
         */
        if($request->hasFile('photo'))
        {
            $file = $request->file('photo');
            $filename = hexdec(uniqid()).'.'.$file->getClientOriginalExtension();

            $file->storeAs('customers/', $filename, 'public');
            $customer->update([
                'photo' => $filename
            ]);
        }

        // Check if this is an AJAX request
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'New customer has been created!',
                'customer' => [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'email' => $customer->email,
                    'phone' => $customer->phone,
                    'address' => $customer->address,
                ]
            ]);
        }

        return redirect()
            ->route('customers.index')
            ->with('success', 'New customer has been created!');
    }

    public function show(Customer $customer)
    {
        // Ensure orders are loaded for the single customer instance
        $customer->loadMissing('orders');

        return view('customers.show', [
            'customer' => $customer
        ]);
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', [
            'customer' => $customer
        ]);
    }

    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        //
        $customer->update($request->except('photo'));

        if($request->hasFile('photo')){

            // Delete Old Photo
            if($customer->photo){
                unlink(public_path('storage/customers/') . $customer->photo);
            }

            // Prepare New Photo
            $file = $request->file('photo');
            $fileName = hexdec(uniqid()).'.'.$file->getClientOriginalExtension();

            // Store an image to Storage
            $file->storeAs('customers/', $fileName, 'public');

            // Save DB
            $customer->update([
                'photo' => $fileName
            ]);
        }

        return redirect()
            ->route('customers.index')
            ->with('success', 'Customer has been updated!');
    }

    public function updateAjax(UpdateCustomerRequest $request, Customer $customer)
    {
        try {
            // Update customer details (without photo for AJAX)
            $customer->update($request->except('photo'));

            return response()->json([
                'success' => true,
                'message' => 'Customer details updated successfully!',
                'customer' => [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'email' => $customer->email,
                    'phone' => $customer->phone,
                    'address' => $customer->address,
                    'account_holder' => $customer->account_holder,
                    'account_number' => $customer->account_number,
                    'bank_name' => $customer->bank_name,
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to update customer', [
                'customer_id' => $customer->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update customer details'
            ], 500);
        }
    }

    public function destroy(Customer $customer)
    {
        if($customer->photo)
        {
            unlink(public_path('storage/customers/') . $customer->photo);
        }

        $customer->delete();

        return redirect()
            ->back()
            ->with('success', 'Customer has been deleted!');
    }
}
