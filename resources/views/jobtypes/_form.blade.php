<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="name" class="form-label required">
                {{ __('Name') }}
                <span class="text-danger">*</span>
            </label>
            <input id="name"
                   name="name"
                   type="text"
                   class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name', $type->name ?? '') }}"
                   placeholder="e.g. Repair, Warranty, Maintenance"
                   required
                   autofocus />
            <small class="form-hint">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0"/><path d="M12 8h.01"/><path d="M11 12h1v4h1"/>
                </svg>
                Short, descriptive name shown in job dropdowns
            </small>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="mb-3">
            <label for="default_days" class="form-label">
                {{ __('Default Duration (Days)') }}
            </label>
            <input id="default_days"
                   name="default_days"
                   type="number"
                   min="0"
                   max="365"
                   class="form-control @error('default_days') is-invalid @enderror"
                   value="{{ old('default_days', $type->default_days ?? '') }}"
                   placeholder="e.g. 3, 7, 14">
            <small class="form-hint">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M12 8l0 4l2 2"/><path d="M3.05 11a9 9 0 1 1 .5 4m-.5 5v-5h5"/>
                </svg>
                Optional: Pre-fill typical duration when creating jobs
            </small>
            @error('default_days')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-12">
        <div class="mb-3">
            <label for="description" class="form-label">
                {{ __('Description') }}
            </label>
            <textarea id="description"
                      name="description"
                      rows="4"
                      maxlength="255"
                      class="form-control @error('description') is-invalid @enderror"
                      placeholder="Describe what this job type covers and when to use it...">{{ old('description', $type->description ?? '') }}</textarea>
            <small class="form-hint">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M14 3v4a1 1 0 0 0 1 1h4"/><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"/><path d="M9 9l1 0"/><path d="M9 13l6 0"/><path d="M9 17l6 0"/>
                </svg>
                Help your team understand when to use this job type (max 255 characters)
            </small>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>
