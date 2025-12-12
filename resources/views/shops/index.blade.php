@extends('layouts.nexora')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    @if(Auth::user()->isAdmin())
                        <h4>All Shops in System</h4>
                        <div>
                            <span class="badge bg-info">{{ safe_count($shops) }} Total Shops</span>
                        </div>
                    @elseif(Auth::user()->isShopOwner())
                        <h4>My Shop</h4>
                        @if($shops->isEmpty())
                            <a href="{{ route('shops.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Create Shop
                            </a>
                        @endif
                    @else
                        <h4>Shop Information</h4>
                    @endif
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(safe_count($shops) > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Address</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        @if(auth()->user()->role !== 'shop_owner')
                                            <th>Owner</th>
                                        @endif
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($shops as $shop)
                                        <tr>
                                            <td>
                                                <strong>{{ $shop->name }}</strong>
                                                @if(auth()->user()->getActiveShop()?->id === $shop->id)
                                                    <span class="badge bg-success ms-2">Active</span>
                                                @endif
                                            </td>
                                            <td>{{ $shop->address }}</td>
                                            <td>{{ $shop->phone }}</td>
                                            <td>{{ $shop->email }}</td>
                                            @if(auth()->user()->role !== 'shop_owner')
                                                <td>{{ $shop->owner->name }}</td>
                                            @endif
                                            <td>{{ $shop->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.shops.show', $shop) }}"
                                                       class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye"></i> View
                                                    </a>

                                                    @if(auth()->user()->role === 'shop_owner' && $shop->owner_id === auth()->id())
                                                        <a href="{{ route('admin.shops.edit', $shop) }}"
                                                           class="btn btn-sm btn-warning">
                                                            <i class="fas fa-edit"></i> Edit
                                                        </a>
                                                    @endif

                                                    @if(auth()->user()->isInShop($shop->id) && auth()->user()->getActiveShop()?->id !== $shop->id)
                                                        <a href="{{ route('shops.switch', $shop) }}"
                                                           class="btn btn-sm btn-secondary">
                                                            <i class="fas fa-exchange-alt"></i> Switch
                                                        </a>
                                                    @endif

                                                    @if((auth()->user()->role === 'shop_owner' && $shop->owner_id === auth()->id()) || auth()->user()->role === 'admin')
                                                        <form action="{{ route('shops.destroy', $shop) }}"
                                                              method="POST"
                                                              class="d-inline"
                                                              onsubmit="return confirm('Are you sure you want to delete this shop? This action cannot be undone.')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger">
                                                                <i class="fas fa-trash"></i> Delete
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-store fa-3x text-muted mb-3"></i>
                            <h5>No Shops Found</h5>
                            <p class="text-muted">
                                @if(auth()->user()->role === 'shop_owner')
                                    You haven't created a shop yet. Click the "Create Shop" button to get started.
                                @else
                                    No shops have been created yet.
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
