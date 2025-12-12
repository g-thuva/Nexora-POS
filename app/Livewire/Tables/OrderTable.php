<?php

namespace App\Livewire\Tables;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class OrderTable extends Component
{
    use WithPagination;

    public $perPage = 10;

    public $search = '';

    public $sortField = 'invoice_no';

    public $sortAsc = false;

    public function sortBy($field): void
    {
        if ($this->sortField === $field) {
            $this->sortAsc = ! $this->sortAsc;

        } else {
            $this->sortAsc = true;
        }

        $this->sortField = $field;
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
        
        return view('livewire.tables.order-table', [
            'orders' => $query
                ->search($this->search)
                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate($this->perPage),
        ]);
    }
}
