<x-guest-layout>

    <!-- Estado de sesión -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input
                id="email"
                class="block mt-1 w-full"
                type="email"
                name="email"
                :value="old('email')"
                required
                autofocus
                autocomplete="username"
            />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Contraseña -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input
                id="password"
                class="block mt-1 w-full"
                type="password"
                name="password"
                required
                autocomplete="current-password"
            />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Recuérdame -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input
                    id="remember_me"
                    type="checkbox"
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                    name="remember"
                >
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <!-- Olvidé contraseña + Botón login -->
        <div class="flex items-center justify-end mt-6">
            @if (Route::has('password.request'))
                <a
                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md
                           focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    href="{{ route('password.request') }}"
                >
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>

    </form>

    <!-- Separador -->
    <div class="flex items-center my-6">
        <div class="flex-1 border-t border-gray-200"></div>
        <span class="mx-4 text-sm text-gray-400">¿No tienes cuenta?</span>
        <div class="flex-1 border-t border-gray-200"></div>
    </div>

    <!-- Botón Registrarse -->
    @if (Route::has('register'))
        <a
            href="{{ route('register') }}"
            class="block w-full text-center py-2 px-4 border border-indigo-600
                   text-indigo-600 text-sm font-semibold rounded-md
                   hover:bg-indigo-50 transition-colors duration-150
                   focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        >
            Crear una cuenta
        </a>
    @endif

</x-guest-layout>