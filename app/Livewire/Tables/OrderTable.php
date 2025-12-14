<?php

namespace App\Livewire\Tables;

use App\Models\Order;
use App\Models\Customer;
use Livewire\Component;
use Livewire\WithPagination;

class OrderTable extends Component
{
    use WithPagination;

    public $perPage = 10;

    public $search = '';

    public $sortField = 'invoice_no';

    public $sortAsc = false;

    // Filter properties
    public $filterCustomer = '';
    public $filterDateFrom = '';
    public $filterDateTo = '';
    public $filterMonth = '';
    public $filterYear = '';

    public function sortBy($field): void
    {
        if ($this->sortField === $field) {
            $this->sortAsc = ! $this->sortAsc;

        } else {
            $this->sortAsc = true;
        }

        $this->sortField = $field;
    }

    public function resetFilters(): void
    {
        $this->filterCustomer = '';
        $this->filterDateFrom = '';
        $this->filterDateTo = '';
        $this->filterMonth = '';
        $this->filterYear = '';
        $this->search = '';
    }

    public function render()
    {
        $query = Order::query()->with(['customer', 'details']);

        // Apply shop scoping for non-super admin users
        $user = auth()->user();
        if ($user && !$user->isSuperAdmin()) {
            $activeShop = $user->getActiveShop();
            if ($activeShop) {
                $query->where('shop_id', $activeShop->id);
            }
        }

        // Search filter - invoice number, customer name, phone number
        if ($this->search) {
            $query->where(function($q) {
                $q->where('invoice_no', 'like', "%{$this->search}%")
                  ->orWhereHas('customer', function($customerQuery) {
                      $customerQuery->where('name', 'like', "%{$this->search}%")
                                   ->orWhere('phone', 'like', "%{$this->search}%");
                  });
            });
        }

        // Customer filter
        if ($this->filterCustomer) {
            $query->where('customer_id', $this->filterCustomer);
        }

        // Date range filter
        if ($this->filterDateFrom) {
            $query->whereDate('order_date', '>=', $this->filterDateFrom);
        }
        if ($this->filterDateTo) {
            $query->whereDate('order_date', '<=', $this->filterDateTo);
        }

        // Month filter
        if ($this->filterMonth) {
            $query->whereMonth('order_date', $this->filterMonth);
        }

        // Year filter
        if ($this->filterYear) {
            $query->whereYear('order_date', $this->filterYear);
        }

        // Get customers for dropdown (scoped to shop)
        $customersQuery = Customer::query();
        if ($user && !$user->isSuperAdmin()) {
            $activeShop = $user->getActiveShop();
            if ($activeShop) {
                $customersQuery->where('shop_id', $activeShop->id);
            }
        }
        $customers = $customersQuery->orderBy('name')->get();

        return view('livewire.tables.order-table', [
            'orders' => $query
                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate($this->perPage),
            'customers' => $customers,
        ]);
    }
}
