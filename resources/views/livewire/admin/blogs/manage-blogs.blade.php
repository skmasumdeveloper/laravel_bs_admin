<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h5 mb-0">Blog Posts</h1>
            <p class="small text-muted mb-0">Create and manage blog posts.</p>
        </div>

        <div>
            <button wire:click="openCreate" class="btn btn-primary">New Blog Post</button>
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
                    <h6 class="card-title">{{ $editingId ? __('Edit Blog Post') : __('Create Blog Post') }}</h6>

                    <form wire:submit.prevent="{{ $editingId ? 'updateBlog' : 'createBlog' }}">
                        <div class="mb-3">
                            <label class="form-label">{{ __('Title') }}</label>
                            <input wire:model.defer="title" class="form-control" />
                            @error('title') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('Content') }}</label>
                            <textarea wire:model.defer="content" class="form-control" rows="5"></textarea>
                            @error('content') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('Category') }}</label>
                            <select wire:model="category_id" class="form-select">
                                <option value="">— {{ __('None') }} —</option>
                                @foreach ($categories as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('Status') }}</label>
                            <select wire:model="status" class="form-select">
                                <option value="draft">{{ __('Draft') }}</option>
                                <option value="published">{{ __('Published') }}</option>
                            </select>
                            @error('status') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
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
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('Category') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th class="text-end">&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($blogs as $blog)
                                    <tr>
                                        <td>
                                            <div class="fw-semibold">{{ $blog->title }}</div>
                                            @if($blog->content)
                                                <div class="small text-muted">{{ Str::limit($blog->content, 50) }}</div>
                                            @endif
                                        </td>
                                        <td>
                                            @if($blog->category)
                                                <span class="badge bg-light border">{{ $blog->category->name }}</span>
                                            @else
                                                <span class="text-muted small">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($blog->status === 'published')
                                                <span class="badge bg-success">{{ __('Published') }}</span>
                                            @else
                                                <span class="badge bg-secondary">{{ __('Draft') }}</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <button wire:click.prevent="openEdit({{ $blog->id }})" class="btn btn-sm btn-link">{{ __('Edit') }}</button>
                                            <button wire:click.prevent="deleteBlog({{ $blog->id }})" class="btn btn-sm btn-link text-danger">{{ __('Delete') }}</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3">{{ __('No blog posts found.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($blogs->hasPages())
                    <div class="card-footer">
                        {{ $blogs->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

