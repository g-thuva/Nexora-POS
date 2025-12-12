<div class="row row-cards">
    <div class="col-md-6">
        {{-- New customer fields - we no longer allow selecting existing customers here --}}
        <div class="mb-3">
            <label for="new_customer_name" class="form-label">{{ __('Customer Name') }}</label>
            <input type="text" name="new_customer_name" id="new_customer_name" class="form-control" value="{{ old('new_customer_name', optional($job->customer ?? null)->name) }}" required>
            @error('new_customer_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="new_customer_phone" class="form-label">{{ __('Phone') }}</label>
            <input type="text" name="new_customer_phone" id="new_customer_phone" class="form-control" value="{{ old('new_customer_phone', optional($job->customer ?? null)->phone) }}">
            @error('new_customer_phone')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="new_customer_address" class="form-label">{{ __('Address') }}</label>
            <textarea name="new_customer_address" id="new_customer_address" rows="3" class="form-control">{{ old('new_customer_address', optional($job->customer ?? null)->address) }}</textarea>
            @error('new_customer_address')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Job Type dropdown (user-managed) --}}
        <div class="mb-3">
            <label for="job_type_id" class="form-label">{{ __('Job Type') }}</label>
            <select name="job_type_id" id="job_type_id" class="form-select">
                <option value="">-- {{ __('Select job type') }} --</option>
                    @foreach(\App\Models\JobType::all() as $type)
                        <option value="{{ $type->id }}" data-default="{{ $type->default_days ?? '' }}" @if(old('job_type_id', $job->job_type_id ?? '') == $type->id) selected @endif>
                            {{ $type->name }}
                        </option>
                    @endforeach
            </select>
            <p class="text-sm text-gray-500">Manage job types in Settings → Job Types.</p>
        </div>

        <div class="mb-3">
            <label for="estimated_duration" class="form-label">{{ __('Estimated Duration (Days)') }}</label>
            <input type="number" name="estimated_duration" id="estimated_duration" class="form-control" min="0" value="{{ old('estimated_duration', $job->estimated_duration ?? '') }}">
            @error('estimated_duration')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-12">
        <div class="mb-3">
            <label for="description" class="form-label">{{ __('Description') }}</label>
            <textarea name="description" id="description" rows="6" class="form-control">{{ old('description', $job->description ?? '') }}</textarea>
        </div>
    </div>

    <div class="col-md-6">
        <div class="mb-3">
            <label for="status" class="form-label">{{ __('Status') }}</label>
            <select name="status" id="status" class="form-select">
                @foreach(\App\Models\Job::statuses() as $status)
                    <option value="{{ $status }}" @if(old('status', $job->status ?? '') == $status) selected @endif>
                        {{ ucfirst(str_replace('_', ' ', $status)) }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>

{{-- No extra page scripts required for customer selection anymore --}}

@push('page-scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const typeSelect = document.getElementById('job_type_id');
            const estInput = document.getElementById('estimated_duration');

            if (!typeSelect || !estInput) return;

            typeSelect.addEventListener('change', function() {
                const opt = typeSelect.options[typeSelect.selectedIndex];
                const def = opt ? opt.dataset.default : null;

                // Only autofill if estimated duration is empty
                if ((estInput.value === null || estInput.value === '' ) && def !== undefined && def !== '') {
                    estInput.value = def;
                }
            });
        });
    </script>
@endpush
