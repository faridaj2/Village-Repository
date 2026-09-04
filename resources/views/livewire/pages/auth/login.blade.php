<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    @if (session('status'))
        <div class="mb-4 p-3 bg-emerald-50 text-emerald-700 text-sm rounded-xl">{{ session('status') }}</div>
    @endif

    <form class="space-y-5">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
            <input wire:model="form.email" type="email" required autofocus autocomplete="username"
                   class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                   placeholder="email@desa.id">
            @error('form.email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
            <input wire:model="form.password" type="password" required autocomplete="current-password"
                   class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                   placeholder="••••••••">
            @error('form.password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 cursor-pointer">
                <input wire:model="form.remember" type="checkbox" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                <span class="text-sm text-gray-600">Ingat saya</span>
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">Lupa password?</a>
            @endif
        </div>

        <button wire:click="login" class="w-full py-3 px-4 bg-gradient-to-r from-indigo-500 to-purple-600 text-white text-sm font-semibold rounded-xl hover:from-indigo-600 hover:to-purple-700 shadow-lg shadow-indigo-500/25 transition-all">
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
