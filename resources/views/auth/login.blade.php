@extends('layouts.app')

@section('content')
<!-- Full Width Background Section -->
<div class="position-relative min-vh-100 d-flex align-items-center" style="background: url('https://wallpapershome.com/images/pages/pic_h/11926.jpg') center/cover no-repeat;">
    <!-- Dark Overlay -->

    <!-- Main Content -->
    <div class="container-fluid px-0">
        <div class="row justify-content-center g-0">
            <div class="col-lg-10">
                <div class="card border-0 shadow-lg overflow-hidden">
                    <div class="row g-0">
                        <!-- Left Column - Hero Section -->
                        <div class="col-lg-6 position-relative">
                            <div class="h-100 bg-gradient-to-br from-primary to-primary-dark text-white p-5">
                                <div class="position-relative h-100 d-flex flex-column">
                                    <!-- Logo/Brand -->
                                    <div class="text-center mb-5">
                                        <img src="/images/logo-light.png" alt="Logo" class="mb-4" style="height: 50px;">
                                        <h1 class="display-6 fw-bold mb-3">Welcome Back!</h1>
                                        <p class="lead opacity-75">Sign in to continue your travel journey</p>
                                    </div>

                                    <!-- Feature Highlights -->
                                    <div class="mb-5">
                                        <div class="d-flex align-items-center mb-4">
                                            <div class="feature-icon bg-white bg-opacity-10 rounded-circle p-3 me-3">
                                                <i class="bi bi-shield-check text-white fs-4"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1">Secure Login</h6>
                                                <p class="small opacity-75 mb-0">Your data is always protected</p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center mb-4">
                                            <div class="feature-icon bg-white bg-opacity-10 rounded-circle p-3 me-3">
                                                <i class="bi bi-lightning-charge text-white fs-4"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1">Fast Access</h6>
                                                <p class="small opacity-75 mb-0">Quick and easy sign in</p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <div class="feature-icon bg-white bg-opacity-10 rounded-circle p-3 me-3">
                                                <i class="bi bi-headset text-white fs-4"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1">24/7 Support</h6>
                                                <p class="small opacity-75 mb-0">Always here to help you</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Social Proof -->
                                    <div class="mt-auto">
                                        <div class="row g-3 text-center">
                                            <div class="col-4">
                                                <div class="bg-white bg-opacity-10 rounded-3 p-3">
                                                    <div class="h4 mb-0 fw-bold">10K+</div>
                                                    <small class="opacity-75">Active Users</small>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="bg-white bg-opacity-10 rounded-3 p-3">
                                                    <div class="h4 mb-0 fw-bold">50K+</div>
                                                    <small class="opacity-75">Trips Planned</small>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="bg-white bg-opacity-10 rounded-3 p-3">
                                                    <div class="h4 mb-0 fw-bold">4.9/5</div>
                                                    <small class="opacity-75">User Rating</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column - Login Form -->
                        <div class="col-lg-6 p-5">
                            <div class="px-lg-4">
                                <form method="POST" action="{{ route('login') }}">
                                    @csrf

                                    <!-- Email -->
                                    <div class="mb-4">
                                        <label for="email" class="form-label">Email Address</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="bi bi-envelope"></i>
                                            </span>
                                            <input type="email" class="form-control border-start-0" id="email" name="email" value="{{ old('email') }}" required autofocus>
                                        </div>
                                        @error('email')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Password -->
                                    <div class="mb-4">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <label for="password" class="form-label">Password</label>
                                            @if (Route::has('password.request'))
                                                <a href="{{ route('password.request') }}" class="text-primary small">Forgot Password?</a>
                                            @endif
                                        </div>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="bi bi-lock"></i>
                                            </span>
                                            <input type="password" class="form-control border-start-0" id="password" name="password" required>
                                        </div>
                                        @error('password')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Remember Me -->
                                    <div class="mb-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                            <label class="form-check-label" for="remember">
                                                Remember me
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Submit Button -->
                                    <button type="submit" class="btn btn-primary w-100 py-2 mb-4">
                                        Sign In
                                    </button>

                                    <!-- Register Link -->
                                    <div class="text-center">
                                        <p class="mb-0">Don't have an account? <a href="{{ route('register') }}" class="text-primary">Create Account</a></p>
                                    </div>
                                </form>

                                <!-- Social Login -->
                                <div class="mt-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <hr class="flex-grow-1">
                                        <span class="mx-3 text-muted">Or continue with</span>
                                        <hr class="flex-grow-1">
                                    </div>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <button type="button" class="btn btn-outline-secondary w-100">
                                                <i class="bi bi-google me-2"></i>Google
                                            </button>
                                        </div>
                                        <div class="col-6">
                                            <button type="button" class="btn btn-outline-secondary w-100">
                                                <i class="bi bi-facebook me-2"></i>Facebook
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.min-vh-100 {
    min-height: 100vh;
}
.container-fluid {
    max-width: 100%;
    padding-left: 0;
    padding-right: 0;
}
.from-primary {
    background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
}
.to-primary-dark {
    background: linear-gradient(135deg, #0a58ca 0%, #084298 100%);
}
.bg-gradient-to-br {
    background: linear-gradient(135deg, #0d6efd 0%, #084298 100%);
}
.feature-icon {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
@endsection
