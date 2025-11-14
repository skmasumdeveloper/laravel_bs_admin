<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h5 mb-0">Categories</h1>
            <p class="small text-muted mb-0">Create and organize categories and sub-categories.</p>
        </div>

        <div>
            <button wire:click="openCreate" class="btn btn-primary">New Category</button>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        <div class="col-lg-5 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="card-title">{{ $editingId ? __('Edit Category') : __('Create Category') }}</h6>

                    <form wire:submit.prevent="{{ $editingId ? 'updateCategory' : 'createCategory' }}">
                        <div class="mb-3">
                            <label class="form-label">{{ __('Name') }}</label>
                            <input wire:model.defer="name" class="form-control" />
                            @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('Parent') }}</label>
                            <select wire:model="parent_id" class="form-select">
                                <option value="">— {{ __('None') }} —</option>
                                @foreach ($allCategories as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                            @error('parent_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">{{ $editingId ? __('Save') : __('Create') }}</button>
                            <button type="button" wire:click.prevent="resetForm" class="btn btn-outline-secondary">{{ __('Reset') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-7 mb-3">
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>{{ __('Category') }}</th>
                                    <th class="text-end">&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($categories as $cat)
                                    <tr>
                                        <td>
                                            <div class="fw-semibold">{{ $cat->name }}</div>
                                            @if($cat->children->isNotEmpty())
                                                <div class="small text-muted">
                                                    @foreach($cat->children as $child)
                                                        <span class="badge bg-light border me-1">{{ $child->name }}</span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <button wire:click.prevent="openEdit({{ $cat->id }})" class="btn btn-sm btn-link">{{ __('Edit') }}</button>
                                            <button wire:click.prevent="deleteCategory({{ $cat->id }})" class="btn btn-sm btn-link text-danger">{{ __('Delete') }}</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
