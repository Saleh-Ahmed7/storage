
    <div class="p-3 ">

        <!-- Logo -->
        <!-- <a class="navbar-brand" href="{{ route('dashboard') }}">
            <img src="/logo.png" height="30">
        </a> -->

        <!-- Hamburger button -->
        
        <!-- Menu -->
       

            <!-- Right Side / User Dropdown -->
            
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                    <div class=" text-start   ">
                        <button type="submit" class="btn btn-danger mx-5">
                            {{ __('Log Out') }}
                        </button>
                    </div>        
                    </form>

                    {{-- <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown"
                       role="button" data-bs-toggle="dropdown" aria-expanded="false">

                        <span class="me-1 text-white">{{ Auth::user()->name }}</span>
                    </a> --}}

                  
                   

        </div>
    </div>
