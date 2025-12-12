<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h3 class="card-title mb-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24"
                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                        <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                    </svg>
                    {{ __('System Users') }}
                </h3>
                <div class="text-muted mt-1">Manage all users across the system</div>
            </div>
            <div class="text-muted">
                Total: {{ $users->total() }} users
            </div>
        </div>
    </div>

    <div class="card-body border-bottom py-3">
        <div class="d-flex">
            <div class="text-secondary">
                Show
                <div class="mx-2 d-inline-block">
                    <select wire:model.live="perPage" class="form-select form-select-sm" aria-label="result per page">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="15">15</option>
                        <option value="25">25</option>
                    </select>
                </div>
                entries
            </div>
            <div class="ms-auto text-secondary">
                Search:
                <div class="ms-2 d-inline-block">
                    <input type="text" wire:model.live="search" class="form-control form-control-sm" aria-label="Search users" placeholder="Search by name, email...">
                </div>
            </div>
        </div>
    </div>

    <x-spinner.loading-spinner/>

    <div class="table-responsive">
        <table wire:loading.remove class="table table-bordered card-table table-vcenter text-nowrap datatable">
            <thead class="thead-light">
            <tr>
                <th class="align-middle text-center w-1">
                    {{ __('No.') }}
                </th>
                <th scope="col" class="align-middle">
                    <a wire:click.prevent="sortBy('name')" href="#" role="button" class="text-decoration-none">
                        {{ __('User Name') }}
                        @include('inclues._sort-icon', ['field' => 'name'])
                    </a>
                </th>
                <th scope="col" class="align-middle">
                    <a wire:click.prevent="sortBy('email')" href="#" role="button" class="text-decoration-none">
                        {{ __('Email Address') }}
                        @include('inclues._sort-icon', ['field' => 'email'])
                    </a>
                </th>
                <th scope="col" class="align-middle text-center">
                    <a wire:click.prevent="sortBy('role')" href="#" role="button" class="text-decoration-none">
                        {{ __('Role') }}
                        @include('inclues._sort-icon', ['field' => 'role'])
                    </a>
                </th>
                <th scope="col" class="align-middle text-center">
                    {{ __('Shop Name') }}
                </th>
                <th scope="col" class="align-middle text-center">
                    {{ __('Status') }}
                </th>
                <th scope="col" class="align-middle text-center">
                    {{ __('Actions') }}
                </th>
            </tr>
            </thead>
            <tbody>
            @forelse ($users as $user)
                <tr>
                    <td class="align-middle text-center">
                        {{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}
                    </td>
                    <td class="align-middle">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-sm me-3" style="background-color: {{ '#' . substr(md5($user->name), 0, 6) }};">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                            <div>
                                <div class="fw-medium">{{ $user->name }}</div>
                                <div class="text-muted small">ID: {{ $user->id }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="align-middle">
                        <div>
                            {{ $user->email }}
                        </div>
                        @if($user->email_verified_at)
                            <div class="text-success small">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-xs" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M5 12l5 5l10 -10"/>
                                </svg>
                                Verified
                            </div>
                        @else
                            <div class="text-warning small">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-xs" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M12 9v2m0 4v.01"/>
                                    <path d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75"/>
                                </svg>
                                Unverified
                            </div>
                        @endif
                    </td>
                    <td class="align-middle text-center">
                        <span class="badge bg-{{ $user->role === 'super_admin' ? 'danger' : ($user->role === 'shop_owner' ? 'success' : ($user->role === 'manager' ? 'info' : 'secondary')) }}">
                            {{ $user->getRoleDisplayName() }}
                        </span>
                    </td>
                    <td class="align-middle text-center">
                        @if($user->shop)
                            <div class="fw-medium">{{ $user->shop->name }}</div>
                            <div class="text-muted small">{{ $user->shop->slug }}</div>
                        @elseif($user->role === 'super_admin')
                            <span class="text-muted">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-xs me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M12 3a12 12 0 0 0 8.5 3a12 12 0 0 1 -8.5 15a12 12 0 0 1 -8.5 -15a12 12 0 0 0 8.5 -3"/>
                                    <path d="M9 12l2 2l4 -4"/>
                                </svg>
                                System Admin
                            </span>
                        @else
                            <span class="text-warning">No Shop Assigned</span>
                        @endif
                    </td>
                    <td class="align-middle text-center">
                        @if($user->email_verified_at && $user->shop)
                            <span class="badge bg-success">Active</span>
                        @elseif(!$user->email_verified_at)
                            <span class="badge bg-warning">Pending</span>
                        @elseif(!$user->shop && $user->role !== 'super_admin')
                            <span class="badge bg-secondary">Inactive</span>
                        @else
                            <span class="badge bg-success">Active</span>
                        @endif
                    </td>
                    <td class="align-middle text-center" style="width: 10%">
                        <div class="btn-group" role="group">
                            <x-button.show class="btn-icon btn-sm" route="{{ route('users.show', $user) }}"/>
                            <x-button.edit class="btn-icon btn-sm" route="{{ route('users.edit', $user) }}"/>
                            @if(!$user->isSuperAdmin())
                                <x-button.delete class="btn-icon btn-sm" route="{{ route('users.destroy', $user) }}"
                                               confirmMessage="Are you sure you want to delete user '{{ $user->name }}'? This will permanently remove their account and cannot be undone."/>
                            @else
                                <button type="button" class="btn btn-icon btn-sm btn-secondary" disabled title="System administrators cannot be deleted">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M12 3a12 12 0 0 0 8.5 3a12 12 0 0 1 -8.5 15a12 12 0 0 1 -8.5 -15a12 12 0 0 0 8.5 -3"/>
                                        <path d="M9 12l2 2l4 -4"/>
                                    </svg>
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td class="align-middle text-center" colspan="7">
                        <div class="text-muted py-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg mb-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <circle cx="12" cy="12" r="9"/>
                                <line x1="9" y1="9" x2="9.01" y2="9"/>
                                <line x1="15" y1="9" x2="15.01" y2="9"/>
                                <path d="M8 13a4 4 0 1 0 8 0m0 0H8"/>
                            </svg>
                            <div>No users found</div>
                            <div class="small">Try adjusting your search criteria</div>
                        </div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer d-flex align-items-center">
        <p class="m-0 text-secondary d-none d-sm-block">
            Showing <span class="fw-medium">{{ $users->firstItem() }}</span> to <span class="fw-medium">{{ $users->lastItem() }}</span> of <span class="fw-medium">{{ $users->total() }}</span> entries
        </p>

        <ul class="pagination m-0 ms-auto">
            {{ $users->links() }}
        </ul>
    </div>
</div>
