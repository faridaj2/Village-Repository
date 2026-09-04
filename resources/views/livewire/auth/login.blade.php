<div>
    <!-- Mobile Logo -->
    <div class="lg:hidden flex items-center gap-3 mb-10">
        <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
        </div>
        <span class="text-xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">SIDESA</span>
    </div>

    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Masuk</h2>
        <p class="text-sm text-gray-500">Masukkan email dan password untuk melanjutkan</p>
    </div>

    @if (session('status'))
        <div class="mb-4 p-3 bg-emerald-50 text-emerald-700 text-sm rounded-xl">{{ session('status') }}</div>
    @endif

    <form wire:submit.prevent="login" class="space-y-5">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
            <input wire:model.defer="email" type="email" required autofocus autocomplete="username"
                   class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                   placeholder="email@desa.id">
            @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
            <input wire:model.defer="password" type="password" required autocomplete="current-password"
                   class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                   placeholder="••••••••">
            @error('password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 cursor-pointer">
                <input wire:model="remember" type="checkbox" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                <span class="text-sm text-gray-600">Ingat saya</span>
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">Lupa password?</a>
            @endif
        </div>

        <button type="submit" class="w-full py-3 px-4 bg-gradient-to-r from-indigo-500 to-purple-600 text-white text-sm font-semibold rounded-xl hover:from-indigo-600 hover:to-purple-700 shadow-lg shadow-indigo-500/25 transition-all">
            Masuk
        </button>
    </form>

    @if (Route::has('register') && \App\Models\AdminSetting::get('enable_registration', true))
        <p class="mt-6 text-center text-sm text-gray-500">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">Daftar</a>
        </p>
    @endif

    <p class="mt-8 text-center text-xs text-gray-400">
        <a href="/" class="hover:text-gray-600 transition-colors">&larr; Kembali ke beranda</a>
    </p>
</div>
