<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <label for="name" class="form-label">{{ __('Name') }}</label>
        <input id="name"
               name="name"
               type="text"
               class="form-control @error('name') is-invalid @enderror"
               value="{{ old('name', $type->name ?? '') }}"
               placeholder="e.g. Repair, Warranty"
               aria-describedby="name-help"
               required />
        <p id="name-help" class="text-xs text-gray-500 mt-1">Short, descriptive name shown in job dropdowns.</p>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div>
        <label for="default_days" class="form-label">{{ __('Default Days') }}</label>
        <input id="default_days"
               name="default_days"
               type="number"
               min="0"
               class="form-control @error('default_days') is-invalid @enderror"
               value="{{ old('default_days', $type->default_days ?? '') }}"
               placeholder="e.g. 3">
        <p class="text-xs text-gray-500 mt-1">Optional default number of days to prefill when creating a job of this type.</p>
        @error('default_days')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="md:col-span-2">
        <label for="description" class="form-label">{{ __('Description') }}</label>
        <textarea id="description"
                  name="description"
                  rows="4"
                  class="form-control @error('description') is-invalid @enderror"
                  placeholder="Optional: more details about what this job type covers (max 255 chars)">
            {{ old('description', $type->description ?? '') }}
        </textarea>
        @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
