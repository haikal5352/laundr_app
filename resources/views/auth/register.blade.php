<x-guest-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-slate-50">
        <div
            class="w-full sm:max-w-md mt-6 px-8 py-10 bg-white shadow-xl overflow-hidden sm:rounded-[2rem] border border-slate-100 relative reveal">

            <!-- Decor -->
            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-blue-500 to-cyan-400"></div>

            <div class="text-center mb-10">
                <a href="/" class="inline-flex items-center gap-2 group">
                    <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center text-white font-black text-2xl shadow-lg shadow-blue-200 group-hover:scale-110 transition-transform duration-300"
                        style="background-color: #2563eb;">
                        U9
                    </div>
                    <div class="text-left">
                        <h1 class="text-2xl font-black text-slate-800 leading-none">Laundry</h1>
                        <p class="text-[0.65rem] font-bold text-blue-500 tracking-[0.2em] uppercase">U9</p>
                    </div>
                </a>
                <h2 class="mt-6 text-xl font-bold text-slate-800">Buat Akun Baru 🚀</h2>
                <p class="text-slate-400 text-sm mt-2">Gabung sekarang untuk kemudahan laundry.</p>
            </div>

            <!-- Validation Errors -->
            <x-auth-validation-errors class="mb-4" :errors="$errors" />

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap</label>
                    <input id="name"
                        class="block w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 font-medium focus:border-blue-500 focus:bg-white focus:ring-blue-500 transition-colors"
                        type="text" name="name" :value="old('name')" required autofocus
                        placeholder="Nama Lengkap Anda" />
                </div>

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-sm font-bold text-slate-700 mb-2">Email Address</label>
                    <input id="email"
                        class="block w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 font-medium focus:border-blue-500 focus:bg-white focus:ring-blue-500 transition-colors"
                        type="email" name="email" :value="old('email')" required placeholder="nama@email.com" />
                </div>

                <!-- Password with Toggle -->
                <div x-data="{ show: false }">
                    <label for="password" class="block text-sm font-bold text-slate-700 mb-2">Password</label>
                    <div class="relative">
                        <input id="password"
                            class="block w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 font-medium focus:border-blue-500 focus:bg-white focus:ring-blue-500 transition-colors pr-12"
                            :type="show ? 'text' : 'password'" name="password" required autocomplete="new-password"
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

                <!-- Confirm Password with Toggle -->
                <div x-data="{ show: false }">
                    <label for="password_confirmation" class="block text-sm font-bold text-slate-700 mb-2">Konfirmasi
                        Password</label>
                    <div class="relative">
                        <input id="password_confirmation"
                            class="block w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 font-medium focus:border-blue-500 focus:bg-white focus:ring-blue-500 transition-colors pr-12"
                            :type="show ? 'text' : 'password'" name="password_confirmation" required
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

                <div class="pt-2">
                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 px-4 rounded-xl shadow-lg shadow-blue-200 transition-all duration-300 transform hover:-translate-y-1 block"
                        style="background-color: #2563eb; color: white;">
                        {{ __('Daftar Sekarang') }}
                    </button>
                </div>

                <div class="text-center mt-6">
                    <p class="text-sm text-slate-500 font-medium">
                        Sudah punya akun?
                        <a href="{{ route('login') }}"
                            class="text-blue-600 hover:text-blue-700 font-bold ml-1 transition-colors">
                            Masuk Disini
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>