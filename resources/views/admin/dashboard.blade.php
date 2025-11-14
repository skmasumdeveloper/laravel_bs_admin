<x-layouts.app :title="__('Dashboard')">
    <div class="container-fluid py-3">
        <!-- Analytics Cards Row -->
        <div class="row g-4">
            <div class="col-12 col-md-6 col-lg-3">
                <div class="card h-100 rounded-3 border">
                    <div class="card-body text-center">
                        <i class="bi bi-people-fill text-primary fs-1 mb-2"></i>
                        <h3 class="mb-0">{{ $analytics['total_users'] }}</h3>
                        <p class="text-muted mb-0">Total Users</p>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-3">
                <div class="card h-100 rounded-3 border">
                    <div class="card-body text-center">
                        <i class="bi bi-file-text-fill text-success fs-1 mb-2"></i>
                        <h3 class="mb-0">{{ $analytics['total_blogs'] }}</h3>
                        <p class="text-muted mb-0">Total Blogs</p>
                        <small class="text-success">{{ $analytics['published_blogs'] }} published</small>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-3">
                <div class="card h-100 rounded-3 border">
                    <div class="card-body text-center">
                        <i class="bi bi-folder-fill text-warning fs-1 mb-2"></i>
                        <h3 class="mb-0">{{ $analytics['total_categories'] }}</h3>
                        <p class="text-muted mb-0">Categories</p>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-3">
                <div class="card h-100 rounded-3 border">
                    <div class="card-body text-center">
                        <i class="bi bi-file-earmark-fill text-info fs-1 mb-2"></i>
                        <h3 class="mb-0">{{ $analytics['total_pages'] }}</h3>
                        <p class="text-muted mb-0">Total Pages</p>
                        <small class="text-info">{{ $analytics['published_pages'] }} published</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Information Row -->
        <div class="row g-4 mt-3">
            <div class="col-12 col-lg-6">
                <div class="card rounded-3 border">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0">Recent Users</h5>
                    </div>
                    <div class="card-body">
                        @if($analytics['recent_users']->count() > 0)
                            <ul class="list-group list-group-flush">
                                @foreach($analytics['recent_users'] as $user)
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <div>
                                            <strong>{{ $user->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $user->email }}</small>
                                        </div>
                                        <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted mb-0">No users found.</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="card rounded-3 border">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0">System Statistics</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span>Total Roles</span>
                            <span class="badge bg-primary">{{ $analytics['total_roles'] }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span>Published Blogs</span>
                            <span class="badge bg-success">{{ $analytics['published_blogs'] }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span>Published Pages</span>
                            <span class="badge bg-info">{{ $analytics['published_pages'] }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Draft Blogs</span>
                            <span class="badge bg-secondary">{{ $analytics['total_blogs'] - $analytics['published_blogs'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
