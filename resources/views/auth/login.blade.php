@extends('layouts.app')

@section('content')
<div class="flex min-h-screen items-center justify-center bg-slate-100 py-12">
    <div class="w-full max-w-md rounded-2xl border border-slate-200 bg-white p-8 shadow-xl">
        <h1 class="text-2xl font-semibold text-slate-900">Sign in</h1>
        <p class="mt-1 text-sm text-slate-500">Access your Stock & Expense workspace.</p>

        <form method="POST" action="{{ route('login.store') }}" class="mt-8 space-y-6">
            @csrf

            <div>
                <label for="email" class="text-sm font-semibold text-slate-600">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus class="mt-1 w-full rounded-lg border-slate-200 px-3 py-2 text-sm focus:border-sky-400 focus:ring-sky-400">
            </div>

            <div>
                <label for="password" class="text-sm font-semibold text-slate-600">Password</label>
                <input id="password" name="password" type="password" required class="mt-1 w-full rounded-lg border-slate-200 px-3 py-2 text-sm focus:border-sky-400 focus:ring-sky-400">
            </div>

            <div class="flex items-center justify-between text-sm">
                <label class="flex items-center gap-2 font-medium text-slate-600">
                    <input type="checkbox" name="remember" class="rounded border-slate-300 text-sky-600 focus:ring-sky-500" {{ old('remember') ? 'checked' : '' }}>
                    Remember me
                </label>
                <span class="text-slate-400">Need help? Contact your admin.</span>
            </div>

            @if ($errors->any())
                <div class="rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 text-sm text-rose-700">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <button type="submit" class="w-full rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-slate-700">Sign in</button>
        </form>
    </div>
</div>
@endsection
