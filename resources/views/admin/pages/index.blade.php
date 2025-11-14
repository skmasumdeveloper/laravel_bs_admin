<x-layouts.app :title="__('Pages')">
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <p class="text-uppercase small text-muted mb-1">{{ __('Content management') }}</p>
                <h1 class="h3 mb-0">{{ __('Pages') }}</h1>
            </div>
            <a href="{{ route('admin.pages.create') }}" class="btn btn-primary">
                {{ __('Create Page') }}
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card shadow-sm border">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>{{ __('ID') }}</th>
                            <th>{{ __('Title') }}</th>
                            <th>{{ __('Slug') }}</th>
                            <th>{{ __('Published') }}</th>
                            <th class="text-end">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pages as $page)
                            <tr>
                                <td>{{ $page->id }}</td>
                                <td>{{ $page->title }}</td>
                                <td>{{ $page->slug }}</td>
                                <td>
                                    <span class="badge rounded-pill {{ $page->is_published ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-muted' }}">
                                        {{ $page->is_published ? __('Published') : __('Draft') }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('admin.pages.edit', $page) }}" class="btn btn-outline-secondary">{{ __('Edit') }}</a>
                                        <form action="{{ route('admin.pages.destroy', $page) }}" method="POST" onsubmit="return confirm('{{ __('Delete this page?') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-outline-danger" type="submit">{{ __('Delete') }}</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">{{ __('No pages yet.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-3">
            {{ $pages->links() }}
        </div>
    </div>
</x-layouts.app>
