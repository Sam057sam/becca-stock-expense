@extends('layouts.app')

@section('content')
<div class="flex flex-col items-center justify-center gap-6 py-16">
    <div class="rounded-3xl border border-slate-200 bg-white px-10 py-12 text-center shadow-sm">
        <p class="text-sm font-semibold uppercase tracking-widest text-sky-500">404</p>
        <h1 class="mt-4 text-3xl font-semibold text-slate-900">We could not find that page</h1>
        <p class="mt-3 max-w-xl text-sm text-slate-500">The address may be incorrect or the page might have been moved. Your navigation and workspace remain available below.</p>
        <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
            <a href="{{ url()->previous() ?: route('dashboard') }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:border-slate-300">
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
                Go back
            </a>
            @auth
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-sky-700">
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 9.75L12 4.5l9 5.25-9 5.25-9-5.25z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 14.25l9 5.25 9-5.25" />
                </svg>
                Back to dashboard
            </a>
            @endauth
        </div>
    </div>

    <div class="w-full max-w-5xl">
        @auth
            <p class="mb-3 text-xs font-semibold uppercase tracking-wide text-slate-500">Quick links</p>
            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                <a href="{{ route('dashboard') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-sky-200 hover:text-sky-700">Dashboard</a>
                <a href="{{ route('products.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-sky-200 hover:text-sky-700">Products</a>
                <a href="{{ route('warehouses.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-sky-200 hover:text-sky-700">Warehouses</a>
                <a href="{{ route('units.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-sky-200 hover:text-sky-700">Units</a>
                <a href="{{ route('customers.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-sky-200 hover:text-sky-700">Customers</a>
                <a href=\"{{ route('suppliers.index') }}\" class=\"rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-sky-200 hover:text-sky-700\">Suppliers</a>\n            </div>
        @else
            <p class="text-center text-sm text-slate-500">If you believe this is a mistake, sign in and try again.</p>
        @endauth
    </div>
</div>
@endsection
