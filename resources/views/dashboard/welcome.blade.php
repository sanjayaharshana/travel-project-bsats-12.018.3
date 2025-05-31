<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <img src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=0D8ABC&color=fff' }}"
                             alt="{{ Auth::user()->name }}"
                             class="rounded-circle"
                             width="64" height="64"
                             style="object-fit: cover;">
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h4 class="mb-1">Welcome back, {{ Auth::user()->name }}! 👋</h4>
                        <p class="text-muted mb-0">Here's what's happening with your trips today.</p>
                    </div>
                    <div class="flex-shrink-0">
                        <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary">
                            <i class="bi bi-gear me-2"></i>Settings
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
