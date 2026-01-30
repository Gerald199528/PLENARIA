<?php
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new #[Layout('livewire.layout.client.client')] class extends Component {
    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = false;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->ensureIsNotRateLimited();

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
        Session::regenerate();

        if (Auth::user()->hasRole(['admin', 'Super Admin', 'Doctor', 'Recepcionista', 'Administrador'])) {
            $this->redirectIntended(default: route('admin.dashboard', absolute: false), navigate: false);
        } else if (Auth::user()->hasRole(['Paciente', 'user'])) {
            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: false);
        }
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }   
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }    
    }; 
    ?>
            <div class="flex flex-col gap-12">
                                    <!-- Icono Plenaria -->
     <!-- Icono Plenaria -->
@php
$faviconPath = \App\Models\Setting::get('logo_icon'); 
$faviconUrl = $faviconPath ? asset('storage/' . $faviconPath) : asset('default-favicon.ico');

// Logo para el login
$logoPath = \App\Models\Setting::get('logo_horizontal_background_solid');
$logoUrl = $logoPath ? asset('storage/' . $logoPath) : null;
@endphp
<link rel="icon" type="image/x-icon" href="{{ $faviconUrl }}">
<link rel="shortcut icon" type="image/x-icon" href="{{ $faviconUrl }}">  

        <section class="relative w-full h-screen overflow-hidden bg-gradient-to-r from-blue-400 via-blue-600 to-indigo-600 animate-gradientBackground">
            <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0 relative z-10">          
                    <!-- Logo Responsive -->
        <a href="{{ route('home') }}" class="flex items-center mb-6 gap-2 sm:gap-3 group">
            @if($logoUrl)
                <!-- Logo desde BD - Responsive -->
                <div class="w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24 lg:w-28 lg:h-28 flex items-center justify-center rounded shadow-lg animate-bounce overflow-hidden" style="animation-duration: 5s;">
                    <img src="{{ $logoUrl }}" alt="Logo" class="w-full h-full object-contain">
                </div>
            @endif
            
            <span class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold tracking-wider text-white group-hover:text-gray-100 transition-all duration-300 -ml-1 sm:-ml-2 md:-ml-3 lg:-ml-4">
                LENARIA
            </span>
        </a>
        <!-- Formulario -->
        <div class="w-full bg-white/90 dark:bg-gray-800/90 rounded-3xl shadow-2xl backdrop-blur-md md:mt-0 sm:max-w-md xl:p-0 transform transition-transform duration-700 ease-out animate-fadeIn scale-95 hover:scale-100">
            <div class="p-6 space-y-6 md:space-y-8 sm:p-8">
                <h1 class="text-2xl text-center font-bold leading-tight tracking-tight text-gray-900 dark:text-white">
                    Inicia sesión en tu cuenta
                </h1>
                <form wire:submit="login" class="space-y-4 md:space-y-6">
                    <!-- Email -->
                    <div>
                        <x-input label="Correo" id="email" type="email" wire:model="email" required
                            placeholder="nombre@dominio.com"
                            class="focus:ring-2 focus:ring-blue-500 focus:shadow-lg transition-all duration-300"/>
                    </div>

                    <!-- Password -->
                    <div>
                        <x-password label="Contraseña" id="password" type="password" wire:model="password" required
                            placeholder="••••••••"
                            class="focus:ring-2 focus:ring-blue-500 focus:shadow-lg transition-all duration-300"/>
                    </div>

                    <!-- Recordarme -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <x-checkbox id="remember" aria-describedby="remember" type="checkbox" wire:model="remember" class="transition duration-300"/>
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="remember" class="text-gray-500 dark:text-gray-300">Recordarme</label>
                            </div>
                        </div>
                    </div>
                    <!-- Botón Login -->
                    <x-button info type="submit" class="w-full bg-gradient-to-r from-blue-500 via-blue-600 to-indigo-600 text-white font-bold px-6 py-2 rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl active:scale-95 animate-pulse" 
                        label="Iniciar sesión" icon="arrow-right-end-on-rectangle" spinner/>
                </form>
            </div>
            <!-- Theme Switcher -->
            <div class="flex justify-center items-center mb-4">
                <livewire:components.teme-switcher />
            </div>
        </div>
    </div>
</section>
</div>

@push('styles')
<style>
    /* Gradiente animado */
    @keyframes gradientBackground {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    .animate-gradientBackground {
        background-size: 200% 200%;
        animation: gradientBackground 15s ease infinite;
    }

    /* Animación de fadeIn */
    @keyframes fadeIn {
        0% { opacity: 0; transform: translateY(20px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    .animate-fadeIn {
        animation: fadeIn 1s ease forwards;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-8px); }
    }
    .animate-float {
        animation: float 3s ease-in-out infinite;
    }
</style>
@endpush

