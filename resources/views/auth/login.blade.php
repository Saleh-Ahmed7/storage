


    @include('layouts.head')


<x-guest-layout>

    <!-- Session Status -->
    @if (session('status'))
        <div class="alert alert-success mb-3">
            {{ session('status') }}
        </div>
    @endif
    
    
    <div class="container mt-5">
        <h1 class="mb-4 title-1">تسجيل الدخول</h1>

        <form method="POST" action="{{ route('login') }}">
            @csrf
    
                <!-- Email Address -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        class="form-control" @error('email') is-invalid @enderror" 
                        value="{{ old('email') }}" 
                        required 
                        autofocus
                    >
                    @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
        
                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password" 
                        class="form-control @error('password') is-invalid @enderror" 
                        required
                    >
                    @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
        
                <!-- Remember Me -->
                <div class="form-check mb-3">
                    <input 
                        class="form-check-input" 
                        type="checkbox" 
                        id="remember_me" 
                        name="remember"
                    >
                    <label class="form-check-label" for="remember_me">
                        Remember me
                    </label>
                </div>
        
                <!-- Submit -->
                <div class="d-flex justify-content-center">
                    <button type="submit" class="btn btn-primary px-5">
                        Log in
                    </button>
                </div>
            </form>
            </div>
    </div>
     

</x-guest-layout>
