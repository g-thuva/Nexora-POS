<?php

namespace App\Livewire\Tables;

use App\Models\Product;
use App\Models\Category;
use App\Models\Unit;
use Livewire\Component;
use Livewire\WithPagination;

class ProductTable extends Component
{
    use WithPagination;

    protected $listeners = ['refreshProducts' => '$refresh'];

    public $perPage = 10;
    public function loadMore()
    {
        $this->perPage += 10;
    }

    public $search = '';

    public $sortField = 'created_at';

    public $sortAsc = false;

    public $categoryFilter = '';

    public $unitFilter = '';

    public $stockFilter = 'all'; // all, in_stock, low_stock, out_of_stock

    protected $queryString = [
        'search' => ['except' => ''],
        'categoryFilter' => ['except' => ''],
        'unitFilter' => ['except' => ''],
        'stockFilter' => ['except' => 'all'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter()
    {
        $this->resetPage();
    }

    public function updatingUnitFilter()
    {
        $this->resetPage();
    }

    public function updatingStockFilter()
    {
        $this->resetPage();
    }

    public function sortBy($field): void
    {
        if ($this->sortField === $field) {
            $this->sortAsc = ! $this->sortAsc;
        } else {
            $this->sortAsc = true;
        }

        $this->sortField = $field;
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->categoryFilter = '';
        $this->unitFilter = '';
        $this->stockFilter = 'all';
        $this->resetPage();
    }

    public function render()
    {
        $query = Product::query()
            ->with(['category', 'unit', 'warranty']);

        // Apply search
        if ($this->search) {
            $query->search($this->search);
        }

        // Apply category filter
        if ($this->categoryFilter) {
            $query->where('category_id', $this->categoryFilter);
        }

        // Apply unit filter
        if ($this->unitFilter) {
            $query->where('unit_id', $this->unitFilter);
        }

        // Apply stock filter
        switch ($this->stockFilter) {
            case 'in_stock':
                $query->whereRaw('quantity > quantity_alert');
                break;
            case 'low_stock':
                $query->whereRaw('quantity <= quantity_alert AND quantity > 0');
                break;
            case 'out_of_stock':
                $query->where('quantity', '<=', 0);
                break;
        }

        $products = $query
            ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
            ->paginate($this->perPage);

        return view('livewire.tables.product-table', [
            'products' => $products,
            'categories' => Category::all(['id', 'name']),
            'units' => Unit::all(['id', 'name']),
        ]);
    }
}
