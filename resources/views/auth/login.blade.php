<x-guest-layout>
    <div
        class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-slate-50 relative overflow-hidden">

        <!-- Background Pattern -->
        <div class="absolute inset-0 z-0">
            <div
                class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-blue-400/20 rounded-full blur-[100px] animate-float">
            </div>
            <div
                class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-indigo-400/20 rounded-full blur-[100px] animate-float-delayed">
            </div>
            <div
                class="absolute top-[20%] right-[20%] w-[20%] h-[20%] bg-cyan-400/20 rounded-full blur-[80px] animate-float">
            </div>
        </div>

        <div
            class="w-full sm:max-w-md mt-6 px-8 py-10 bg-white/80 backdrop-blur-xl shadow-2xl overflow-hidden sm:rounded-[2.5rem] border border-white/50 relative z-10 transition-all duration-300 hover:shadow-blue-200/50">

            <!-- Decor -->
            <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-blue-600 via-indigo-500 to-cyan-400">
            </div>

            <div class="text-center mb-10">
                <a href="/" class="inline-flex items-center gap-3 group">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center text-white font-black text-2xl shadow-lg shadow-blue-500/30 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300">
                        U9
                    </div>
                    <div class="text-left">
                        <h1 class="text-3xl font-black text-slate-800 leading-none tracking-tight">Laundry</h1>
                        <p class="text-[0.7rem] font-bold text-blue-600 tracking-[0.3em] uppercase">U9</p>
                    </div>
                </a>
                <h2 class="mt-8 text-2xl font-bold text-slate-800">Selamat Datang! 👋</h2>
                <p class="text-slate-500 text-sm mt-3 font-medium">Masuk untuk mengelola pesanan laundry Anda.</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <!-- Validation Errors -->
            <x-auth-validation-errors class="mb-4" :errors="$errors" />

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-sm font-bold text-slate-700 mb-2 pl-1">Email Address</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400 group-focus-within:text-blue-500 transition-colors"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                            </svg>
                        </div>
                        <input id="email"
                            class="block w-full rounded-2xl border-slate-200 bg-slate-50/50 pl-12 pr-4 py-4 text-slate-900 font-medium focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100 transition-all duration-300"
                            type="email" name="email" :value="old('email')" required autofocus
                            placeholder="nama@email.com" />
                    </div>
                </div>

                <!-- Password with Toggle -->
                <div x-data="{ show: false }">
                    <div class="flex justify-between items-center mb-2 pl-1">
                        <label for="password" class="block text-sm font-bold text-slate-700">Password</label>
                        @if (Route::has('password.request'))
                            <a class="text-xs font-bold text-blue-600 hover:text-blue-700 transition-colors"
                                href="{{ route('password.request') }}">
                                Lupa Password?
                            </a>
                        @endif
                    </div>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400 group-focus-within:text-blue-500 transition-colors"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input id="password"
                            class="block w-full rounded-2xl border-slate-200 bg-slate-50/50 pl-12 pr-12 py-4 text-slate-900 font-medium focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100 transition-all duration-300"
                            :type="show ? 'text' : 'password'" name="password" required autocomplete="current-password"
                            placeholder="••••••••" />
                        <button type="button" @click="show = !show"
                            class="absolute inset-y-0 right-0 px-4 flex items-center text-slate-400 hover:text-blue-600 transition-colors focus:outline-none"
                            tabindex="-1">
                            <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.059 10.059 0 013.999-5.125m3.75-2.125C9.643 4.542 10.812 4.49 12 5c4.478 0 8.268 2.943 9.542 7a10.058 10.058 0 01-3.75 4.966m-1.05 1.05L4.25 4.25" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.88 9.88l4.24 4.24" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="block pl-1">
                    <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                        <input id="remember_me" type="checkbox"
                            class="rounded-md border-slate-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition-colors group-hover:border-blue-400"
                            name="remember">
                        <span
                            class="ml-2 text-sm text-slate-600 font-bold group-hover:text-blue-600 transition-colors">{{ __('Ingat Saya') }}</span>
                    </label>
                </div>

                <div class="pt-2">
                    <button type="submit"
                        class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-black text-lg py-4 px-4 rounded-2xl shadow-xl shadow-blue-200 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-2xl flex items-center justify-center gap-2 group">
                        {{ __('Masuk Sekarang') }}
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 group-hover:translate-x-1 transition-transform" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>

                <div class="text-center mt-8">
                    <p class="text-sm text-slate-500 font-medium">
                        Belum punya akun?
                        <a href="{{ route('register') }}"
                            class="text-blue-600 hover:text-indigo-600 font-black ml-1 transition-colors underline decoration-2 decoration-transparent hover:decoration-blue-600">
                            Daftar Sekarang
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>